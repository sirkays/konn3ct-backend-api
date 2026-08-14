<?php

namespace App\Jobs\Payment;

use App\Models\EventTransaction;
use App\Models\FulfillmentLog;
use App\Services\PdfGenerationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * GenerateReceiptPdfJob
 *
 * Generates the PDF receipt for a paid event transaction.
 * Idempotent: skips if receipt_generated=1 in fulfillment_log.
 * Uses row-level locking to prevent concurrent duplicate generation.
 * Dispatched after the payment DB transaction commits (via PaymentSucceeded event).
 */
class GenerateReceiptPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 30; // seconds

    public function __construct(
        public readonly int $transactionId
    ) {
        $this->afterCommit();
    }

    public function handle(PdfGenerationService $pdfService): void
    {
        DB::transaction(function () use ($pdfService) {
            $log = FulfillmentLog::where('event_transaction_id', $this->transactionId)
                ->lockForUpdate()
                ->first();

            if (!$log) {
                Log::warning('GenerateReceiptPdfJob: no fulfillment log found', [
                    'transaction_id' => $this->transactionId,
                ]);
                return;
            }

            if ($log->receipt_generated) {
                return; // Idempotent — already done.
            }

            $transaction = EventTransaction::find($this->transactionId);
            if (!$transaction || $transaction->status !== EventTransaction::STATUS_PAID) {
                return;
            }

            try {
                $path = $pdfService->generateReceipt($transaction);
                $log->update([
                    'receipt_generated' => true,
                    'receipt_path'      => $path,
                ]);
            } catch (\Exception $e) {
                $log->increment('retry_count');
                $log->update(['last_error' => substr($e->getMessage(), 0, 500)]);
                throw $e; // Re-throw to trigger queue retry.
            }
        });
    }
}
