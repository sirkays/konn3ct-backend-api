<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('country', 3)->default('NG');
            $table->string('referral_code', 10)->nullable();
            $table->text('referral')->nullable();
            $table->integer('plan');
            $table->string('subscription', 25)->default('new');
            $table->tinyInteger('freetrial')->default(0);
            $table->string('type', 50)->default('user');
            $table->string('status', 15)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // two_factor_secret and two_factor_recovery_codes are handled in a separate default migration
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('whatsapp_invite', 100)->default('0');
            $table->integer('room_bundles')->default(0);
            $table->string('streaming_service', 100)->default('0');
            $table->integer('reseller_id')->default(0);
            $table->timestamp('last_used')->nullable();
            $table->timestamps();

            // Indexes for optimization
            $table->index('plan');
            $table->index('status');
            $table->index('type');
            $table->index('reseller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
