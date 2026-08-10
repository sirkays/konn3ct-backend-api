<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->integer('meeting_id');
            $table->text('identifier')->nullable();
            $table->string('name', 90);
            $table->string('email', 90);
            $table->text('password_attendee');
            $table->string('keyword', 300)->nullable();
            $table->string('status', 90);
            $table->timestamps();
            $table->index('meeting_id');
            $table->index('email');
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
        Schema::dropIfExists('meetings');
    }
}
