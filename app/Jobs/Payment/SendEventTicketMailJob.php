<?php

namespace App\Jobs\Payment;

use App\Mail\EventTicketMail;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * SendEventTicketMailJob
 *
 * Sends the event ticket email (with PDF attachments) to the ticket purchaser.
 * Idempotent: skips if email_sent=1 in fulfillment_log.
 * Uses row-level locking to prevent concurrent duplicate sends.
 * Dispatched after the payment DB transaction commits (via PaymentSucceeded event).
 */
class SendEventTicketMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 30;

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
                return;
            }

            if ($log->email_sent) {
                return; // Already sent.
            }

            $transaction = EventTransaction::find($this->transactionId);
            if (!$transaction || $transaction->status !== EventTransaction::STATUS_PAID) {
                return;
            }

            $user = $transaction->user;

            // Ensure PDFs exist (generate them if not already done).
            if (!$log->ticket_generated) {
                try {
                    $ticketPath = $pdfService->generateTicket($transaction);
                    $log->ticket_generated = true;
                    $log->ticket_path      = $ticketPath;
                    $log->save();
                } catch (\Exception $e) {
                    $log->increment('retry_count');
                    $log->update(['last_error' => substr($e->getMessage(), 0, 500)]);
                    throw $e;
                }
            }

            try {
                // Send email with ticket PDF attachment.
                $mailable = new EventTicketMail($transaction);

                if ($log->ticket_path && Storage::exists($log->ticket_path)) {
                    $mailable->attachStorage($log->ticket_path, $transaction->ticket_number . '-ticket.pdf');
                }

                if ($log->receipt_path && Storage::exists($log->receipt_path)) {
                    $mailable->attachStorage($log->receipt_path, $transaction->ticket_number . '-receipt.pdf');
                }

                Mail::to($user->email)->send($mailable);

                $log->update(['email_sent' => true]);
            } catch (\Exception $e) {
                $log->increment('retry_count');
                $log->update(['last_error' => substr($e->getMessage(), 0, 500)]);
                throw $e;
            }
        });
    }
}
