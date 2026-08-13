<?php

namespace App\Contracts\Admin;

/**
 * Meeting Enforcement Gateway interface.
 *
 * Implementations are responsible for revoking meeting access tokens and
 * disconnecting live participants from the external meeting service.
 *
 * The confirmed external meeting service is accessed via Konn3ctMeetingService
 * (KONN3CT_BASE_URL / KONN3CT_API_KEY). As of the current implementation,
 * no force-disconnect or token-revocation endpoint has been confirmed on that
 * service. The only confirmed join endpoint is:
 *   POST /api/external/v1/meetings/join
 *
 * Until a force-disconnect/revocation endpoint is confirmed and tested,
 * implementors MUST return BLOCKED_PENDING_MEETING_SERVICE_CONTRACT
 * for meeting_join_tokens and live_disconnect, and set complete = false.
 *
 * Required environment variables (future):
 *   KONN3CT_BASE_URL     — Base URL of the external Konn3ct meeting service
 *   KONN3CT_API_KEY      — API key for the external meeting service
 *
 * When a real endpoint is confirmed, add:
 *   KONN3CT_FORCE_DISCONNECT_PATH — Path for force-disconnect endpoint (e.g. /api/external/v1/meetings/force-disconnect)
 */
interface MeetingEnforcementGateway
{
    public const STATUS_ENFORCED  = 'ENFORCED';
    public const STATUS_BLOCKED   = 'BLOCKED_PENDING_MEETING_SERVICE_CONTRACT';
    public const STATUS_FAILED    = 'FAILED';

    /**
     * Attempt to revoke all active meeting join tokens for a user
     * and disconnect them from live meetings.
     *
     * @param  int    $userId
     * @param  string $correlationId  UUID for tracing
     * @return array{
     *   meeting_join_tokens: string,
     *   live_disconnect: string,
     *   complete: bool
     * }
     */
    public function revokeAndDisconnect(int $userId, string $correlationId): array;
}
