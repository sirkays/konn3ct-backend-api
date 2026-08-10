<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name', 900);
            $table->string('url', 900);
            $table->string('default_room', 9)->nullable()->default('no');
            $table->string('dial_number', 20)->nullable();
            $table->string('password_attendee', 90)->nullable();
            $table->string('password_moderator', 90);
            $table->text('welcome_message');
            $table->text('logout_url');
            $table->integer('max_participants');
            $table->integer('duration');
            $table->string('muj', 5)->nullable();
            $table->string('dpuc', 5)->nullable();
            $table->string('dprc', 5)->nullable();
            $table->string('ewma', 5)->nullable();
            $table->string('dum', 5)->nullable();
            $table->string('dsn', 5)->nullable();
            $table->string('dwr', 5)->nullable();
            $table->enum('mode', ['meeting','webinar','',''])->default('meeting');
            $table->integer('privacy')->default(1);
            $table->string('color', 20)->default('#00d492');
            $table->text('banner')->nullable();
            $table->text('prereg')->nullable();
            $table->string('bbb_returncode', 90)->nullable();
            $table->text('internalMeetingID')->nullable();
            $table->text('parentMeetingID')->nullable();
            $table->string('voiceBridge', 50)->nullable();
            $table->text('createDate')->nullable();
            $table->text('createTime')->nullable();
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
        Schema::dropIfExists('room');
    }
}
