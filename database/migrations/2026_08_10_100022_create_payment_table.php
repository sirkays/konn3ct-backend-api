<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type', 150)->default('Subscription');
            $table->integer('plan');
            $table->text('gateway');
            $table->string('currency', 90)->nullable();
            $table->string('duration')->nullable();
            $table->string('status', 90);
            $table->integer('amount');
            $table->timestamp('date')->useCurrent();
            $table->string('reference', 90);
            $table->string('gateway_reference', 90);
            $table->text('gateway_response');
            $table->timestamps();
            $table->index('user_id');
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
        Schema::dropIfExists('payment');
    }
}
