<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrolledChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrolled_chats', function (Blueprint $table) {
            $table->id();
            $table->integer('room_id');
            $table->integer('user_id');
            $table->integer('status')->default(1)->comment('0 means kickedout, 1 means particiapant');
            $table->integer('owner')->default(0)->comment('0 means participant, 1 means owner');
            $table->integer('banned')->default(0)->comment('0 means ');
            $table->integer('public_chat')->default(1)->comment('0 means only admin can send message, 1 means all can send message');
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
        Schema::dropIfExists('enrolled_chats');
    }
}
