<?php

namespace App\Jobs\Payment;

use App\Models\EventTransaction;
use App\Models\FulfillmentLog;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * NotifyOdooPaymentJob
 *
 * Dispatches the PAID_EVENT_PURCHASE Odoo outbound signal for a paid transaction.
 * Reuses the existing OdooSignalDispatcher + OdooPayloadFactory infrastructure.
 * The dispatcher handles its own idempotency via the outbox table.
 *
 * Idempotent: skips if odoo_notified=1 in fulfillment_log.
 * Uses row-level locking to prevent concurrent duplicate dispatches.
 * Dispatched after the payment DB transaction commits (via PaymentSucceeded event).
 *
 * NOTE: The Odoo delivery URL is not yet confirmed for production.
 * For staging smoke testing, set ODOO19_BASE_URL=https://webhook.site and
 * ODOO19_PAID_EVENT_PURCHASE_PATH=/your-token in .env.
 * See docs/integrations/payment-security.md for the smoke test procedure.
 */
class NotifyOdooPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 30;

    public function __construct(
        public readonly int $transactionId
    ) {
        $this->afterCommit();
    }

    public function handle(OdooPayloadFactory $factory, OdooSignalDispatcher $dispatcher): void
    {
        DB::transaction(function () use ($factory, $dispatcher) {
            $log = FulfillmentLog::where('event_transaction_id', $this->transactionId)
                ->lockForUpdate()
                ->first();

            if (!$log || $log->odoo_notified) {
                return; // Already dispatched.
            }

            $transaction = EventTransaction::find($this->transactionId);
            if (!$transaction || $transaction->status !== EventTransaction::STATUS_PAID) {
                return;
            }

            $user = $transaction->user;

            try {
                // Build the Odoo PAID_EVENT_PURCHASE payload using existing factory.
                $payload = $factory->paidEventPurchase(
                    userId:       (int) $user->id,
                    eventId:      (int) $transaction->event_id,
                    ticketPrice:  $transaction->amount_minor / 100  // major units
                );

                // Deterministic idempotency key — prevents duplicate Odoo signals.
                $idempotencyKey = 'PAID_EVENT_PURCHASE:' . $transaction->provider . ':' . $transaction->local_reference;

                $dispatcher->dispatch(
                    'PAID_EVENT_PURCHASE',
                    'paid_event_purchase',
                    $idempotencyKey,
                    $payload
                );

                $log->update(['odoo_notified' => true]);
            } catch (\Exception $e) {
                $log->increment('retry_count');
                $log->update(['last_error' => substr($e->getMessage(), 0, 500)]);
                Log::error('NotifyOdooPaymentJob: dispatch failed', [
                    'transaction_id' => $this->transactionId,
                    'error'          => substr($e->getMessage(), 0, 300),
                ]);
                throw $e;
            }
        });
    }
}
