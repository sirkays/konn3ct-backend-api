<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add users.account_status as the authoritative moderation status field.
 *
 * This is separate from users.status (subscription/payment state) which is
 * managed by payment flows and must not be used for access control decisions.
 *
 * Allowed values: ACTIVE, SUSPENDED, BANNED
 * Legacy null values are treated as ACTIVE at the application layer.
 */
class AddAccountStatusToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_status', 20)
                  ->nullable()
                  ->default(null)
                  ->after('status')
                  ->comment('Moderation status: ACTIVE, SUSPENDED, BANNED. Null treated as ACTIVE.');

            $table->index('account_status', 'users_account_status_index');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_account_status_index');
            $table->dropColumn('account_status');
        });
    }
}
