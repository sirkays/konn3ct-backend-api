<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support', function (Blueprint $table) {
            $table->id();
            $table->string('name', 900);
            $table->string('email', 900);
            $table->string('subject', 900);
            $table->text('message');
            $table->text('attachment');
            $table->string('status', 90);
            $table->string('admin', 900);
            $table->timestamp('admin_date')->useCurrent();
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
        Schema::dropIfExists('support');
    }
}
