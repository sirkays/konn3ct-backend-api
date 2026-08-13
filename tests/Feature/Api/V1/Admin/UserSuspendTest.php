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
 * Tests for POST /api/v1/admin/users/{id}/suspend
 */
class UserSuspendTest extends TestCase
{
    use RefreshDatabase;

    protected AdminJwtService $jwtService;
    protected string $routeTemplate = '/api/v1/admin/users/%d/suspend';

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
        return ['reason' => 'Repeated violation of platform policies during meetings.'];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Validation
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function missing_reason_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson($this->route($target->id), [], $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function short_reason_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson(
            $this->route($target->id),
            ['reason' => 'Too short'],
            $this->authHeader($admin)
        );
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function reason_trimmed_before_validation(): void
    {
        // Reason that is only spaces + fewer than 10 chars should fail
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $response = $this->postJson(
            $this->route($target->id),
            ['reason' => '   short   '],
            $this->authHeader($admin)
        );
        $response->assertStatus(422);
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
    public function status_becomes_exactly_suspended(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create(['account_status' => null]);

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200)->assertJsonPath('data.status', 'SUSPENDED');

        $target->refresh();
        $this->assertEquals('SUSPENDED', $target->account_status);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // State conflicts
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function banned_user_cannot_be_downgraded_to_suspended(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->banned()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(409)->assertJson(['code' => 'USER_STATE_CONFLICT']);

        // Account status must still be BANNED
        $target->refresh();
        $this->assertEquals('BANNED', $target->account_status);
    }

    /** @test */
    public function self_suspension_is_rejected(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->postJson($this->route($admin->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(409)->assertJson(['code' => 'USER_STATE_CONFLICT']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Idempotency
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function suspending_already_suspended_user_is_idempotent(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->suspended()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200);
        $this->assertStringContainsString('idempotent', $response->json('message'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sanctum tokens
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function sanctum_tokens_are_revoked_on_suspension(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        // Create a Sanctum token for the target
        $target->createToken('test-token');
        $this->assertGreaterThan(0, $target->tokens()->count());

        $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));

        $this->assertEquals(0, $target->tokens()->count());
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Admin token revocation (when target is admin)
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_refresh_tokens_revoked_when_target_is_admin(): void
    {
        $actor = $this->makeAdmin();
        $targetAdmin = $this->makeAdmin();

        // Create an active refresh token for the target admin
        AdminRefreshToken::create([
            'user_id'    => $targetAdmin->id,
            'token_id'   => hash('sha256', 'test-jti'),
            'expires_at' => now()->addDays(7),
            'revoked_at' => null,
        ]);

        $this->postJson($this->route($targetAdmin->id), $this->validPayload(), $this->authHeader($actor));

        $unrevoked = AdminRefreshToken::where('user_id', $targetAdmin->id)
            ->whereNull('revoked_at')
            ->count();
        $this->assertEquals(0, $unrevoked);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Audit record
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function audit_record_is_created_with_correct_event_and_actor(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();

        $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));

        $audit = AdminAuditLog::where('event_code', AdminAuditService::EVENT_USER_SUSPENDED)
            ->where('actor_admin_id', $admin->id)
            ->where('target_user_id', $target->id)
            ->first();

        $this->assertNotNull($audit);
        $this->assertEquals(AdminAuditService::PRIORITY_NORMAL, $audit->priority);
        $this->assertNotNull($audit->correlation_id);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Meeting enforcement stub (truthful response)
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function meeting_enforcement_gateway_is_invoked_and_returns_blocked(): void
    {
        // Bind a spy/fake
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

    /** @test */
    public function response_includes_truthful_enforcement_object(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();

        $response = $this->postJson($this->route($target->id), $this->validPayload(), $this->authHeader($admin));
        $response->assertStatus(200);

        $enforcement = $response->json('enforcement');
        $this->assertArrayHasKey('account_access', $enforcement);
        $this->assertArrayHasKey('sanctum_tokens', $enforcement);
        $this->assertArrayHasKey('complete', $enforcement);
        $this->assertEquals('ENFORCED', $enforcement['account_access']);
        $this->assertEquals('REVOKED', $enforcement['sanctum_tokens']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Permission: exact users:suspend vs admin.*
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function users_suspend_permission_cannot_moderate_another_admin(): void
    {
        $actor = $this->makeAdmin();
        $targetAdmin = $this->makeAdmin();

        $token = $this->jwtService->issueAccessToken($actor, ['users:suspend'])['token'];

        $response = $this->postJson(
            $this->route($targetAdmin->id),
            $this->validPayload(),
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(403)->assertJson(['code' => 'FORBIDDEN']);
    }

    /** @test */
    public function admin_wildcard_can_suspend_another_admin(): void
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
