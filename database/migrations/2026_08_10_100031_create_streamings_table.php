<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streamings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('room_id');
            $table->string('identifier', 200);
            $table->string('type', 200);
            $table->string('stream_key', 200);
            $table->integer('status')->default(0);
            $table->timestamp('ended_at')->nullable();
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
        Schema::dropIfExists('streamings');
    }
}
