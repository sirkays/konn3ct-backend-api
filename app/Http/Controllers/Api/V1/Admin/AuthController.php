<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\AdminLoginRequest;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use App\Services\Admin\AdministratorPermissionsResolver;
use Illuminate\Http\JsonResponse;
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
        if ($user && $user->status !== null) {
            $status = strtolower((string) $user->status);
            $disabledStatuses = ['suspended', 'disabled', 'blocked', 'inactive', 'deactivated', 'banned'];
            if (in_array($status, $disabledStatuses, true)) {
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
