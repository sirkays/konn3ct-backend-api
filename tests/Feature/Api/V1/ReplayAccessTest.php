<?php

namespace Tests\Feature\Api\V1;

use App\Models\EventRecording;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\ReplayAccessToken;
use App\Models\RoomModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ReplayAccessTest
 *
 * Tests the replay access token issuance and stream endpoint.
 * Covers entitlement enforcement, cross-user rejection, expired/revoked tokens.
 */
class ReplayAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private PreRegModel $event;
    private EventRecording $recording;
    private PreRegUserModel $attendee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['email' => 'attendee@test.com']);

        // Create a paid event
        $this->event = PreRegModel::create([
            'user_id'   => $this->user->id,
            'room_id'   => 1,
            'title'     => 'Test Webinar',
            'reference' => 'WEB-2026-001',
            'host_name' => 'Test Host',
            'date'      => '2026-09-01',
            'time'      => '10:00',
            'timezone'  => 'UTC',
            'about'     => 'Test description',
            'free'      => 0,
            'amount'    => '500000',
            'currency'  => 'NGN',
        ]);

        // Map recording to event
        $this->recording = EventRecording::create([
            'recording_id' => 'bdb4ef8138bd46aa7f134cd4c2a1dd8fc2863f06-001',
            'room_id'      => 1,
            'event_id'     => $this->event->id,
            'state'        => 'published',
            'synced_at'    => now(),
        ]);

        // Create paid attendee record
        $this->attendee = PreRegUserModel::create([
            'prereg_id' => $this->event->id,
            'name'      => 'Test Attendee',
            'email'     => 'attendee@test.com',
            'phone'     => '+234800000001',
            'paid'      => 1,
            'paid_at'   => now(),
        ]);
    }

    /**
     * Authenticated paid attendee can issue a replay access token.
     */
    public function test_paid_attendee_can_issue_token()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_at']);

        // Verify plaintext token is non-empty (64 hex chars)
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $response->json('token'));
    }

    /**
     * Unauthenticated request is rejected with 401.
     */
    public function test_unauthenticated_request_is_rejected()
    {
        $response = $this->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        $response->assertStatus(401);
    }

    /**
     * Unpaid attendee cannot issue a replay access token.
     */
    public function test_unpaid_attendee_cannot_issue_token()
    {
        $unpaidUser = User::factory()->create(['email' => 'unpaid@test.com']);
        PreRegUserModel::create([
            'prereg_id' => $this->event->id,
            'name'      => 'Unpaid',
            'email'     => 'unpaid@test.com',
            'phone'     => '+234800000002',
            'paid'      => 0,
        ]);

        $response = $this->actingAs($unpaidUser, 'sanctum')
            ->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        $response->assertStatus(403);
    }

    /**
     * Unknown recording ID is rejected (fail closed).
     */
    public function test_unknown_recording_id_returns_403()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson('/api/v1/replays/nonexistent-recording-id-999/access');

        $response->assertStatus(403);
    }

    /**
     * Another user cannot use a token issued to a different user.
     */
    public function test_cross_user_token_is_rejected()
    {
        // Issue token for user1
        $tokenResponse = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        $token = $tokenResponse->json('token');

        // User2 tries to use user1's token
        $otherUser = User::factory()->create(['email' => 'other@test.com']);
        PreRegUserModel::create([
            'prereg_id' => $this->event->id,
            'email'     => 'other@test.com',
            'name'      => 'Other',
            'phone'     => '+234800000003',
            'paid'      => 1,
        ]);

        $response = $this->actingAs($otherUser, 'sanctum')
            ->getJson("/api/v1/replays/{$this->recording->recording_id}/stream?token={$token}");

        $response->assertStatus(401);
    }

    /**
     * Expired token is rejected.
     */
    public function test_expired_token_is_rejected()
    {
        // Create an already-expired token
        $hash = hash('sha256', 'expired-plaintext-token-12345');
        ReplayAccessToken::create([
            'token_hash'   => $hash,
            'user_id'      => $this->user->id,
            'recording_id' => $this->recording->recording_id,
            'event_id'     => $this->event->id,
            'expires_at'   => now()->subMinute(), // Already expired
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/replays/{$this->recording->recording_id}/stream?token=expired-plaintext-token-12345");

        $response->assertStatus(401);
    }

    /**
     * Revoked token is rejected.
     */
    public function test_revoked_token_is_rejected()
    {
        $hash = hash('sha256', 'revoked-plaintext-token-99999');
        ReplayAccessToken::create([
            'token_hash'   => $hash,
            'user_id'      => $this->user->id,
            'recording_id' => $this->recording->recording_id,
            'event_id'     => $this->event->id,
            'expires_at'   => now()->addMinutes(10),
            'revoked_at'   => now(), // Revoked
        ]);

        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/replays/{$this->recording->recording_id}/stream?token=revoked-plaintext-token-99999");

        $response->assertStatus(401);
    }

    /**
     * Cross-recording token (valid for recording A, used for recording B) is rejected.
     */
    public function test_cross_recording_token_is_rejected()
    {
        $hash = hash('sha256', 'valid-token-recording-a');
        ReplayAccessToken::create([
            'token_hash'   => $hash,
            'user_id'      => $this->user->id,
            'recording_id' => 'completely-different-recording-id',
            'event_id'     => $this->event->id,
            'expires_at'   => now()->addMinutes(10),
        ]);

        // Token is for a different recordingId — use it for our recording
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/replays/{$this->recording->recording_id}/stream?token=valid-token-recording-a");

        $response->assertStatus(401);
    }

    /**
     * Stream endpoint requires token parameter.
     */
    public function test_stream_endpoint_without_token_returns_401()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->getJson("/api/v1/replays/{$this->recording->recording_id}/stream");

        $response->assertStatus(401);
    }

    /**
     * Only the token hash is stored in the database, never the plaintext.
     */
    public function test_only_token_hash_is_stored()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        $plaintextToken = $response->json('token');
        $expectedHash   = hash('sha256', $plaintextToken);

        $this->assertDatabaseHas('replay_access_tokens', [
            'token_hash' => $expectedHash,
            'user_id'    => $this->user->id,
        ]);

        // Confirm plaintext is NOT stored
        $this->assertDatabaseMissing('replay_access_tokens', [
            'token_hash' => $plaintextToken,
        ]);
    }

    /**
     * Token response has Cache-Control: no-store header.
     */
    public function test_token_response_has_no_cache_header()
    {
        $response = $this->actingAs($this->user, 'sanctum')
            ->postJson("/api/v1/replays/{$this->recording->recording_id}/access");

        // Laravel/Sanctum may append ', private' to no-store depending on middleware.
        $cacheControl = $response->headers->get('Cache-Control', '');
        $this->assertStringContainsString('no-store', $cacheControl,
            'Cache-Control header should contain no-store directive.');
    }
}
