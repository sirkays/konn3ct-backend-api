<?php

/**
 * Adds the "Odoo 19 Integration Contract Requests" folder to the
 * Konn3ct Postman collection without corrupting existing content.
 *
 * Run: php scripts/add_odoo_postman_folder.php
 */

$collectionPath = __DIR__ . '/../postman/Konn3ct-Backend-API.postman_collection.json';

$collection = json_decode(file_get_contents($collectionPath), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'ERROR: Could not parse collection JSON: ' . json_last_error_msg() . PHP_EOL;
    exit(1);
}

// Remove any existing Odoo folder so this script is idempotent.
$collection['item'] = array_values(array_filter(
    $collection['item'],
    fn ($folder) => $folder['name'] !== 'Odoo 19 Integration Contract Requests'
));

// Add ODOO19 collection variables if not already present.
$existingKeys = array_column($collection['variable'] ?? [], 'key');
$odooVars = [
    ['key' => 'ODOO19_BASE_URL',    'value' => 'https://odoo.example.com', 'type' => 'string'],
    ['key' => 'ODOO19_API_TOKEN',   'value' => '',                          'type' => 'string'],
];
foreach ($odooVars as $var) {
    if (!in_array($var['key'], $existingKeys, true)) {
        $collection['variable'][] = $var;
    }
}

// Build the Odoo Integration folder.
$odooFolder = [
    'name' => 'Odoo 19 Integration Contract Requests',
    'item' => [

        // API-026: USER_REGISTERED
        [
            'name'    => 'API-026: USER_REGISTERED',
            'request' => [
                'method' => 'POST',
                'header' => [
                    ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}', 'type' => 'text'],
                    ['key' => 'Content-Type',             'value' => 'application/json',            'type' => 'text'],
                    ['key' => 'Accept',                   'value' => 'application/json',            'type' => 'text'],
                    ['key' => 'Idempotency-Key',          'value' => 'USER_REGISTERED:123',         'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}',                   'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Name',     'value' => 'USER_REGISTERED',             'type' => 'text'],
                    ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0',                         'type' => 'text'],
                ],
                'body' => [
                    'mode'    => 'raw',
                    'raw'     => json_encode([
                        'user_id'       => 123,
                        'name'          => 'Amina Bello',
                        'email'         => 'amina@example.com',
                        'country_code'  => 'NG',
                        'referral_code' => 'ABC123',
                        'lead_source'   => 'web',
                        'ip'            => '192.168.1.1',
                    ], JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ],
                'url' => '{{ODOO19_BASE_URL}}/api/v1/user/registered',
            ],
        ],

        // API-027: USAGE_METRICS
        [
            'name'    => 'API-027: USAGE_METRICS',
            'request' => [
                'method' => 'POST',
                'header' => [
                    ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}',    'type' => 'text'],
                    ['key' => 'Content-Type',             'value' => 'application/json',              'type' => 'text'],
                    ['key' => 'Accept',                   'value' => 'application/json',              'type' => 'text'],
                    ['key' => 'Idempotency-Key',          'value' => 'USAGE_METRICS:123:2026-08-12',  'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}',                     'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Name',     'value' => 'USAGE_METRICS',                 'type' => 'text'],
                    ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0',                           'type' => 'text'],
                ],
                'body' => [
                    'mode'    => 'raw',
                    'raw'     => json_encode([
                        'user_id'         => 123,
                        'meetings_hosted' => 5,
                    ], JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ],
                'url' => '{{ODOO19_BASE_URL}}/api/v1/usage',
            ],
        ],

        // API-028: PAYMENT_SUCCESS
        [
            'name'    => 'API-028: PAYMENT_SUCCESS',
            'request' => [
                'method' => 'POST',
                'header' => [
                    ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}',       'type' => 'text'],
                    ['key' => 'Content-Type',             'value' => 'application/json',                 'type' => 'text'],
                    ['key' => 'Accept',                   'value' => 'application/json',                 'type' => 'text'],
                    ['key' => 'Idempotency-Key',          'value' => 'PAYMENT_SUCCESS:paystack:TX001',   'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}',                        'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Name',     'value' => 'PAYMENT_SUCCESS',                  'type' => 'text'],
                    ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0',                              'type' => 'text'],
                ],
                'body' => [
                    'mode'    => 'raw',
                    'raw'     => json_encode([
                        'transaction_reference' => 'TX001',
                        'user_id'              => 123,
                        'amount'               => 27000.00,
                        'currency'             => 'NGN',
                        'plan_or_event_id'     => 3,
                        'gateway'              => 'paystack',
                    ], JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ],
                'url' => '{{ODOO19_BASE_URL}}/api/v1/payment/success',
            ],
        ],

        // API-028: PAYMENT_FAILED
        [
            'name'    => 'API-028: PAYMENT_FAILED',
            'request' => [
                'method' => 'POST',
                'header' => [
                    ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}',               'type' => 'text'],
                    ['key' => 'Content-Type',             'value' => 'application/json',                         'type' => 'text'],
                    ['key' => 'Accept',                   'value' => 'application/json',                         'type' => 'text'],
                    ['key' => 'Idempotency-Key',          'value' => 'PAYMENT_FAILED:paystack:TX002',             'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}',                                 'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Name',     'value' => 'PAYMENT_FAILED',                            'type' => 'text'],
                    ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0',                                       'type' => 'text'],
                ],
                'body' => [
                    'mode'    => 'raw',
                    'raw'     => json_encode([
                        'transaction_reference' => 'TX002',
                        'user_id'              => 123,
                        'amount'               => 27000.00,
                        'gateway'              => 'paystack',
                        'error_code'           => 'insufficient_funds',
                        'abandoned_cart'       => false,
                    ], JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ],
                'url' => '{{ODOO19_BASE_URL}}/api/v1/payment/failed',
            ],
        ],

        // API-028: PAID_EVENT_PURCHASE
        [
            'name'    => 'API-028: PAID_EVENT_PURCHASE',
            'request' => [
                'method' => 'POST',
                'header' => [
                    ['key' => 'Authorization',            'value' => 'Bearer {{ODOO19_API_TOKEN}}',             'type' => 'text'],
                    ['key' => 'Content-Type',             'value' => 'application/json',                       'type' => 'text'],
                    ['key' => 'Accept',                   'value' => 'application/json',                       'type' => 'text'],
                    ['key' => 'Idempotency-Key',          'value' => 'PAID_EVENT_PURCHASE:45:REF001',          'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Id',       'value' => '{{$guid}}',                              'type' => 'text'],
                    ['key' => 'X-Konn3ct-Event-Name',     'value' => 'PAID_EVENT_PURCHASE',                    'type' => 'text'],
                    ['key' => 'X-Konn3ct-Schema-Version', 'value' => '1.0',                                    'type' => 'text'],
                ],
                'body' => [
                    'mode'    => 'raw',
                    'raw'     => json_encode([
                        'user_id'        => 123,
                        'event_id'       => 45,
                        'ticket_price'   => 5000.00,
                        'payment_status' => 'paid',
                    ], JSON_PRETTY_PRINT),
                    'options' => ['raw' => ['language' => 'json']],
                ],
                'url' => '{{ODOO19_BASE_URL}}/api/v1/payment/event',
            ],
        ],
    ],
];

$collection['item'][] = $odooFolder;

$output = json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// Validate the output before writing.
json_decode($output);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo 'ERROR: Output JSON is invalid: ' . json_last_error_msg() . PHP_EOL;
    exit(1);
}

file_put_contents($collectionPath, $output);
echo 'Done. Odoo folder added to collection.' . PHP_EOL;
