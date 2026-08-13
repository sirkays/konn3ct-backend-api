<?php

namespace App\Services\Admin;

use App\Contracts\Admin\MeetingEnforcementGateway;
use Illuminate\Support\Facades\Log;

/**
 * Unsupported Meeting Enforcement Gateway.
 *
 * This implementation is used when no confirmed force-disconnect or
 * token-revocation endpoint exists on the external Konn3ct meeting service.
 *
 * The confirmed external service (Konn3ctMeetingService / KONN3CT_BASE_URL) only
 * exposes join endpoints. There is no confirmed revocation or force-disconnect
 * endpoint in this repository.
 *
 * Result: BLOCKED_PENDING_MEETING_SERVICE_CONTRACT
 *
 * This implementation MUST NOT:
 * - Pretend to disconnect users
 * - Publish invented Redis messages
 * - Log a proposed payload as though it was sent
 *
 * This implementation MUST:
 * - Return a truthful enforcement object
 * - Log intent without fabricating delivery confirmation
 * - Set complete = false
 *
 * To replace this with a real implementation:
 * 1. Confirm the force-disconnect/revocation endpoint with the meeting service team
 * 2. Implement a new class that satisfies this interface
 * 3. Bind it in AppServiceProvider instead of this class
 * 4. Add integration tests before deploying
 */
class UnsupportedMeetingEnforcementGateway implements MeetingEnforcementGateway
{
    /**
     * Return BLOCKED status for both token revocation and live disconnect.
     *
     * No external call is made. No fabricated message is logged.
     *
     * @param  int    $userId
     * @param  string $correlationId
     * @return array
     */
    public function revokeAndDisconnect(int $userId, string $correlationId): array
    {
        // Log intent only — do NOT claim the action was performed
        Log::notice('Meeting enforcement skipped: no confirmed force-disconnect endpoint on external meeting service', [
            'event'          => 'MEETING_ENFORCEMENT_BLOCKED',
            'user_id'        => $userId,
            'correlation_id' => $correlationId,
            'reason'         => 'BLOCKED_PENDING_MEETING_SERVICE_CONTRACT',
            'service_url'    => rtrim((string) env('KONN3CT_BASE_URL', ''), '/') ?: '(not configured)',
            'note'           => 'Implement MeetingEnforcementGateway with a confirmed endpoint to enable live disconnection.',
        ]);

        return [
            'meeting_join_tokens' => MeetingEnforcementGateway::STATUS_BLOCKED,
            'live_disconnect'     => MeetingEnforcementGateway::STATUS_BLOCKED,
            'complete'            => false,
        ];
    }
}
