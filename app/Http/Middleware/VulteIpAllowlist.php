<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * VulteIpAllowlist
 *
 * SECURITY BLOCKER INTERIM MITIGATION
 * =====================================
 * Vulte does not currently publish a HMAC/signature mechanism for webhooks.
 * This middleware rejects requests from IPs not in the configured allowlist
 * as a defence-in-depth layer.
 *
 * This is NOT a replacement for cryptographic verification. It does not
 * protect against requests from a compromised Vulte server or from an
 * attacker who discovers the allowlisted IP range.
 *
 * ACTION REQUIRED before production:
 *   Contact Vulte support to obtain a signing secret or server-side
 *   verification endpoint. Replace this middleware with HMAC verification
 *   once the contract is confirmed.
 *
 * Configuration:
 *   Set VULTE_WEBHOOK_IP_ALLOWLIST in .env as a comma-separated list of IPs.
 *   Set to * to bypass the check (testing only — NEVER in production).
 *   Leave empty to reject ALL Vulte webhooks (safe default).
 *
 * See: config/vulte.php
 */
class VulteIpAllowlist
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $allowlist = config('vulte.webhook_ip_allowlist', []);

        // Wildcard '*' disables the check — for testing only.
        if (in_array('*', $allowlist, true)) {
            Log::debug('VulteIpAllowlist: wildcard bypass active — for testing only');
            return $next($request);
        }

        if (empty($allowlist)) {
            // No allowlist configured — reject all. Safe default.
            Log::warning('VulteIpAllowlist: VULTE_WEBHOOK_IP_ALLOWLIST is empty — rejecting all Vulte webhooks. Configure the allowlist in .env.');
            return response('not configured', 403);
        }

        $clientIp = $request->ip();

        if (!in_array($clientIp, $allowlist, true)) {
            Log::warning('VulteIpAllowlist: rejected request from non-allowlisted IP', [
                'ip' => $clientIp,
            ]);
            return response('forbidden', 403);
        }

        return $next($request);
    }
}
