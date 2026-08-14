<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EventRecording;
use App\Models\PreRegUserModel;
use App\Services\EventRecordingSyncService;
use App\Services\ReplayAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ReplayAccessController
 *
 * Issues and consumes short-lived replay access tokens for BBB recordings.
 *
 * SECURITY NOTE:
 *   These tokens control access at the Konn3ct API layer only.
 *   The /stream endpoint reveals a permanently public BBB playback URL.
 *   BBB protection status: PARTIAL (API-layer sharing resistance only).
 *
 * POST /api/v1/replays/{recordingId}/access  → issue token
 * GET  /api/v1/replays/{recordingId}/stream  → exchange token for BBB URL
 */
class ReplayAccessController extends Controller
{
    public function __construct(
        private readonly ReplayAccessService      $replayService,
        private readonly EventRecordingSyncService $syncService
    ) {}

    /**
     * Issue a short-lived replay access token.
     * Verifies entitlement via prereg_users.paid=1 for the authenticated user.
     *
     * @param  Request $request
     * @param  string  $recordingId  BBB recordID
     * @return JsonResponse
     */
    public function issueToken(Request $request, string $recordingId): JsonResponse
    {
        $user = Auth::user();

        // Validate recordingId format — basic sanity check.
        if (!preg_match('/^[a-zA-Z0-9\-]+$/', $recordingId) || strlen($recordingId) > 300) {
            return response()->json(['message' => 'Invalid recording identifier.'], 422);
        }

        // Attempt to sync if the recording is not yet mapped.
        if (EventRecording::resolveEventId($recordingId) === null) {
            $this->syncService->syncForUser($user->id);
        }

        $result = $this->replayService->issue($user->id, $user->email, $recordingId);

        if ($result === null) {
            // Could be: recording not found, not mapped, or user not entitled.
            // Always return 403 — do not leak whether the recording exists.
            return response()->json(['message' => 'Access denied.'], 403);
        }

        return response()->json([
            'token'      => $result['token'],
            'expires_at' => $result['expires_at']->toISOString(),
        ], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }

    /**
     * Exchange a valid replay access token for the BBB playback URL.
     *
     * NOTE: The BBB URL returned here is a permanently public URL on the BBB server.
     * This endpoint provides API-layer sharing resistance — it does NOT revoke
     * or expire the underlying BBB URL. BBB protection: PARTIAL.
     *
     * @param  Request $request
     * @param  string  $recordingId  BBB recordID
     * @return JsonResponse
     */
    public function stream(Request $request, string $recordingId): JsonResponse
    {
        $user  = Auth::user();
        $token = $request->query('token') ?? $request->header('X-Replay-Token');

        if (!$token) {
            return response()->json(['message' => 'Token required.'], 401);
        }

        $validToken = $this->replayService->validate((string) $token, $user->id, $recordingId);

        if (!$validToken) {
            return response()->json(['message' => 'Invalid, expired, or revoked token.'], 401);
        }

        // Resolve the BBB recording to get the playback URL.
        // This is a server-side lookup — the client does not supply the URL.
        try {
            if (!app()->environment(['local', 'testing'])) {
                $recordings = \Bigbluebutton::getRecordings(['recordID' => $recordingId]);
                $playbackUrl = $recordings[0]['playbackUrl'] ?? null;
            } else {
                $playbackUrl = 'https://bbb.example.com/playback/presentation/2.3/' . $recordingId;
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Recording not available.'], 503);
        }

        if (!$playbackUrl) {
            return response()->json(['message' => 'Recording not found.'], 404);
        }

        return response()->json([
            'playback_url' => $playbackUrl,
            // NOTICE: This URL is permanently public on the BBB server.
            // API-layer token protection only.
        ], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }
}
