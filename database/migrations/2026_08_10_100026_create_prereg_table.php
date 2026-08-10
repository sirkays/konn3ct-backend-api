<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreregTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prereg', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('room_id');
            $table->text('reference');
            $table->text('title');
            $table->text('host_name');
            $table->text('date');
            $table->text('time');
            $table->text('timezone');
            $table->text('about');
            $table->text('logo')->nullable();
            $table->text('reminder')->nullable();
            $table->integer('status')->default(1);
            $table->integer('public')->default(1);
            $table->integer('free')->default(1)->comment('1 - Free; 0 - Paid');
            $table->string('currency', 50)->default('NGN');
            $table->string('amount', 200)->default('0');
            $table->string('tags', 200)->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('room_id');
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
        Schema::dropIfExists('prereg');
    }
}
