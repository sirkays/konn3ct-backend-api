<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Odoo 19 Integration — Master Enable
    |--------------------------------------------------------------------------
    | When false, no signal will be dispatched to Odoo under any circumstances.
    | Keep false until the endpoint contract and credentials are confirmed.
    */
    'enabled' => (bool) env('ODOO19_INTEGRATION_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Odoo 19 Base URL
    |--------------------------------------------------------------------------
    | The scheme + host (no trailing slash) of the Odoo 19 inbound API.
    | Must be HTTPS in production. Validated at dispatch time when not local/testing.
    */
    'base_url' => env('ODOO19_BASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Endpoint Paths (per signal)
    |--------------------------------------------------------------------------
    | These are appended to base_url. Keep blank until Odoo supplies them.
    */
    'endpoints' => [
        'user_registered'     => env('ODOO19_USER_REGISTERED_PATH', ''),
        'usage_metrics'       => env('ODOO19_USAGE_METRICS_PATH', ''),
        'payment_success'     => env('ODOO19_PAYMENT_SUCCESS_PATH', ''),
        'payment_failed'      => env('ODOO19_PAYMENT_FAILED_PATH', ''),
        'paid_event_purchase' => env('ODOO19_PAID_EVENT_PURCHASE_PATH', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    | api_token  : Bearer token for Authorization header.
    | signing_secret : HMAC-SHA256 secret for X-Konn3ct-Signature header.
    |               Leave blank to skip HMAC (header will be omitted).
    */
    'api_token'      => env('ODOO19_API_TOKEN', ''),
    'signing_secret' => env('ODOO19_SIGNING_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    | Use a dedicated queue so Odoo delivery is isolated from other jobs.
    | Do NOT change the global QUEUE_CONNECTION. Only this integration uses
    | the configured connection and queue name.
    */
    'queue_connection' => env('ODOO19_QUEUE_CONNECTION', 'database'),
    'queue_name'       => env('ODOO19_QUEUE_NAME', 'odoo'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Timeouts (seconds)
    |--------------------------------------------------------------------------
    */
    'connect_timeout' => (int) env('ODOO19_CONNECT_TIMEOUT_SECONDS', 5),
    'request_timeout' => (int) env('ODOO19_REQUEST_TIMEOUT_SECONDS', 15),

    /*
    |--------------------------------------------------------------------------
    | Retry Configuration
    |--------------------------------------------------------------------------
    | max_attempts : How many times the job may be attempted (including first).
    | backoff       : Comma-separated seconds between attempts (queue backoff).
    */
    'max_attempts' => (int) env('ODOO19_MAX_ATTEMPTS', 5),
    'backoff'       => array_map(
        'intval',
        explode(',', env('ODOO19_BACKOFF_SECONDS', '60,300,900,3600,21600'))
    ),

    /*
    |--------------------------------------------------------------------------
    | Usage Metrics Command
    |--------------------------------------------------------------------------
    */
    'usage_metrics_enabled' => (bool) env('ODOO19_USAGE_METRICS_ENABLED', false),
    'usage_metrics_time'    => env('ODOO19_USAGE_METRICS_TIME', '01:00'),

    /*
    |--------------------------------------------------------------------------
    | Schema Version
    |--------------------------------------------------------------------------
    | The value sent in X-Konn3ct-Schema-Version for all signals.
    */
    'schema_version' => '1.0',
];
