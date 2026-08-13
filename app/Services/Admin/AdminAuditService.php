<?php

namespace App\Services\Admin;

use App\Models\AdminAuditLog;
use Illuminate\Support\Facades\Log;

/**
 * Append-only admin audit service.
 *
 * All admin moderation actions must be recorded through this service.
 * Never log tokens, raw payment payloads, passwords, or moderation reasons
 * to the application logger — only structured, non-secret context.
 */
class AdminAuditService
{
    // Event codes
    public const EVENT_USER_SUSPENDED = 'AUDIT_USER_SUSPENDED';
    public const EVENT_USER_BANNED    = 'AUDIT_USER_BANNED';

    // Priority levels
    public const PRIORITY_NORMAL   = 'NORMAL';
    public const PRIORITY_HIGH     = 'HIGH';
    public const PRIORITY_CRITICAL = 'CRITICAL';

    /**
     * Persist an audit record.
     *
     * @param  string      $eventCode     One of the EVENT_* constants.
     * @param  string      $priority      One of the PRIORITY_* constants.
     * @param  int|null    $actorAdminId  ID of the acting administrator.
     * @param  int|null    $targetUserId  ID of the affected user.
     * @param  string|null $reason        Human-readable reason (stored, not logged).
     * @param  string      $correlationId UUID request correlation ID.
     * @param  string|null $ip            Client IP address.
     * @param  string|null $ua            Client User-Agent (truncated to 500 chars).
     * @param  array|null  $metadata      Non-sensitive contextual data only.
     * @return AdminAuditLog
     */
    public function record(
        string $eventCode,
        string $priority,
        ?int $actorAdminId,
        ?int $targetUserId,
        ?string $reason,
        string $correlationId,
        ?string $ip,
        ?string $ua,
        ?array $metadata = null
    ): AdminAuditLog {
        $log = new AdminAuditLog();
        $log->event_code     = $eventCode;
        $log->priority       = $priority;
        $log->actor_admin_id = $actorAdminId;
        $log->target_user_id = $targetUserId;
        $log->reason         = $reason;
        $log->correlation_id = $correlationId;
        $log->ip_address     = $ip;
        $log->user_agent     = $ua ? mb_substr($ua, 0, 500) : null;
        $log->metadata       = $metadata;
        $log->created_at     = now();
        $log->save();

        // Emit structured log — do NOT include reason, tokens, or secrets
        Log::info('Admin audit event recorded', [
            'event'          => $eventCode,
            'priority'       => $priority,
            'actor_admin_id' => $actorAdminId,
            'target_user_id' => $targetUserId,
            'correlation_id' => $correlationId,
            'ip'             => $ip,
        ]);

        return $log;
    }
}
