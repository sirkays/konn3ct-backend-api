<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOdooOutboundSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odoo_outbound_signals', function (Blueprint $table) {
            $table->id();

            // Stable UUID for this outbound event. Never changes across retries.
            $table->uuid('event_id')->unique();

            // Signal name: USER_REGISTERED, USAGE_METRICS, PAYMENT_SUCCESS, etc.
            $table->string('event_name', 60);

            // Contract schema version (e.g. "1.0")
            $table->string('schema_version', 10)->default('1.0');

            // Unique business key preventing duplicate signals.
            // Example: "USER_REGISTERED:123", "PAYMENT_SUCCESS:paystack:TX-001"
            $table->string('idempotency_key', 300)->unique();

            // Which endpoint config key to use (matches config/odoo.php endpoints keys)
            $table->string('endpoint_key', 60);

            // Encrypted JSON payload. Contains PII — must remain encrypted at rest.
            $table->text('payload');

            // Delivery lifecycle status.
            // pending → queued → delivering → delivered
            //                              → failed
            //                 → blocked (permanent non-retryable failure)
            //                 → waiting_for_identity (paid event, no user)
            $table->string('status', 30)->default('pending')->index();

            // Number of delivery attempts made.
            $table->unsignedTinyInteger('attempts')->default(0);

            // Last HTTP status code received from Odoo (nullable until first attempt).
            $table->unsignedSmallInteger('last_http_status')->nullable();

            // Sanitized error description — no raw payloads or secrets.
            $table->string('last_error', 500)->nullable();

            // When the next retry should be attempted.
            $table->timestamp('next_attempt_at')->nullable();

            // Lifecycle timestamps.
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->timestamps();

            // Composite indexes for worker queries.
            $table->index(['status', 'next_attempt_at']);
            $table->index(['event_name', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odoo_outbound_signals');
    }
}
