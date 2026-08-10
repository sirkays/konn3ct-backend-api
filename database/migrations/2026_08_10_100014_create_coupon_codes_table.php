<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->integer('discount')->default(0);
            $table->text('code');
            $table->enum('type', ['0','1','2',''])->comment('0 for all, 1 for monthly alone, 2 for yearly alone');
            $table->integer('status')->default(1);
            $table->text('used_by')->nullable();
            $table->integer('reoccuring')->default(0);
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
        Schema::dropIfExists('coupon_codes');
    }
}
