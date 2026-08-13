<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminLoginRequest;
use App\Models\AdminRefreshToken;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use App\Services\Admin\AdministratorPermissionsResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class AuthController extends Controller
{
    protected $jwtService;
    protected $permissionsResolver;

    public function __construct(
        AdminJwtService $jwtService,
        AdministratorPermissionsResolver $permissionsResolver
    ) {
        $this->jwtService = $jwtService;
        $this->permissionsResolver = $permissionsResolver;
    }

    /**
     * Authenticate Konn3ct Global Admin Portal users.
     *
     * @param AdminLoginRequest $request
     * @return JsonResponse
     */
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $email = $request->input('email');
        $ip = $request->ip();
        $rateLimitKey = 'admin_login_failed:' . md5($email) . ':' . $ip;

        $maxAttempts = (int) config('admin_auth.rate_limit.max_attempts', 5);
        $decaySeconds = (int) config('admin_auth.rate_limit.decay_seconds', 900);

        // Enforce rate limiting on failed attempts
        if (RateLimiter::tooManyAttempts($rateLimitKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);

            return response()->json([
                'success' => false,
                'code' => 'TOO_MANY_LOGIN_ATTEMPTS',
                'message' => "Too many login attempts. Please try again in {$seconds} seconds.",
                'retry_after' => $seconds,
            ], 429)->header('Retry-After', (string) $seconds);
        }

        $user = User::where('email', $email)->first();

        // Administrator verification criteria
        $isValidPassword = $user && Hash::check($request->input('password'), $user->password);
        $isAdminRole = $user && strtolower((string) $user->type) === 'admin';

        $isBlockedOrSuspended = false;

        // Check new account_status field (authoritative moderation status)
        if ($user && $user->account_status !== null) {
            $accountStatus = strtoupper((string) $user->account_status);
            if (in_array($accountStatus, ['SUSPENDED', 'BANNED'], true)) {
                $isBlockedOrSuspended = true;
            }
        }

        // Legacy backward-compat: also check users.status
        if (!$isBlockedOrSuspended && $user && $user->status !== null) {
            $legacyStatus = strtolower((string) $user->status);
            $disabledStatuses = ['suspended', 'disabled', 'blocked', 'inactive', 'deactivated', 'banned'];
            if (in_array($legacyStatus, $disabledStatuses, true)) {
                $isBlockedOrSuspended = true;
            }
        }

        if (!$user || !$isValidPassword || !$isAdminRole || $isBlockedOrSuspended) {
            $this->handleFailedAttempt($rateLimitKey, $decaySeconds, $email, $ip, $request->userAgent(), 'invalid_credentials_or_disabled');

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        // MFA Handling
        if (!empty($user->two_factor_secret)) {
            $mfaCode = $request->input('mfa_code');

            if ($mfaCode === null || $mfaCode === '') {
                $this->handleFailedAttempt($rateLimitKey, $decaySeconds, $email, $ip, $request->userAgent(), 'mfa_required_missing_code');

                return response()->json([
                    'success' => false,
                    'code' => 'MFA_REQUIRED',
                    'message' => 'Multi-factor authentication is required.',
                    'data' => [
                        'mfa_required' => true,
                    ],
                ], 202);
            }

            // Verify MFA code using Fortify's provider
            $isValidMfa = false;
            try {
                $tfaProvider = app(TwoFactorAuthenticationProvider::class);
                $secret = decrypt($user->two_factor_secret);
                $isValidMfa = (bool) $tfaProvider->verify($secret, $mfaCode);
            } catch (\Throwable $e) {
                $isValidMfa = false;
            }

            if (!$isValidMfa) {
                $this->handleFailedAttempt($rateLimitKey, $decaySeconds, $email, $ip, $request->userAgent(), 'invalid_mfa_code');

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }
        }

        // Successful authentication
        if (Hash::needsRehash($user->password)) {
            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        RateLimiter::clear($rateLimitKey);

        $permissions = $this->permissionsResolver->resolve($user);
        $accessTokenData = $this->jwtService->issueAccessToken($user, $permissions);
        $refreshTokenData = $this->jwtService->issueRefreshToken($user, $ip, $request->userAgent());

        $adminProfile = [
            'id' => (int) $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'role' => 'admin',
            'permissions' => $permissions,
            'profile_photo_url' => $user->profile_photo_url ?? null,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'access_token' => $accessTokenData['token'],
                'token_type' => 'Bearer',
                'expires_in' => $accessTokenData['expires_in'],
                'admin' => $adminProfile,
            ],
        ], 200)
        ->header('Cache-Control', 'no-store')
        ->header('Pragma', 'no-cache')
        ->withCookie($refreshTokenData['cookie']);
    }

    /**
     * Refresh administrator access token and rotate refresh token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        $cookieName = config('admin_auth.cookie.name', 'konn3ct_admin_refresh_token');
        $rawJwt = $request->cookie($cookieName);

        // Reject missing, non-string, empty, or oversized cookie before parsing
        if ($rawJwt === null || !is_string($rawJwt) || trim($rawJwt) === '' || strlen($rawJwt) > 4096) {
            Log::warning('Admin refresh failed: missing, invalid, or oversized refresh cookie', [
                'event' => 'admin_refresh_failed',
                'reason' => 'missing_or_invalid_cookie',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                'timestamp' => now()->toIso8601String(),
            ]);

            return $this->unauthorizedRefreshResponse();
        }

        // Cryptographic validation of refresh JWT
        try {
            $token = $this->jwtService->validateRefreshToken($rawJwt);
        } catch (\InvalidArgumentException $e) {
            Log::warning('Admin refresh failed: JWT validation error', [
                'event' => 'admin_refresh_failed',
                'reason' => $e->getMessage(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                'timestamp' => now()->toIso8601String(),
            ]);

            return $this->unauthorizedRefreshResponse();
        }

        $jti = (string) $token->claims()->get('jti');
        $sub = (string) $token->claims()->get('sub');
        $tokenHash = hash('sha256', $jti);

        // Atomic database session validation and token rotation
        try {
            $rotationResult = DB::transaction(function () use ($tokenHash, $sub, $request) {
                $sessionRecord = AdminRefreshToken::where('token_id', $tokenHash)
                    ->lockForUpdate()
                    ->first();

                if (! $sessionRecord) {
                    Log::warning('Admin refresh failed: untracked token session', [
                        'event' => 'admin_refresh_failed',
                        'reason' => 'untracked_session',
                        'jti_hash' => $tokenHash,
                        'user_id' => $sub,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                        'timestamp' => now()->toIso8601String(),
                    ]);

                    return null;
                }

                if ($sessionRecord->revoked_at !== null) {
                    Log::warning('Revoked admin refresh token replay detected', [
                        'event' => 'admin_refresh_replay_detected',
                        'reason' => 'revoked_token_replay',
                        'jti_hash' => $tokenHash,
                        'user_id' => $sub,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                        'timestamp' => now()->toIso8601String(),
                    ]);

                    return null;
                }

                if ($sessionRecord->expires_at <= now()) {
                    Log::warning('Admin refresh failed: expired database session', [
                        'event' => 'admin_refresh_failed',
                        'reason' => 'db_session_expired',
                        'jti_hash' => $tokenHash,
                        'user_id' => $sub,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                        'timestamp' => now()->toIso8601String(),
                    ]);

                    return null;
                }

                if ((string) $sessionRecord->user_id !== $sub) {
                    Log::warning('Admin refresh failed: token subject user_id mismatch', [
                        'event' => 'admin_refresh_failed',
                        'reason' => 'user_id_mismatch',
                        'jti_hash' => $tokenHash,
                        'user_id' => $sub,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                        'timestamp' => now()->toIso8601String(),
                    ]);

                    return null;
                }

                $user = User::find($sub);

                if (! $user || strtolower((string) $user->type) !== 'admin') {
                    Log::warning('Admin refresh failed: non-existent or non-admin account', [
                        'event' => 'admin_refresh_failed',
                        'reason' => 'invalid_or_non_admin_user',
                        'jti_hash' => $tokenHash,
                        'user_id' => $sub,
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                        'timestamp' => now()->toIso8601String(),
                    ]);

                    return null;
                }

                // Check new account_status field (authoritative moderation status)
                if ($user->account_status !== null) {
                    $accountStatus = strtoupper((string) $user->account_status);
                    if (in_array($accountStatus, ['SUSPENDED', 'BANNED'], true)) {
                        Log::warning('Admin refresh failed: account moderation status', [
                            'event'          => 'admin_refresh_failed',
                            'reason'         => 'account_moderation_status',
                            'jti_hash'       => $tokenHash,
                            'user_id'        => $sub,
                            'ip'             => $request->ip(),
                            'user_agent'     => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                            'timestamp'      => now()->toIso8601String(),
                        ]);

                        return null;
                    }
                }

                // Legacy backward-compat: also check users.status
                if ($user->status !== null) {
                    $legacyStatus = strtolower((string) $user->status);
                    $disabledStatuses = ['suspended', 'disabled', 'blocked', 'inactive', 'deactivated', 'banned'];
                    if (in_array($legacyStatus, $disabledStatuses, true)) {
                        Log::warning('Admin refresh failed: legacy status blocks access', [
                            'event'         => 'admin_refresh_failed',
                            'reason'        => 'legacy_status_blocked',
                            'jti_hash'      => $tokenHash,
                            'user_id'       => $sub,
                            'ip'            => $request->ip(),
                            'user_agent'    => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
                            'timestamp'     => now()->toIso8601String(),
                        ]);

                        return null;
                    }
                }

                // Revoke old refresh token
                $sessionRecord->revoked_at = now();
                $sessionRecord->save();

                // Rotate session: issue new refresh token & access token
                $permissions = $this->permissionsResolver->resolve($user);
                $accessTokenData = $this->jwtService->issueAccessToken($user, $permissions);
                $refreshTokenData = $this->jwtService->issueRefreshToken($user, $request->ip(), $request->userAgent());

                return [
                    'user' => $user,
                    'permissions' => $permissions,
                    'access_token_data' => $accessTokenData,
                    'refresh_token_data' => $refreshTokenData,
                ];
            });

            if (! $rotationResult) {
                return $this->unauthorizedRefreshResponse();
            }

            $user = $rotationResult['user'];
            $permissions = $rotationResult['permissions'];
            $accessTokenData = $rotationResult['access_token_data'];
            $refreshTokenData = $rotationResult['refresh_token_data'];

            $adminProfile = [
                'id' => (int) $user->id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'role' => 'admin',
                'permissions' => $permissions,
                'profile_photo_url' => $user->profile_photo_url ?? null,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Session refreshed successfully.',
                'data' => [
                    'access_token' => $accessTokenData['token'],
                    'token_type' => 'Bearer',
                    'expires_in' => $accessTokenData['expires_in'],
                    'admin' => $adminProfile,
                ],
            ], 200)
            ->header('Cache-Control', 'no-store')
            ->header('Pragma', 'no-cache')
            ->withCookie($refreshTokenData['cookie']);

        } catch (\Throwable $e) {
            // Unexpected database/system exception: let it bubble up for proper 500 handling
            throw $e;
        }
    }

    /**
     * Build generic 401 Unauthorized refresh error response with expired cookie header.
     *
     * @return JsonResponse
     */
    protected function unauthorizedRefreshResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => 'INVALID_REFRESH_TOKEN',
            'message' => 'Authentication session is invalid or has expired.',
        ], 401)
        ->header('Cache-Control', 'no-store')
        ->header('Pragma', 'no-cache')
        ->withCookie($this->jwtService->forgetRefreshCookie());
    }

    /**
     * Record rate limiter failure hit and security warning log.
     */
    protected function handleFailedAttempt(
        string $rateLimitKey,
        int $decaySeconds,
        string $email,
        ?string $ip,
        ?string $userAgent,
        string $reason
    ): void {
        RateLimiter::hit($rateLimitKey, $decaySeconds);

        Log::warning('Admin authentication failed', [
            'event' => 'admin_login_failed',
            'email_hash' => hash('sha256', $email),
            'ip' => $ip,
            'user_agent' => $userAgent ? Str::limit($userAgent, 200) : null,
            'reason' => $reason,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
