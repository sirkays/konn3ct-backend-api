<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\Admin\AdminJwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Admin JWT Authentication Middleware.
 *
 * Authenticates protected Admin API routes using access JWTs from
 * the Authorization: Bearer header only.
 *
 * Security rules enforced:
 * - Reads ONLY the Authorization: Bearer header (no cookies, no query params)
 * - Rejects: missing, malformed, empty, oversized (>4096 bytes), expired,
 *   tampered, wrong-secret, refresh-type, wrong-issuer, wrong-audience tokens
 * - Validates using AdminJwtService::validateAccessToken()
 * - Requires a valid numeric sub claim
 * - Loads the administrator from the database
 * - Confirms: user exists, type = admin, account_status is not suspended/banned
 * - Legacy null account_status is treated as ACTIVE (allowed)
 * - Also checks legacy users.status for backward compatibility
 * - Never logs the raw JWT
 * - Adds Cache-Control: no-store to all responses
 */
class AdminJwtMiddleware
{
    protected AdminJwtService $jwtService;

    public function __construct(AdminJwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization', '');

        // Validate Authorization header format
        if (
            !is_string($authHeader) ||
            trim($authHeader) === '' ||
            strlen($authHeader) > 4200 || // Bearer + space + up to ~4096 token chars
            !str_starts_with($authHeader, 'Bearer ')
        ) {
            return $this->unauthorizedResponse('Missing or malformed Authorization header.');
        }

        $rawJwt = trim(substr($authHeader, 7));

        if ($rawJwt === '' || strlen($rawJwt) > 4096) {
            return $this->unauthorizedResponse('Access token is missing or oversized.');
        }

        // Cryptographic validation — never log the raw token
        try {
            $token = $this->jwtService->validateAccessToken($rawJwt);
        } catch (\InvalidArgumentException $e) {
            Log::warning('Admin JWT validation failed', [
                'event'      => 'admin_jwt_auth_failed',
                'reason'     => $e->getMessage(),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent() ? Str::limit($request->userAgent(), 200) : null,
            ]);
            return $this->unauthorizedResponse('Invalid or expired access token.');
        }

        $claims = $token->claims();
        $sub    = (string) $claims->get('sub');

        // Require numeric sub claim
        if (!ctype_digit($sub) || (int) $sub <= 0) {
            Log::warning('Admin JWT rejected: non-numeric sub claim', [
                'event' => 'admin_jwt_auth_failed',
                'reason' => 'non_numeric_sub',
                'ip'    => $request->ip(),
            ]);
            return $this->unauthorizedResponse('Invalid token subject claim.');
        }

        $userId = (int) $sub;
        $user   = User::find($userId);

        // User must exist and be an admin
        if (!$user || strtolower((string) $user->type) !== 'admin') {
            Log::warning('Admin JWT rejected: user not found or not admin', [
                'event'   => 'admin_jwt_auth_failed',
                'reason'  => 'not_found_or_not_admin',
                'user_id' => $userId,
                'ip'      => $request->ip(),
            ]);
            return $this->unauthorizedResponse('Account not found or does not have admin role.');
        }

        // Check account_status (new moderation field) — null treated as ACTIVE
        if ($user->account_status !== null) {
            $accountStatus = strtoupper((string) $user->account_status);
            if (in_array($accountStatus, ['SUSPENDED', 'BANNED'], true)) {
                Log::warning('Admin JWT rejected: account moderation status', [
                    'event'          => 'admin_jwt_auth_failed',
                    'reason'         => 'account_moderation_status',
                    'user_id'        => $userId,
                    'account_status' => $accountStatus,
                    'ip'             => $request->ip(),
                ]);
                return $this->unauthorizedResponse('Administrator account is suspended or banned.');
            }
        }

        // Legacy backward-compat check on users.status (subscription/legacy status field)
        if ($user->status !== null) {
            $legacyStatus    = strtolower((string) $user->status);
            $disabledStatuses = ['suspended', 'disabled', 'blocked', 'inactive', 'deactivated', 'banned'];
            if (in_array($legacyStatus, $disabledStatuses, true)) {
                Log::warning('Admin JWT rejected: legacy status field blocks access', [
                    'event'         => 'admin_jwt_auth_failed',
                    'reason'        => 'legacy_status_blocked',
                    'user_id'       => $userId,
                    'legacy_status' => $legacyStatus,
                    'ip'            => $request->ip(),
                ]);
                return $this->unauthorizedResponse('Administrator account is restricted.');
            }
        }

        // Attach authenticated admin and validated claims to request
        $request->attributes->set('admin_user',   $user);
        $request->attributes->set('admin_claims',  $claims);
        $request->attributes->set('admin_user_id', $userId);

        $response = $next($request);

        return $response->header('Cache-Control', 'no-store');
    }

    protected function unauthorizedResponse(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'UNAUTHENTICATED',
            'message' => $message,
        ], 401)->header('Cache-Control', 'no-store');
    }
}
