<?php

namespace App\Events;

use App\Models\EventTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * PaymentSucceeded
 *
 * Fired after the payment webhook verifies a successful payment AND the
 * DB transaction (idempotency + PENDING→PAID + prereg_users.paid=1) commits.
 *
 * Listeners are dispatched via ->afterCommit() so they never fire on a
 * rolled-back transaction.
 */
class PaymentSucceeded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly EventTransaction $transaction
    ) {}
}
