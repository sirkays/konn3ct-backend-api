<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->text('email')->nullable();
            $table->string('status', 90)->default('pending');
            $table->text('ip_address')->nullable();
            $table->text('device')->nullable();
            $table->text('city')->nullable();
            $table->text('region')->nullable();
            $table->text('country')->nullable();
            $table->text('countryCode')->nullable();
            $table->text('timezone')->nullable();
            $table->text('provider')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('visit_logs');
    }
}
