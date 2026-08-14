<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SECURITY NOTICE — Vulte Webhook
    |--------------------------------------------------------------------------
    | Vulte does not currently provide a published HMAC/signature mechanism
    | for webhook delivery. This means any actor who discovers the webhook URL
    | can POST a fake payload and, without this mitigation, set
    | prereg_users.paid = 1 for any attendee.
    |
    | INTERIM MITIGATION: IP allowlist. Requests from IPs not in this list
    | are rejected with 403 before any payload is processed.
    |
    | ACTION REQUIRED (BEFORE PRODUCTION): Contact Vulte support and obtain:
    |   1. A signing secret (HMAC key), OR
    |   2. A confirmed server-side transaction verify endpoint
    | Replace this allowlist check with cryptographic verification once
    | the contract is available.
    |
    | To disable the allowlist (testing only): set VULTE_WEBHOOK_IP_ALLOWLIST=*
    | Do NOT use * in production.
    |--------------------------------------------------------------------------
    */

    /*
    | Comma-separated list of Vulte outbound IP addresses.
    | Set VULTE_WEBHOOK_IP_ALLOWLIST in .env to the IPs provided by Vulte.
    | Wildcard (*) disables the check — for testing only.
    */
    'webhook_ip_allowlist' => array_filter(
        array_map('trim', explode(',', env('VULTE_WEBHOOK_IP_ALLOWLIST', '')))
    ),

];
