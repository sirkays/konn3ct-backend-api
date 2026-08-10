<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKycsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('type', ['individual','corporate']);
            $table->string('address', 200)->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('bvn', 11);
            $table->string('bank_name', 200);
            $table->string('bank_code', 20);
            $table->string('bank_account_number', 10);
            $table->string('bank_account_name', 200);
            $table->string('id_type', 200);
            $table->string('id_document', 200);
            $table->string('company_name', 200)->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('company_phone', 15)->nullable();
            $table->string('company_address', 200)->nullable();
            $table->string('company_taxid', 50)->nullable();
            $table->integer('status')->default(0)->comment('0 - Pending; 1 -Approved; 2 - Rejected');
            $table->timestamps();
            $table->index('user_id');
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
        Schema::dropIfExists('kycs');
    }
}
