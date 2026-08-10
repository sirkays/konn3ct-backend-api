<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_requests', function (Blueprint $table) {
            $table->id();
            $table->string('mobile', 150);
            $table->string('code', 6)->nullable();
            $table->enum('type', ['register','recover']);
            $table->integer('status');
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
        Schema::dropIfExists('code_requests');
    }
}
