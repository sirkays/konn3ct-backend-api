<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Add performance indexes for Admin Portal queries.
 *
 * FULLTEXT index on users (firstname, lastname, email) is MySQL/MariaDB only.
 * The migration checks the driver and skips FULLTEXT on SQLite (test environment).
 * All B-tree indexes are ascending; MySQL can traverse them backwards for DESC ordering.
 *
 * payment.gateway is TEXT — we use a prefix index (191 chars) for MySQL/MariaDB only.
 * No destructive type changes are made.
 */
class AddAdminPortalIndexes extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();
        $isMySQL = in_array($driver, ['mysql', 'mariadb'], true);

        // ── users table indexes ──────────────────────────────────────────────
        Schema::table('users', function (Blueprint $table) use ($isMySQL) {
            // FULLTEXT index for name/email token search (MySQL/MariaDB only)
            if ($isMySQL) {
                // Raw DDL for FULLTEXT — Blueprint::fullText() not available in Laravel 8
                // Done via raw statement below
            }

            // (created_at, id) for deterministic default ordering
            if (!$this->indexExists('users', 'users_created_at_id_idx')) {
                $table->index(['created_at', 'id'], 'users_created_at_id_idx');
            }

            // (last_used, id) for deterministic last-used sort
            if (!$this->indexExists('users', 'users_last_used_id_idx')) {
                $table->index(['last_used', 'id'], 'users_last_used_id_idx');
            }

            // (type, account_status, created_at, id) for role+status+order filtering
            if (!$this->indexExists('users', 'users_type_acct_status_idx')) {
                $table->index(['type', 'account_status', 'created_at', 'id'], 'users_type_acct_status_idx');
            }
        });

        // FULLTEXT raw DDL (MySQL/MariaDB only)
        if ($isMySQL && !$this->fulltextIndexExists('users', 'users_fulltext_name_email')) {
            DB::statement('ALTER TABLE `users` ADD FULLTEXT INDEX `users_fulltext_name_email` (`firstname`, `lastname`, `email`)');
        }

        // ── payment table indexes ────────────────────────────────────────────
        Schema::table('payment', function (Blueprint $table) use ($isMySQL) {
            // (date, id) for deterministic default ordering
            if (!$this->indexExists('payment', 'payment_date_id_idx')) {
                $table->index(['date', 'id'], 'payment_date_id_idx');
            }

            // (status, date) for combined status+date filtering
            if (!$this->indexExists('payment', 'payment_status_date_idx')) {
                $table->index(['status', 'date'], 'payment_status_date_idx');
            }

            // type index for payment type filter
            if (!$this->indexExists('payment', 'payment_type_idx')) {
                $table->index('type', 'payment_type_idx');
            }
        });

        // payment.gateway prefix index (MySQL/MariaDB only — TEXT column)
        if ($isMySQL && !$this->indexExists('payment', 'payment_gateway_prefix_idx')) {
            DB::statement('ALTER TABLE `payment` ADD INDEX `payment_gateway_prefix_idx` (`gateway`(191))');
        }
    }

    public function down()
    {
        $driver = DB::getDriverName();
        $isMySQL = in_array($driver, ['mysql', 'mariadb'], true);

        if ($isMySQL) {
            if ($this->fulltextIndexExists('users', 'users_fulltext_name_email')) {
                DB::statement('ALTER TABLE `users` DROP INDEX `users_fulltext_name_email`');
            }
            if ($this->indexExists('payment', 'payment_gateway_prefix_idx')) {
                DB::statement('ALTER TABLE `payment` DROP INDEX `payment_gateway_prefix_idx`');
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndexIfExists('users_created_at_id_idx');
            $table->dropIndexIfExists('users_last_used_id_idx');
            $table->dropIndexIfExists('users_type_acct_status_idx');
        });

        Schema::table('payment', function (Blueprint $table) {
            $table->dropIndexIfExists('payment_date_id_idx');
            $table->dropIndexIfExists('payment_status_date_idx');
            $table->dropIndexIfExists('payment_type_idx');
        });
    }

    /**
     * Check if a regular B-tree index exists on a table.
     */
    protected function indexExists(string $table, string $indexName): bool
    {
        try {
            $driver = DB::getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'], true)) {
                $result = DB::select(
                    "SELECT COUNT(*) as cnt FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
                    [$table, $indexName]
                );
                return ($result[0]->cnt ?? 0) > 0;
            }
            // SQLite / other: assume index does not exist (safe to attempt add)
            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Check if a FULLTEXT index exists on a MySQL/MariaDB table.
     */
    protected function fulltextIndexExists(string $table, string $indexName): bool
    {
        try {
            $result = DB::select(
                "SELECT COUNT(*) as cnt FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? AND index_type = 'FULLTEXT'",
                [$table, $indexName]
            );
            return ($result[0]->cnt ?? 0) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }
}
