<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('donation_id');
            $table->string('meeting_id', 200);
            $table->double('amount');
            $table->text('description')->nullable();
            $table->string('payee_name', 200);
            $table->string('payee_email', 200);
            $table->string('payee_id', 200);
            $table->integer('status')->default(0)->comment('0 for pending; 1 for paid');
            $table->string('reference', 100)->nullable();
            $table->string('provider', 50)->nullable();
            $table->text('provider_response')->nullable();
            $table->text('notification_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();
            $table->index('donation_id');
            $table->index('meeting_id');
            $table->index('payee_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donation_payments');
    }
}
