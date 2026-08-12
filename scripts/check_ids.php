<?php
$f = 'postman/Konn3ct-Backend-API.postman_collection.json';
$d = json_decode(file_get_contents($f), true);

function checkIds(array $items, string $path = '') {
    foreach ($items as $i => $item) {
        $loc = $path . '[' . $i . '] ' . ($item['name'] ?? '?');
        if (!isset($item['id'])) echo 'MISSING id: ' . $loc . PHP_EOL;
        if (isset($item['event'])) {
            foreach ($item['event'] as $ei => $ev) {
                if (!isset($ev['id'])) echo 'MISSING event id at: ' . $loc . '/event[' . $ei . ']' . PHP_EOL;
                if (!isset($ev['script']['id'])) echo 'MISSING script id at: ' . $loc . '/event[' . $ei . ']/script' . PHP_EOL;
            }
        }
        if (isset($item['item'])) checkIds($item['item'], $loc . '/');
    }
}
checkIds($d['item']);
echo 'ID check complete.' . PHP_EOL;
