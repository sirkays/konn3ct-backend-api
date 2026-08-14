<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFulfillmentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Per-transaction fulfillment state tracking.
     * Each step (PDF, email, Odoo) is tracked independently so partial
     * failures can be retried without re-processing completed steps.
     * Row-level locking (lockForUpdate) prevents concurrent duplicate work.
     */
    public function up()
    {
        Schema::create('fulfillment_log', function (Blueprint $table) {
            $table->id();

            // One-to-one with event_transactions.
            $table->unsignedBigInteger('event_transaction_id')->unique()->index();

            // Receipt PDF generation step.
            $table->boolean('receipt_generated')->default(false);
            $table->string('receipt_path', 500)->nullable();

            // Ticket PDF generation step.
            $table->boolean('ticket_generated')->default(false);
            $table->string('ticket_path', 500)->nullable();

            // Email delivery step.
            $table->boolean('email_sent')->default(false);

            // Odoo outbound signal dispatch step.
            // Note: "dispatched" means queued to the Odoo outbox, not delivered.
            $table->boolean('odoo_notified')->default(false);

            // Last error message (truncated, no PII).
            $table->text('last_error')->nullable();

            // How many times the fulfillment pipeline has been retried.
            $table->unsignedTinyInteger('retry_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('fulfillment_log');
    }
}
