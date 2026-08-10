<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 90);
            $table->string('monthly_cost', 50);
            $table->string('yearly_cost', 50);
            $table->integer('recording');
            $table->integer('duration');
            $table->integer('participant');
            $table->integer('rooms');
            $table->integer('customize_link');
            $table->integer('breakout_rooms');
            $table->integer('access_code');
            $table->integer('dialin');
            $table->string('status', 90);
            $table->timestamps();
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
        Schema::dropIfExists('plans');
    }
}
