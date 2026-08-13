<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for AdminJwtMiddleware and AdminPermissionMiddleware.
 */
class AdminJwtMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected AdminJwtService $jwtService;
    protected string $testRoute = '/api/v1/admin/users';

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

    protected function issueAccessToken(User $user, array $permissions = ['admin.*']): string
    {
        $data = $this->jwtService->issueAccessToken($user, $permissions);
        return $data['token'];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 401 — Authentication failures
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function missing_bearer_token_returns_401(): void
    {
        $response = $this->getJson($this->testRoute);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function empty_bearer_token_returns_401(): void
    {
        $response = $this->getJson($this->testRoute, ['Authorization' => 'Bearer ']);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function oversized_token_returns_401(): void
    {
        $token = str_repeat('a', 4097);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function tampered_token_returns_401(): void
    {
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin);
        // Change a character in the middle of the signature part (3rd segment)
        $parts = explode('.', $token);
        // Flip a char in the middle of the signature (not the padding at the end)
        $sig = $parts[2];
        $midPos = (int) (strlen($sig) / 2);
        $charAtMid = $sig[$midPos];
        $newChar = $charAtMid === 'A' ? 'B' : 'A';
        $parts[2] = substr($sig, 0, $midPos) . $newChar . substr($sig, $midPos + 1);
        $tampered = implode('.', $parts);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$tampered}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function refresh_token_used_as_access_token_returns_401(): void
    {
        $admin = $this->makeAdmin();
        $refreshData = $this->jwtService->issueRefreshToken($admin, '127.0.0.1', 'test');
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$refreshData['token']}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function wrong_secret_token_returns_401(): void
    {
        // Construct a token with a different secret by hand — simulate wrong-secret scenario
        // We use a tampered token which will fail signature check
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin);
        $parts = explode('.', $token);
        // Replace the signature part with garbage
        $parts[2] = base64_encode('invalidsignature');
        $badToken = implode('.', $parts);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$badToken}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function deleted_admin_user_returns_401(): void
    {
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin);
        $admin->delete();
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function non_admin_user_returns_401(): void
    {
        $user = User::factory()->create(['type' => 'user']);
        $token = $this->issueAccessToken($user);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function suspended_admin_returns_401(): void
    {
        $admin = $this->makeAdmin(['account_status' => 'SUSPENDED']);
        $token = $this->issueAccessToken($admin);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function banned_admin_returns_401(): void
    {
        $admin = $this->makeAdmin(['account_status' => 'BANNED']);
        $token = $this->issueAccessToken($admin);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(401)->assertJson(['code' => 'UNAUTHENTICATED']);
    }

    /** @test */
    public function null_account_status_admin_is_allowed(): void
    {
        $admin = $this->makeAdmin(['account_status' => null]);
        $token = $this->issueAccessToken($admin, ['users:read']);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        // Should not be 401 — may be 200 or other non-auth error
        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Permission tests
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function admin_wildcard_authorizes_users_list(): void
    {
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin, ['admin.*']);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /** @test */
    public function exact_permission_users_read_authorizes_list(): void
    {
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin, ['users:read']);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(200)->assertJson(['success' => true]);
    }

    /** @test */
    public function missing_permission_returns_403(): void
    {
        $admin = $this->makeAdmin();
        // Give only financials:read — not users:read
        $token = $this->issueAccessToken($admin, ['financials:read']);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(403)->assertJson(['code' => 'FORBIDDEN']);
    }

    /** @test */
    public function admin_wildcard_authorizes_suspend(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $token = $this->issueAccessToken($admin, ['admin.*']);
        $response = $this->postJson("/api/v1/admin/users/{$target->id}/suspend", [
            'reason' => 'Testing permission authorization for suspension.',
        ], ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(200);
    }

    /** @test */
    public function missing_suspend_permission_returns_403(): void
    {
        $admin = $this->makeAdmin();
        $target = User::factory()->create();
        $token = $this->issueAccessToken($admin, ['users:read']); // not users:suspend
        $response = $this->postJson("/api/v1/admin/users/{$target->id}/suspend", [
            'reason' => 'Testing permission deny.',
        ], ['Authorization' => "Bearer {$token}"]);
        $response->assertStatus(403)->assertJson(['code' => 'FORBIDDEN']);
    }

    /** @test */
    public function cache_control_no_store_header_is_present(): void
    {
        $admin = $this->makeAdmin();
        $token = $this->issueAccessToken($admin, ['users:read']);
        $response = $this->getJson($this->testRoute, ['Authorization' => "Bearer {$token}"]);
        // Framework middleware may append ', private' — verify no-store is present
        $cacheControl = $response->headers->get('Cache-Control', '');
        $this->assertStringContainsString('no-store', $cacheControl);
    }
}
