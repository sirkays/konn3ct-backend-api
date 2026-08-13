<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Admin Permission Middleware.
 *
 * Reads permissions ONLY from cryptographically validated token claims
 * attached by AdminJwtMiddleware. Must run after AdminJwtMiddleware.
 *
 * Supported permissions:
 *   users:read       — List/search users
 *   users:suspend    — Suspend a user
 *   users:ban        — Ban a user
 *   financials:read  — View financial transactions
 *   admin.*          — Super-admin wildcard (authorizes all endpoints)
 *
 * Rules:
 * - Exact match: token permission must exactly equal the required permission
 * - Wildcard: admin.* grants access to all endpoints
 * - No substring matching
 * - Returns HTTP 403 FORBIDDEN when token is valid but lacks the required permission
 */
class AdminPermissionMiddleware
{
    /**
     * @param  Request $request
     * @param  Closure $next
     * @param  string  $requiredPermission  e.g. 'users:read'
     */
    public function handle(Request $request, Closure $next, string $requiredPermission)
    {
        $claims = $request->attributes->get('admin_claims');

        if (!$claims) {
            // AdminJwtMiddleware must run first
            return response()->json([
                'success' => false,
                'code'    => 'UNAUTHENTICATED',
                'message' => 'Authentication context missing. Ensure admin.jwt middleware is applied.',
            ], 401)->header('Cache-Control', 'no-store');
        }

        $tokenPermissions = $claims->get('permissions');

        // Permissions claim must be an array
        if (!is_array($tokenPermissions)) {
            return $this->forbiddenResponse($requiredPermission);
        }

        // Check super-admin wildcard first (exact match only — no substring)
        if (in_array('admin.*', $tokenPermissions, true)) {
            return $next($request);
        }

        // Check exact permission match
        if (in_array($requiredPermission, $tokenPermissions, true)) {
            return $next($request);
        }

        return $this->forbiddenResponse($requiredPermission);
    }

    protected function forbiddenResponse(string $requiredPermission): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'FORBIDDEN',
            'message' => "You do not have the required permission: {$requiredPermission}.",
        ], 403)->header('Cache-Control', 'no-store');
    }
}
