<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->string('room_id', 200);
            $table->string('sender', 200);
            $table->text('message');
            $table->integer('reply_to')->default(0);
            $table->string('type', 20)->default('text');
            $table->integer('status')->default(1)->comment('1 means active, 0 means deleted');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
            $table->index('room_id');
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
        Schema::dropIfExists('chat_messages');
    }
}
