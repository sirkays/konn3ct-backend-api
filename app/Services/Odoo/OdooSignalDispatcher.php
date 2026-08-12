<?php

namespace App\Services\Odoo;

use App\Jobs\Odoo\DeliverOdooSignalJob;
use App\Models\OdooOutboundSignal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * OdooSignalDispatcher
 *
 * Central entry point for emitting outbound signals to Odoo 19.
 *
 * Responsibilities:
 *  - Guard against disabled integration
 *  - Create a durable OdooOutboundSignal record with encrypted payload
 *  - Enforce idempotency via the DB unique constraint on idempotency_key
 *  - Dispatch DeliverOdooSignalJob AFTER the calling transaction commits
 *  - Never call Odoo synchronously from registration or payment request handlers
 */
class OdooSignalDispatcher
{
    /**
     * Dispatch a named signal to Odoo.
     *
     * @param string $eventName       e.g. 'USER_REGISTERED'
     * @param string $endpointKey     Key in config('odoo.endpoints'), e.g. 'user_registered'
     * @param string $idempotencyKey  Stable business key, e.g. 'USER_REGISTERED:123'
     * @param array  $payload         Final business payload (will be encrypted at rest)
     * @param string $status          Initial status for the record (usually 'pending')
     *
     * @return OdooOutboundSignal|null  null if integration is disabled or duplicate
     */
    public function dispatch(
        string $eventName,
        string $endpointKey,
        string $idempotencyKey,
        array $payload,
        string $status = OdooOutboundSignal::STATUS_PENDING
    ): ?OdooOutboundSignal {
        if (!config('odoo.enabled')) {
            Log::debug('OdooSignalDispatcher: integration disabled — skipping signal', [
                'event_name' => $eventName,
            ]);
            return null;
        }

        // Attempt to create the record. A unique constraint on idempotency_key
        // will reject any duplicate without throwing an application exception.
        try {
            $signal = DB::transaction(function () use (
                $eventName,
                $endpointKey,
                $idempotencyKey,
                $payload,
                $status
            ) {
                return OdooOutboundSignal::create([
                    'event_id'        => (string) Str::uuid(),
                    'event_name'      => $eventName,
                    'schema_version'  => config('odoo.schema_version', '1.0'),
                    'idempotency_key' => $idempotencyKey,
                    'endpoint_key'    => $endpointKey,
                    'payload'         => $payload,  // triggers encrypted setter
                    'status'          => $status,
                    'attempts'        => 0,
                    'queued_at'       => now(),
                ]);
            });
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Duplicate idempotency key — this signal was already created.
            Log::info('OdooSignalDispatcher: duplicate idempotency key — signal already exists', [
                'event_name'      => $eventName,
                'idempotency_key' => $idempotencyKey,
            ]);
            return null;
        } catch (\Exception $e) {
            // Handle older Laravel/DB drivers that don't use the specific exception subclass.
            if (str_contains($e->getMessage(), 'Duplicate entry') ||
                str_contains($e->getMessage(), 'UNIQUE constraint failed') ||
                str_contains($e->getMessage(), 'unique constraint')) {
                Log::info('OdooSignalDispatcher: duplicate idempotency key — signal already exists', [
                    'event_name'      => $eventName,
                    'idempotency_key' => $idempotencyKey,
                ]);
                return null;
            }
            throw $e;
        }

        // Only dispatch the job when the status is not a blocking terminal state.
        if (in_array($status, OdooOutboundSignal::RETRYABLE_STATUSES, true)) {
            DeliverOdooSignalJob::dispatch($signal->event_id)
                ->onConnection(config('odoo.queue_connection', 'database'))
                ->onQueue(config('odoo.queue_name', 'odoo'))
                ->afterCommit();  // Ensures the job is queued only after the DB transaction commits.
        }

        Log::info('OdooSignalDispatcher: signal created', [
            'event_id'   => $signal->event_id,
            'event_name' => $eventName,
            'status'     => $status,
        ]);

        return $signal;
    }

    /**
     * Create a signal record in waiting_for_identity status.
     * Used for PAID_EVENT_PURCHASE when no Konn3ct user_id can be resolved.
     *
     * @param string $eventName
     * @param string $endpointKey
     * @param string $idempotencyKey
     * @param array  $payload
     * @return OdooOutboundSignal|null
     */
    public function dispatchWaitingForIdentity(
        string $eventName,
        string $endpointKey,
        string $idempotencyKey,
        array $payload
    ): ?OdooOutboundSignal {
        return $this->dispatch(
            $eventName,
            $endpointKey,
            $idempotencyKey,
            $payload,
            OdooOutboundSignal::STATUS_WAITING_FOR_IDENTITY
        );
    }
}
