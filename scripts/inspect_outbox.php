<?php
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\OdooOutboundSignal;

// Show the last 10 USAGE_METRICS signals
$signals = OdooOutboundSignal::where('event_name', 'USAGE_METRICS')
    ->orderBy('queued_at', 'desc')
    ->limit(10)
    ->get(['event_id', 'event_name', 'idempotency_key', 'status', 'attempts', 'last_http_status', 'queued_at', 'delivered_at']);

echo "=== USAGE_METRICS outbox records (latest 10) ===" . PHP_EOL;
foreach ($signals as $s) {
    echo "---" . PHP_EOL;
    echo "  event_id:        " . $s->event_id . PHP_EOL;
    echo "  idempotency_key: " . $s->idempotency_key . PHP_EOL;
    echo "  status:          " . $s->status . PHP_EOL;
    echo "  attempts:        " . $s->attempts . PHP_EOL;
    echo "  http_status:     " . ($s->last_http_status ?? 'none yet') . PHP_EOL;
    echo "  queued_at:       " . $s->queued_at . PHP_EOL;
    echo "  delivered_at:    " . ($s->delivered_at ?? 'not yet delivered') . PHP_EOL;
}

$total = OdooOutboundSignal::where('event_name', 'USAGE_METRICS')->count();
echo PHP_EOL . "Total USAGE_METRICS signals in outbox: " . $total . PHP_EOL;

// Show a decrypted sample payload
$first = OdooOutboundSignal::where('event_name', 'USAGE_METRICS')->first();
if ($first) {
    echo PHP_EOL . "=== Sample Decrypted Payload (user_id from idempotency key) ===" . PHP_EOL;
    echo json_encode($first->payload, JSON_PRETTY_PRINT) . PHP_EOL;
} else {
    echo PHP_EOL . "(No USAGE_METRICS signals found in the database)" . PHP_EOL;
}

// All signal statuses breakdown
echo PHP_EOL . "=== Status Breakdown (all signals) ===" . PHP_EOL;
$breakdown = OdooOutboundSignal::selectRaw('event_name, status, count(*) as cnt')
    ->groupBy('event_name', 'status')
    ->orderBy('event_name')
    ->orderBy('status')
    ->get();
foreach ($breakdown as $row) {
    printf("  %-30s %-20s %d\n", $row->event_name, $row->status, $row->cnt);
}
