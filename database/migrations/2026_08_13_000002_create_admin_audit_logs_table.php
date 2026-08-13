<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create admin_audit_logs table for append-only audit records.
 *
 * Do NOT store JWTs, raw payment payloads, passwords, or tokens here.
 * The metadata JSON column may hold non-sensitive contextual data only.
 */
class CreateAdminAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_code', 60)->comment('e.g. AUDIT_USER_SUSPENDED');
            $table->string('priority', 20)->default('NORMAL')->comment('NORMAL, HIGH, CRITICAL');
            $table->unsignedBigInteger('actor_admin_id')->nullable()->comment('Administrator who performed the action');
            $table->unsignedBigInteger('target_user_id')->nullable()->comment('User affected by the action');
            $table->text('reason')->nullable();
            $table->string('correlation_id', 36)->comment('UUID request correlation ID');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable()->comment('Non-sensitive contextual data only');
            $table->timestamp('created_at')->useCurrent();

            $table->index('event_code', 'audit_event_code_idx');
            $table->index('actor_admin_id', 'audit_actor_idx');
            $table->index('target_user_id', 'audit_target_idx');
            $table->index('created_at', 'audit_created_at_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_audit_logs');
    }
}
