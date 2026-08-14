<?php

namespace App\Providers;

use App\Events\PaymentSucceeded;
use App\Jobs\Payment\GenerateReceiptPdfJob;
use App\Jobs\Payment\NotifyOdooPaymentJob;
use App\Jobs\Payment\SendEventTicketMailJob;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // Dispatch independent fulfillment jobs after a payment succeeds.
        // Each job is independently idempotent and dispatched ->afterCommit().
        // They run in order of priority: PDF first, then email, then Odoo.
        Event::listen(PaymentSucceeded::class, function (PaymentSucceeded $event) {
            $txId = $event->transaction->id;
            GenerateReceiptPdfJob::dispatch($txId);
            SendEventTicketMailJob::dispatch($txId);
            NotifyOdooPaymentJob::dispatch($txId);
        });
    }
}

