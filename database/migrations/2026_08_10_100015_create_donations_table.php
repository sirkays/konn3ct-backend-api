<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->integer('user_id');
            $table->string('name', 200);
            $table->integer('type')->default(1)->comment('1 for variable; 0 for fixed');
            $table->string('currency', 10)->default('NGN');
            $table->string('amount', 200);
            $table->integer('status')->default(1);
            $table->integer('enableFlashNotification')->default(1);
            $table->timestamps();
            $table->index('room_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['user_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
