<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_pricings', function (Blueprint $table) {
            $table->id();
            $table->integer('plan_id');
            $table->string('currency', 3);
            $table->string('type', 100);
            $table->string('price', 100);
            $table->string('plan_code', 50);
            $table->integer('status')->default(1);
            $table->string('payment_gateway', 200)->default('paystack');
            $table->timestamps();
            $table->index('plan_id');
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
        Schema::dropIfExists('plan_pricings');
    }
}
