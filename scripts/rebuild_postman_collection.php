<?php
/**
 * Rebuilds the Konn3ct Postman collection.
 * Adds required 'id' fields to every event/script to prevent
 * the "e is not iterable" Postman crash.
 *
 * Run: php scripts/rebuild_postman_collection.php
 */

$outputPath = __DIR__ . '/../postman/Konn3ct-Backend-API.postman_collection.json';

// ---------------------------------------------------------------------------
// Helper: build a UUID-like id (Postman just needs a unique string)
// ---------------------------------------------------------------------------
function uid(): string
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// ---------------------------------------------------------------------------
// Helper: build a test event block (Postman requires id on event AND script)
// ---------------------------------------------------------------------------
function makeTestEvent(array $lines): array
{
    return [
        'id'     => uid(),
        'listen' => 'test',
        'script' => [
            'id'   => uid(),
            'exec' => $lines,
            'type' => 'text/javascript',
        ],
    ];
}

// ---------------------------------------------------------------------------
// Helper: build a full request item
// ---------------------------------------------------------------------------
function makeItem(string $name, string $method, string $url, array $headers, string $rawBody = '', array $testLines = []): array
{
    $request = [
        'method' => $method,
        'header' => $headers,
        'url'    => $url,
    ];

    if ($rawBody !== '') {
        $request['body'] = [
            'mode'    => 'raw',
            'raw'     => $rawBody,
            'options' => ['raw' => ['language' => 'json']],
        ];
    }

    $item = [
        'id'       => uid(),
        'name'     => $name,
        'request'  => $request,
        'response' => [],
    ];

    if (!empty($testLines)) {
        $item['event'] = [makeTestEvent($testLines)];
    }

    return $item;
}

// ---------------------------------------------------------------------------
// Helper: build a folder
// ---------------------------------------------------------------------------
function makeFolder(string $name, array $items): array
{
    return [
        'id'   => uid(),
        'name' => $name,
        'item' => $items,
    ];
}

// ---------------------------------------------------------------------------
// Standard header sets
// ---------------------------------------------------------------------------
$jsonHeaders = [
    ['key' => 'Content-Type', 'value' => 'application/json'],
    ['key' => 'Accept',       'value' => 'application/json'],
];

// ---------------------------------------------------------------------------
// Admin API requests
// ---------------------------------------------------------------------------
$adminAuthItems = [

    makeItem(
        'Admin Login – Success',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"email\": \"{{admin_email}}\",\n  \"password\": \"{{admin_password}}\"\n}",
        [
            "pm.test('Status code is 200', function () {",
            "    pm.response.to.have.status(200);",
            "});",
            "var json = pm.response.json();",
            "if (json.data && json.data.access_token) {",
            "    pm.collectionVariables.set('admin_access_token', json.data.access_token);",
            "}",
        ]
    ),

    makeItem(
        'Admin Login – Invalid Credentials',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"email\": \"{{admin_email}}\",\n  \"password\": \"wrong_password\"\n}",
        [
            "pm.test('Status code is 401', function () {",
            "    pm.response.to.have.status(401);",
            "});",
        ]
    ),

    makeItem(
        'Admin Login – Missing Email (422)',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"password\": \"{{admin_password}}\"\n}",
        [
            "pm.test('Status code is 422', function () {",
            "    pm.response.to.have.status(422);",
            "});",
        ]
    ),

    makeItem(
        'Admin Login – MFA Required, No Code (202)',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"email\": \"{{admin_mfa_email}}\",\n  \"password\": \"{{admin_mfa_password}}\"\n}",
        [
            "pm.test('Status code is 202', function () {",
            "    pm.response.to.have.status(202);",
            "});",
        ]
    ),

    makeItem(
        'Admin Login – MFA Success',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"email\": \"{{admin_mfa_email}}\",\n  \"password\": \"{{admin_mfa_password}}\",\n  \"mfa_code\": \"{{admin_mfa_code}}\"\n}",
        [
            "pm.test('Status code is 200', function () {",
            "    pm.response.to.have.status(200);",
            "});",
        ]
    ),

    makeItem(
        'Admin Login – MFA Wrong Code (401)',
        'POST',
        '{{base_url}}/api/v1/admin/auth/login',
        $jsonHeaders,
        "{\n  \"email\": \"{{admin_mfa_email}}\",\n  \"password\": \"{{admin_mfa_password}}\",\n  \"mfa_code\": \"000000\"\n}",
        [
            "pm.test('Status code is 401', function () {",
            "    pm.response.to.have.status(401);",
            "});",
        ]
    ),

    makeItem(
        'Admin Refresh Token – Success',
        'POST',
        '{{base_url}}/api/v1/admin/auth/refresh',
        [['key' => 'Accept', 'value' => 'application/json']],
        '',
        [
            "pm.test('Status code is 200', function () {",
            "    pm.response.to.have.status(200);",
            "});",
            "var json = pm.response.json();",
            "if (json.data && json.data.access_token) {",
            "    pm.collectionVariables.set('admin_access_token', json.data.access_token);",
            "}",
        ]
    ),

    makeItem(
        'Admin Refresh Token – No Cookie (401)',
        'POST',
        '{{base_url}}/api/v1/admin/auth/refresh',
        [['key' => 'Accept', 'value' => 'application/json']],
        '',
        [
            "pm.test('Status code is 401', function () {",
            "    pm.response.to.have.status(401);",
            "});",
        ]
    ),
];

// ---------------------------------------------------------------------------
// Odoo 19 signal headers helper
// ---------------------------------------------------------------------------
function odooHeaders(string $eventName, string $idempKey): array
{
    return [
        ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}'],
        ['key' => 'Content-Type',             'value' => 'application/json'],
        ['key' => 'Accept',                   'value' => 'application/json'],
        ['key' => 'Idempotency-Key',          'value' => $idempKey],
        ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}'],
        ['key' => 'X-Konn3ct-Event-Name',     'value' => $eventName],
        ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0'],
    ];
}

$okTest = [
    "pm.test('Status code is 2xx', function () {",
    "    pm.response.to.be.success;",
    "});",
];

$odooItems = [

    makeItem(
        'API-026: USER_REGISTERED',
        'POST',
        '{{ODOO19_BASE_URL}}/api/v1/user/registered',
        odooHeaders('USER_REGISTERED', 'USER_REGISTERED:123'),
        json_encode([
            'user_id'       => 123,
            'name'          => 'Amina Bello',
            'email'         => 'amina@example.com',
            'country_code'  => 'NG',
            'referral_code' => 'ABC123',
            'lead_source'   => 'web',
            'ip'            => '192.168.1.1',
        ], JSON_PRETTY_PRINT),
        $okTest
    ),

    makeItem(
        'API-027: USAGE_METRICS',
        'POST',
        '{{ODOO19_BASE_URL}}/api/v1/usage',
        odooHeaders('USAGE_METRICS', 'USAGE_METRICS:123:2026-08-12'),
        json_encode([
            'user_id'         => 123,
            'meetings_hosted' => 5,
        ], JSON_PRETTY_PRINT),
        $okTest
    ),

    makeItem(
        'API-028: PAYMENT_SUCCESS',
        'POST',
        '{{ODOO19_BASE_URL}}/api/v1/payment/success',
        odooHeaders('PAYMENT_SUCCESS', 'PAYMENT_SUCCESS:paystack:TX001'),
        json_encode([
            'transaction_reference' => 'TX001',
            'user_id'               => 123,
            'amount'                => 27000.00,
            'currency'              => 'NGN',
            'plan_or_event_id'      => 3,
            'gateway'               => 'paystack',
        ], JSON_PRETTY_PRINT),
        $okTest
    ),

    makeItem(
        'API-028: PAYMENT_FAILED',
        'POST',
        '{{ODOO19_BASE_URL}}/api/v1/payment/failed',
        odooHeaders('PAYMENT_FAILED', 'PAYMENT_FAILED:paystack:TX002'),
        json_encode([
            'transaction_reference' => 'TX002',
            'user_id'               => 123,
            'amount'                => 27000.00,
            'gateway'               => 'paystack',
            'error_code'            => 'insufficient_funds',
            'abandoned_cart'        => false,
        ], JSON_PRETTY_PRINT),
        $okTest
    ),

    makeItem(
        'API-028: PAID_EVENT_PURCHASE',
        'POST',
        '{{ODOO19_BASE_URL}}/api/v1/payment/event',
        odooHeaders('PAID_EVENT_PURCHASE', 'PAID_EVENT_PURCHASE:45:REF001'),
        json_encode([
            'user_id'        => 123,
            'event_id'       => 45,
            'ticket_price'   => 5000.00,
            'payment_status' => 'paid',
        ], JSON_PRETTY_PRINT),
        $okTest
    ),
];

// ---------------------------------------------------------------------------
// Assemble the full collection
// ---------------------------------------------------------------------------
$collection = [
    'info' => [
        '_postman_id' => '87ca140a-5d78-4c56-9233-2f95770b8ea5',
        'name'        => 'Konn3ct Backend API',
        'description' => 'Konn3ct Backend API — Admin Auth and Odoo 19 Outbound Signals (API-026, API-027, API-028)',
        'schema'      => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
    ],
    'item' => [
        makeFolder('Admin API', [
            makeFolder('Authentication', $adminAuthItems),
        ]),
        makeFolder('Odoo 19 Integration Contract Requests', $odooItems),
    ],
    'variable' => [
        ['key' => 'base_url',           'value' => 'http://127.0.0.1:8000',   'type' => 'string'],
        ['key' => 'admin_email',        'value' => 'admin@example.com',        'type' => 'string'],
        ['key' => 'admin_password',     'value' => 'password',                 'type' => 'string'],
        ['key' => 'admin_mfa_email',    'value' => 'admin_mfa@example.com',    'type' => 'string'],
        ['key' => 'admin_mfa_password', 'value' => 'password',                 'type' => 'string'],
        ['key' => 'admin_mfa_code',     'value' => '123456',                   'type' => 'string'],
        ['key' => 'admin_access_token', 'value' => '',                         'type' => 'string'],
        ['key' => 'ODOO19_BASE_URL',    'value' => 'https://odoo.example.com', 'type' => 'string'],
        ['key' => 'ODOO19_API_TOKEN',   'value' => '',                         'type' => 'string'],
    ],
];

// ---------------------------------------------------------------------------
// Write and validate
// ---------------------------------------------------------------------------
$output = json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

json_decode($output);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'ERROR: Output JSON is invalid: ' . json_last_error_msg() . PHP_EOL;
    exit(1);
}

file_put_contents($outputPath, $output);
echo 'Done. Collection written to: ' . realpath($outputPath) . PHP_EOL;
