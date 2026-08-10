<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreregUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prereg_users', function (Blueprint $table) {
            $table->id();
            $table->integer('prereg_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone', 15);
            $table->integer('hasjoin')->default(0);
            $table->integer('paid')->default(0);
            $table->string('amount', 200)->default('0');
            $table->string('payment_reference', 200)->nullable();
            $table->string('payment_provider', 100)->nullable();
            $table->text('payment_provider_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index('prereg_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prereg_users');
    }
}
