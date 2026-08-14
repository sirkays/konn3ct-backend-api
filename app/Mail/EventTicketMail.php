<?php

namespace App\Mail;

use App\Models\EventTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * EventTicketMail
 *
 * Sends the event ticket confirmation email.
 * PDF attachments are added by the calling job before send().
 */
class EventTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly EventTransaction $transaction
    ) {}

    /**
     * Build the message.
     */
    public function build(): static
    {
        $event = $this->transaction->event;

        return $this
            ->subject('Your Event Ticket — ' . (optional($event)->title ?? 'Konn3ct Event'))
            ->view('emails.event-ticket')
            ->with([
                'transaction'  => $this->transaction,
                'event'        => $event,
                'ticket_number' => $this->transaction->ticket_number,
            ]);
    }
}
