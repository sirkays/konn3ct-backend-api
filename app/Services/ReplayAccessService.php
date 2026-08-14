<?php

namespace App\Services;

use App\Models\EventRecording;
use App\Models\PreRegModel;
use App\Models\ReplayAccessToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * ReplayAccessService
 *
 * Issues and validates short-lived, cryptographically opaque access tokens
 * for BBB recording replay.
 *
 * SECURITY MODEL:
 *   - Tokens are 32 bytes of CSPRNG entropy, hex-encoded (64 chars plaintext).
 *   - Only the SHA-256 hash is stored in the database.
 *   - The plaintext token is returned ONCE to the caller and never stored/logged.
 *   - Token validation enforces: not expired, not revoked, correct user, correct recording.
 *   - The /stream endpoint reveals a permanently public BBB URL.
 *     BBB protection is PARTIAL (API-layer sharing resistance only).
 *
 * ENTITLEMENT MODEL:
 *   - A user has replay access if they have a prereg_users record with
 *     paid=1 AND the email matches Auth::user()->email for the resolved event.
 *   - The event is resolved server-side via EventRecording → room → prereg.
 *   - The client never supplies eventId or email.
 */
class ReplayAccessService
{
    /**
     * Attempt to issue a replay access token for the authenticated user.
     *
     * @param  int    $userId           Authenticated user's ID
     * @param  string $userEmail        Authenticated user's email (from Auth)
     * @param  string $recordingId      BBB recordID (from client)
     * @return array{token: string, expires_at: \Carbon\Carbon}|null
     *         Returns null if the user is not entitled.
     */
    public function issue(int $userId, string $userEmail, string $recordingId): ?array
    {
        // Step 1: Resolve event from recordingId server-side. Fail closed.
        $eventId = EventRecording::resolveEventId($recordingId);
        if ($eventId === null) {
            Log::info('ReplayAccessService::issue: recording not mapped to any event', [
                'recording_id' => $recordingId,
            ]);
            return null;
        }

        // Step 2: Verify entitlement — authenticated user's email has paid=1 for this event.
        // We use Auth email (server-resolved), never client-supplied email.
        $entitled = \App\Models\PreRegUserModel::where('prereg_id', $eventId)
            ->where('email', strtolower($userEmail))
            ->where('paid', 1)
            ->exists();

        if (!$entitled) {
            return null;
        }

        // Step 3: Generate opaque token.
        $plaintext = bin2hex(random_bytes(32)); // 64-char hex string
        $hash      = hash('sha256', $plaintext);
        $ttl       = (int) config('replay.access_token_ttl_seconds', 300);
        $expiresAt = now()->addSeconds($ttl);

        // Step 4: Persist the hash only.
        ReplayAccessToken::create([
            'token_hash'   => $hash,
            'user_id'      => $userId,
            'recording_id' => $recordingId,
            'event_id'     => $eventId,
            'expires_at'   => $expiresAt,
        ]);

        // Return plaintext token ONCE — never stored or logged.
        return [
            'token'      => $plaintext,
            'expires_at' => $expiresAt,
        ];
    }

    /**
     * Validate a replay access token for a given user and recording.
     *
     * Rejects if: expired, revoked, user mismatch, or recording mismatch.
     * Returns the token model on success, null on any rejection.
     *
     * @param  string $plaintextToken
     * @param  int    $userId
     * @param  string $recordingId
     * @return ReplayAccessToken|null
     */
    public function validate(string $plaintextToken, int $userId, string $recordingId): ?ReplayAccessToken
    {
        $token = ReplayAccessToken::findValid($plaintextToken);

        if ($token === null) {
            return null; // Not found, expired, or revoked.
        }

        if (!$token->isValidFor($userId, $recordingId)) {
            Log::warning('ReplayAccessService::validate: cross-user or cross-recording token attempt', [
                'token_user_id'      => $token->user_id,
                'request_user_id'    => $userId,
                'token_recording_id' => $token->recording_id,
                'request_recording'  => $recordingId,
            ]);
            return null;
        }

        return $token;
    }
}
