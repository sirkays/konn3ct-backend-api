<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessedWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Idempotency table for payment webhooks.
     * A webhook event is recorded here INSIDE the same DB transaction as the
     * entitlement provisioning. If provisioning rolls back, this record
     * is also rolled back — so the event can be retried safely.
     *
     * Composite unique on (provider, provider_event_id) prevents double-processing.
     */
    public function up()
    {
        Schema::create('processed_webhooks', function (Blueprint $table) {
            $table->id();

            // e.g. 'paystack', 'stripe'
            $table->string('provider', 30)->index();

            // Provider's event type, e.g. 'charge.success', 'checkout.session.completed'
            $table->string('event_type', 100);

            // Provider's unique event identifier (Paystack: reference, Stripe: event ID).
            $table->string('provider_event_id', 300);

            // Our computed idempotency key. Unique across all webhooks.
            // Format: "{provider}:{event_type}:{provider_event_id}"
            $table->string('idempotency_key', 400)->unique();

            // The event_transaction this webhook resolved (nullable — not all webhooks match a tx).
            $table->unsignedBigInteger('event_transaction_id')->nullable()->index();

            // When this webhook was fully processed.
            $table->timestamp('processed_at')->useCurrent();

            // Unique composite prevents the same provider event from being processed twice.
            $table->unique(['provider', 'provider_event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('processed_webhooks');
    }
}
