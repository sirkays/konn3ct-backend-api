<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin JWT Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for issuing and validating signed JWT access and refresh
    | tokens for the Konn3ct Global Admin Portal.
    |
    */

    'jwt' => [
        'access_secret' => env('ADMIN_JWT_ACCESS_SECRET'),
        'refresh_secret' => env('ADMIN_JWT_REFRESH_SECRET'),
        'issuer' => env('ADMIN_JWT_ISSUER', 'konn3ct-api'),
        'audience' => env('ADMIN_JWT_AUDIENCE', 'konn3ct-admin'),
        'access_ttl' => (int) env('ADMIN_ACCESS_TOKEN_TTL', 900), // 15 minutes
        'refresh_ttl' => (int) env('ADMIN_REFRESH_TOKEN_TTL', 604800), // 7 days
    ],

    /*
    |--------------------------------------------------------------------------
    | Refresh Token Cookie Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for sending the refresh token in an HTTP-only cookie.
    |
    */

    'cookie' => [
        'name' => env('ADMIN_REFRESH_COOKIE_NAME', 'konn3ct_admin_refresh_token'),
        'secure' => filter_var(env('ADMIN_REFRESH_COOKIE_SECURE', false), FILTER_VALIDATE_BOOLEAN),
        'same_site' => env('ADMIN_REFRESH_COOKIE_SAME_SITE', 'lax'),
        'path' => '/api/v1/admin/auth',
    ],

    /*
    |--------------------------------------------------------------------------
    | Failed Login Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Max failed authentication attempts allowed within decay_seconds before HTTP 429.
    |
    */

    'rate_limit' => [
        'max_attempts' => (int) env('ADMIN_LOGIN_MAX_ATTEMPTS', 5),
        'decay_seconds' => (int) env('ADMIN_LOGIN_DECAY_SECONDS', 900),
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Admin Permissions
    |--------------------------------------------------------------------------
    */

    'default_permissions' => [
        'admin.*',
    ],

];
