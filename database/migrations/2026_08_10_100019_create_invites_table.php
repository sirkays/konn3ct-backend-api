<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type', 20);
            $table->enum('shedule_type', ['meeting','webinar'])->default('meeting');
            $table->enum('recurrence', ['once','daily','weekly','monthly'])->default('once');
            $table->text('hostname')->nullable();
            $table->text('roomlink')->nullable();
            $table->text('accesscode')->nullable();
            $table->text('title')->nullable();
            $table->text('date')->nullable();
            $table->text('time')->nullable();
            $table->string('totime', 20)->nullable();
            $table->text('roomname')->nullable();
            $table->text('timezone')->nullable();
            $table->text('additional')->nullable();
            $table->text('guest');
            $table->timestamps();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invites');
    }
}
