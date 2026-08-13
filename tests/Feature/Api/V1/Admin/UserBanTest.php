<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Contracts\Admin\MeetingEnforcementGateway;
use App\Models\AdminAuditLog;
use App\Models\AdminRefreshToken;
use App\Models\User;
use App\Services\Admin\AdminAuditService;
use App\Services\Admin\AdminJwtService;
use App\Services\Admin\UnsupportedMeetingEnforcementGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for POST /api/v1/admin/users/{id}/ban
 */
class UserBanTest extends TestCase
{
    use RefreshDatabase;

    protected AdminJwtService $jwtService;
    protected string $routeTemplate = '/api/v1/admin/users/%d/ban';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VisitLogMiddleware::class);
        $this->jwtService = app(AdminJwtService::class);
    }

    protected function makeAdmin(array $overrides = []): User
    {
        return User::factory()->admin()->create($overrides);
    }

    protected function authHeader(User $admin, array $permissions = ['admin.*']): array
    {
        $token = $this->jwtService->issueAccessToken($admin, $permissions)['token'];
        return ['Authorization' => "Bearer {$token}"];
    }

    protected function route(int $id): string
    {
        return sprintf($this->routeTemplate, $id);
    }

    protected function validPayload(): array
    {
        return [
            'reason'           => 'Confirmed severe abuse of the platform.',
            'confirmationCode' => 'CONFIRM BAN',
        ];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Validation
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function missing_confirmation_code_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson($this->route($target->id), [
            'reason' => 'Confirmed severe abuse.',
        ], $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function wrong_confirmation_code_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson($this->route($target->id), [
            'reason'           => 'Confirmed severe abuse of the platform.',
            'confirmationCode' => 'confirm ban', // lowercase — should fail
        ], $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function confirmation_code_is_case_sensitive(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        // Various wrong casing
        foreach (['Confirm Ban', 'CONFIRM ban', 'confirm BAN', 'CONFIRM  BAN'] as $wrong) {
            $response = $this->postJson($this->route($target->id), [
                'reason'           => 'Confirmed severe abuse of the platform.',
                'confirmationCode' => $wrong,
            ], $this->authHeader($admin));
            $response->assertStatus(422, "Expected 422 for confirmationCode: {$wrong}");
        }
    }

    /** @test */
    public function missing_reason_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson($this->route($target->id), [
            'confirmationCode' => 'CONFIRM BAN',
        ], $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Not found
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function unknown_user_returns_404(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->postJson($this->route(999999), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(404)->assertJson(['code' => 'USER_NOT_FOUND']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Status change
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function status_becomes_exactly_banned(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create(['account_status' => null]);

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200)->assertJsonPath('data.status', 'BANNED');

        $target->refresh();
        $this->assertEquals('BANNED', $target->account_status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Self-ban block
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function self_ban_is_rejected(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->postJson($this->route($admin->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(409)->assertJson(['code' => 'USER_STATE_CONFLICT']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Idempotency
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function banning_already_banned_user_is_idempotent(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->banned()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200);
        $this->assertStringContainsString('idempotent', $response->json('message'));

        $target->refresh();
        $this->assertEquals('BANNED', $target->account_status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sanctum tokens
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function sanctum_tokens_are_revoked_on_ban(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $target->createToken('test-token-1');
        $target->createToken('test-token-2');
        $this->assertEquals(2, $target->tokens()->count());

        $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));

        $this->assertEquals(0, $target->tokens()->count());
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Admin refresh token revocation
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_refresh_tokens_revoked_when_target_is_admin(): void
    {
        $actor = $this->makeAdmin();
        $targetAdmin = $this->makeAdmin();

        AdminRefreshToken::create([
            'user_id'    => $targetAdmin->id,
            'token_id'   => hash('sha256', 'ban-test-jti'),
            'expires_at' => now()->addDays(7),
            'revoked_at' => null,
        ]);

        $response = $this->postJson($this->route($targetAdmin->id), $this->validPayload(), $this->authHeader($actor));
        $response->assertStatus(200);

        $unrevoked = AdminRefreshToken::where('user_id', $targetAdmin->id)
            ->whereNull('revoked_at')
            ->count();
        $this->assertEquals(0, $unrevoked);

        $this->assertEquals('REVOKED', $response->json('enforcement.admin_refresh_tokens'));
    }

    /** @test */
    public function admin_refresh_tokens_shows_na_for_regular_user(): void
    {
        $actor = $this->makeAdmin();
        $target = User::factory()->create(['type' => 'user']);

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($actor));
        $response->assertStatus(200);
        $this->assertEquals('N/A', $response->json('enforcement.admin_refresh_tokens'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Meeting enforcement (BLOCKED_PENDING_MEETING_SERVICE_CONTRACT)
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function meeting_enforcement_gateway_is_invoked_and_returns_blocked(): void
    {
        $spy = new class extends UnsupportedMeetingEnforcementGateway {
            public bool $called = false;
            public function revokeAndDisconnect(int $userId, string $correlationId): array {
                $this->called = true;
                return parent::revokeAndDisconnect($userId, $correlationId);
            }
        };
        $this->app->instance(MeetingEnforcementGateway::class, $spy);

        $admin = $this->makeAdmin();
        $target = User::factory()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200);

        $this->assertTrue($spy->called);

        $enforcement = $response->json('enforcement');
        $this->assertEquals('BLOCKED_PENDING_MEETING_SERVICE_CONTRACT', $enforcement['meeting_join_tokens']);
        $this->assertEquals('BLOCKED_PENDING_MEETING_SERVICE_CONTRACT', $enforcement['live_disconnect']);
        $this->assertFalse($enforcement['complete']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Audit record
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function high_priority_audit_record_is_created(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();

        $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));

        $audit = AdminAuditLog::where('event_code', AdminAuditService::EVENT_USER_BANNED)
            ->where('actor_admin_id', $admin->id)
            ->where('target_user_id', $target->id)
            ->first();

        $this->assertNotNull($audit);
        $this->assertEquals(AdminAuditService::PRIORITY_HIGH, $audit->priority);
        $this->assertNotNull($audit->correlation_id);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Correlation ID in response header
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function response_includes_correlation_id_header(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200);
        $this->assertNotNull($response->headers->get('X-Correlation-Id'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Permission: exact users:ban vs admin.*
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function users_ban_permission_cannot_ban_another_admin(): void
    {
        $actor = $this->makeAdmin();
        $targetAdmin = $this->makeAdmin();

        $token = $this->jwtService->issueAccessToken($actor, ['users:ban'])['token'];

        $response = $this->postJson(
            $this->route($targetAdmin->id),
            $this->validPayload(),
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(403)->assertJson(['code' => 'FORBIDDEN']);
    }

    /** @test */
    public function admin_wildcard_can_ban_another_admin(): void
    {
        $actor = $this->makeAdmin();
        $targetAdmin = $this->makeAdmin();

        $token = $this->jwtService->issueAccessToken($actor, ['admin.*'])['token'];

        $response = $this->postJson(
            $this->route($targetAdmin->id),
            $this->validPayload(),
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(200);
    }
}
