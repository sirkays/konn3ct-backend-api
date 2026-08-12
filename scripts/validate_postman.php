<?php
$f = 'postman/Konn3ct-Backend-API.postman_collection.json';
$data = json_decode(file_get_contents($f), true);
echo 'JSON valid: ' . (json_last_error() === JSON_ERROR_NONE ? 'YES' : 'NO: ' . json_last_error_msg()) . PHP_EOL;
echo 'Total top-level folders: ' . count($data['item']) . PHP_EOL;
$last = $data['item'][count($data['item'])-1];
echo 'Last folder: ' . $last['name'] . PHP_EOL;
echo 'Odoo requests: ' . count($last['item']) . PHP_EOL;
foreach ($last['item'] as $req) {
    echo '  - ' . $req['name'] . PHP_EOL;
    foreach ($req['request']['header'] as $h) {
        if ($h['key'] === 'X-Konn3ct-Event-Id') {
            echo '    Event-Id value: ' . $h['value'] . PHP_EOL;
        }
    }
}
