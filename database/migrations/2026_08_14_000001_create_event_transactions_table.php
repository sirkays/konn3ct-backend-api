<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Stores one record per Paystack/Stripe checkout attempt.
     * Vulte event payments continue to use prereg_users directly.
     * This table is the authoritative source for new-gateway event ticket
     * purchases and feeds the fulfillment pipeline.
     */
    public function up()
    {
        Schema::create('event_transactions', function (Blueprint $table) {
            $table->id();

            // Stable UUID for external reference. Never changes.
            $table->uuid('uuid')->unique();

            // Human-readable ticket number. Format: TICK-{YEAR}-{RANDOM_12}.
            // Required by GET /api/v1/tickets/{number}/verify
            $table->string('ticket_number', 30)->unique();

            // FK to users (the authenticated purchaser).
            $table->unsignedBigInteger('user_id')->index();

            // FK to prereg (the event being purchased).
            $table->unsignedBigInteger('event_id')->index();

            // Price locked at checkout time — prevents price tampering.
            // Stored in minor units (e.g. 500000 = NGN 5,000.00).
            $table->unsignedBigInteger('amount_minor');
            $table->char('currency', 3);

            // Payment provider: 'paystack' or 'stripe'.
            $table->string('provider', 30);

            // Our internal reference sent to the payment provider.
            $table->string('local_reference', 100)->unique();

            // Provider's reference (populated after initialization).
            $table->string('provider_reference', 200)->nullable()->index();

            // Provider session/access code (Stripe: checkout session id, Paystack: access_code).
            $table->string('provider_session_id', 300)->nullable();

            // Lifecycle status.
            // pending             → checkout initialized, awaiting payment
            // paid                → payment confirmed via webhook
            // failed              → payment failed via webhook
            // initialization_failed → provider rejected the checkout request
            $table->string('status', 30)->default('pending')->index();

            // Encrypted JSON snapshot of prereg.amount + prereg.currency at checkout.
            // Proves the price was not tampered with after the fact.
            $table->text('pricing_snapshot');

            // Optional metadata (e.g., geo resolution, client hints).
            $table->text('metadata')->nullable();

            // When the payment was confirmed.
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // Composite indexes for webhook lookup.
            $table->index(['provider', 'provider_reference']);
            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('event_transactions');
    }
}
