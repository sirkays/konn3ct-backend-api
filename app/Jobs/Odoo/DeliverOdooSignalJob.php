<?php

namespace App\Jobs\Odoo;

use App\Models\OdooOutboundSignal;
use App\Services\Odoo\OdooSignalClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * DeliverOdooSignalJob
 *
 * Delivers a single outbound Odoo signal from the odoo_outbound_signals outbox.
 *
 * Design principles:
 *  - Carries ONLY the event_id UUID, never the raw payload or credentials.
 *  - Claims the record atomically before delivery to prevent concurrent processing.
 *  - Preserves the stable event_id and idempotency_key across all retry attempts.
 *  - Updates delivery status and audit fields atomically.
 *  - Does not log payloads, tokens, secrets, emails, or IPs.
 *
 * Queue configuration:
 *  - Connection and queue name from config/odoo.php.
 *  - $tries and backoff from config/odoo.php.
 *
 * Production worker command:
 *  php artisan queue:work database --queue=odoo --tries=5 --timeout=60
 */
class DeliverOdooSignalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The stable event UUID. This never changes across retries.
     * The raw payload is NOT carried in the job.
     *
     * @var string
     */
    public string $eventId;

    /**
     * Maximum number of attempts. Pulled from config at dispatch time.
     *
     * @var int
     */
    public int $tries;

    /**
     * Create a new job instance.
     *
     * @param string $eventId  UUID from odoo_outbound_signals.event_id
     */
    public function __construct(string $eventId)
    {
        $this->eventId = $eventId;
        $this->tries   = config('odoo.max_attempts', 5);

        $this->onConnection(config('odoo.queue_connection', 'database'));
        $this->onQueue(config('odoo.queue_name', 'odoo'));
    }

    /**
     * Backoff delays between attempts (in seconds).
     */
    public function backoff(): array
    {
        return config('odoo.backoff', [60, 300, 900, 3600, 21600]);
    }

    /**
     * Execute the delivery job.
     */
    public function handle(OdooSignalClient $client): void
    {
        // Load the signal record.
        $signal = OdooOutboundSignal::where('event_id', $this->eventId)->first();

        if (!$signal) {
            Log::warning('DeliverOdooSignalJob: signal record not found — discarding', [
                'event_id' => $this->eventId,
            ]);
            return;
        }

        // If the signal has already been delivered or is in a terminal blocked state,
        // do not attempt again.
        if (in_array($signal->status, [
            OdooOutboundSignal::STATUS_DELIVERED,
            OdooOutboundSignal::STATUS_BLOCKED,
            OdooOutboundSignal::STATUS_WAITING_FOR_IDENTITY,
        ], true)) {
            Log::debug('DeliverOdooSignalJob: signal already in terminal state — skipping', [
                'event_id' => $this->eventId,
                'status'   => $signal->status,
            ]);
            return;
        }

        // Atomically claim the record for this worker.
        // claim() uses a WHERE + UPDATE so only one worker can succeed.
        if (!$signal->claim()) {
            Log::info('DeliverOdooSignalJob: could not claim signal — another worker may have it', [
                'event_id' => $this->eventId,
            ]);
            return;
        }

        // Increment attempt counter.
        $attemptNumber = $signal->attempts + 1;
        $signal->attempts = $attemptNumber;
        $signal->save();

        // Decrypt the payload.
        $payload = $signal->getDecryptedPayload();
        if ($payload === null) {
            Log::error('DeliverOdooSignalJob: payload decryption failed — marking blocked', [
                'event_id'   => $this->eventId,
                'event_name' => $signal->event_name,
                'attempt'    => $attemptNumber,
            ]);
            $this->markBlocked($signal, 'Payload decryption failed');
            return;
        }

        // Deliver the signal.
        $result = $client->send(
            $signal->event_id,
            $signal->event_name,
            $signal->endpoint_key,
            $signal->idempotency_key,
            $payload
        );

        // Log structured delivery result — no sensitive data.
        Log::info('DeliverOdooSignalJob: delivery attempt', [
            'event_id'     => $this->eventId,
            'event_name'   => $signal->event_name,
            'attempt'      => $attemptNumber,
            'endpoint_key' => $signal->endpoint_key,
            'http_status'  => $result['http_status'],
            'success'      => $result['success'],
        ]);

        if ($result['success']) {
            $this->markDelivered($signal);
            return;
        }

        // Determine whether to retry or mark as blocked.
        if ($result['retryable']) {
            $this->markQueuedForRetry($signal, $result['http_status'], $result['error']);
            // Re-throw to trigger queue retry machinery.
            throw new \RuntimeException(
                'OdooSignalClient retryable failure: ' . ($result['error'] ?? 'unknown error')
            );
        }

        // Non-retryable — mark blocked permanently.
        $this->markBlocked($signal, $result['error'], $result['http_status']);
    }

    /**
     * Handle final job failure after all retries are exhausted.
     *
     * @param \Throwable $exception
     */
    public function failed(\Throwable $exception): void
    {
        $signal = OdooOutboundSignal::where('event_id', $this->eventId)->first();

        if ($signal) {
            $signal->status    = OdooOutboundSignal::STATUS_FAILED;
            $signal->failed_at = now();
            $signal->last_error = substr($exception->getMessage(), 0, 500);
            $signal->save();
        }

        // Log only sanitized metadata — no payload, no token, no email.
        Log::error('DeliverOdooSignalJob: all attempts exhausted — signal failed', [
            'event_id'   => $this->eventId,
            'event_name' => $signal->event_name ?? 'unknown',
            'attempts'   => $signal->attempts ?? '?',
        ]);
    }

    // -------------------------------------------------------------------------
    // Status transition helpers
    // -------------------------------------------------------------------------

    private function markDelivered(OdooOutboundSignal $signal): void
    {
        $signal->status       = OdooOutboundSignal::STATUS_DELIVERED;
        $signal->delivered_at = now();
        $signal->last_error   = null;
        $signal->save();
    }

    private function markBlocked(OdooOutboundSignal $signal, ?string $error, ?int $httpStatus = null): void
    {
        $signal->status          = OdooOutboundSignal::STATUS_BLOCKED;
        $signal->failed_at       = now();
        $signal->last_error      = $error ? substr($error, 0, 500) : null;
        $signal->last_http_status = $httpStatus;
        $signal->save();
    }

    private function markQueuedForRetry(OdooOutboundSignal $signal, ?int $httpStatus, ?string $error): void
    {
        $signal->status           = OdooOutboundSignal::STATUS_QUEUED;
        $signal->last_http_status = $httpStatus;
        $signal->last_error       = $error ? substr($error, 0, 500) : null;
        $signal->save();
    }
}
