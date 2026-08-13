<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Models\User;
use App\Services\Admin\AdminJwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for GET /api/v1/admin/users
 */
class UserListTest extends TestCase
{
    use RefreshDatabase;

    protected AdminJwtService $jwtService;
    protected string $route = '/api/v1/admin/users';

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

    protected function authHeader(User $admin, array $permissions = ['users:read']): array
    {
        $token = $this->jwtService->issueAccessToken($admin, $permissions)['token'];
        return ['Authorization' => "Bearer {$token}"];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Default response
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function returns_default_paginated_response(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson($this->route, $this->authHeader($admin));
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => ['page', 'limit', 'total', 'total_pages', 'has_next', 'has_previous'],
            ])
            ->assertJsonPath('meta.page', 1)
            ->assertJsonPath('meta.limit', 25);
    }

    /** @test */
    public function unauthenticated_request_returns_401(): void
    {
        $this->getJson($this->route)->assertStatus(401);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Pagination
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function pagination_respects_page_and_limit(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->count(5)->create();

        $response = $this->getJson("{$this->route}?page=2&limit=2", $this->authHeader($admin));
        $response->assertStatus(200)
            ->assertJsonPath('meta.page', 2)
            ->assertJsonPath('meta.limit', 2)
            ->assertJsonPath('meta.has_previous', true);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function limit_capped_at_100(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?limit=101", $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function page_minimum_is_1(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?page=0", $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Search
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function search_by_firstname_case_insensitive(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['firstname' => 'Amina', 'lastname' => 'Bello']);
        User::factory()->create(['firstname' => 'John', 'lastname' => 'Doe']);

        $response = $this->getJson("{$this->route}?search=amina", $this->authHeader($admin));
        $response->assertStatus(200);
        $names = collect($response->json('data'))->pluck('firstname')->toArray();
        $this->assertContains('Amina', $names);
        $this->assertNotContains('John', $names);
    }

    /** @test */
    public function search_by_lastname(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['firstname' => 'Amina', 'lastname' => 'Bello']);
        User::factory()->create(['firstname' => 'John', 'lastname' => 'Doe']);

        $response = $this->getJson("{$this->route}?search=Bello", $this->authHeader($admin));
        $response->assertStatus(200);
        $names = collect($response->json('data'))->pluck('lastname')->toArray();
        $this->assertContains('Bello', $names);
        $this->assertNotContains('Doe', $names);
    }

    /** @test */
    public function search_by_email(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['email' => 'amina@konn3ct.com']);
        User::factory()->create(['email' => 'john@other.com']);

        $response = $this->getJson("{$this->route}?search=konn3ct", $this->authHeader($admin));
        $response->assertStatus(200);
        $emails = collect($response->json('data'))->pluck('email')->toArray();
        $this->assertContains('amina@konn3ct.com', $emails);
    }

    /** @test */
    public function search_by_numeric_id(): void
    {
        $admin = $this->makeAdmin();
        $user = User::factory()->create();

        $response = $this->getJson("{$this->route}?search={$user->id}", $this->authHeader($admin));
        $response->assertStatus(200);
        $ids = collect($response->json('data'))->pluck('id')->toArray();
        $this->assertContains($user->id, $ids);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Filters
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function role_filter_returns_only_matching_type(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['type' => 'user']);
        User::factory()->admin()->create();

        $response = $this->getJson("{$this->route}?role=user", $this->authHeader($admin));
        $response->assertStatus(200);
        foreach ($response->json('data') as $u) {
            $this->assertEquals('user', $u['role']);
        }
    }

    /** @test */
    public function status_filter_active_includes_null_account_status(): void
    {
        $admin = $this->makeAdmin(['account_status' => null]);
        User::factory()->create(['account_status' => null]);
        User::factory()->create(['account_status' => 'SUSPENDED']);

        $response = $this->getJson("{$this->route}?status=ACTIVE", $this->authHeader($admin));
        $response->assertStatus(200);
        foreach ($response->json('data') as $u) {
            $this->assertContains($u['status'], ['ACTIVE', null]);
        }
    }

    /** @test */
    public function status_filter_suspended_returns_suspended_only(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['account_status' => 'SUSPENDED']);
        User::factory()->create(['account_status' => null]);

        $response = $this->getJson("{$this->route}?status=SUSPENDED", $this->authHeader($admin));
        $response->assertStatus(200);
        foreach ($response->json('data') as $u) {
            $this->assertEquals('SUSPENDED', $u['status']);
        }
    }

    /** @test */
    public function combined_role_and_status_filters(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['type' => 'user', 'account_status' => 'SUSPENDED']);
        User::factory()->create(['type' => 'user', 'account_status' => null]);
        User::factory()->admin()->create(['account_status' => 'SUSPENDED']);

        $response = $this->getJson("{$this->route}?role=user&status=SUSPENDED", $this->authHeader($admin));
        $response->assertStatus(200);
        foreach ($response->json('data') as $u) {
            $this->assertEquals('user', $u['role']);
            $this->assertEquals('SUSPENDED', $u['status']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sorting
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function sort_by_email_asc(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['email' => 'z@test.com']);
        User::factory()->create(['email' => 'a@test.com']);

        $response = $this->getJson("{$this->route}?sortBy=email&sortOrder=asc", $this->authHeader($admin));
        $response->assertStatus(200);
        $emails = collect($response->json('data'))->pluck('email')->toArray();
        $sorted = $emails;
        sort($sorted);
        $this->assertEquals($sorted, $emails);
    }

    /** @test */
    public function sort_by_created_at_desc(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?sortBy=createdAt&sortOrder=desc", $this->authHeader($admin));
        $response->assertStatus(200);
    }

    public static function sortFieldProvider(): array
    {
        return [
            ['id', 'asc'],
            ['id', 'desc'],
            ['name', 'asc'],
            ['name', 'desc'],
            ['email', 'asc'],
            ['email', 'desc'],
            ['role', 'asc'],
            ['status', 'asc'],
            ['createdAt', 'asc'],
            ['createdAt', 'desc'],
            ['lastUsed', 'asc'],
            ['lastUsed', 'desc'],
        ];
    }

    /**
     * @test
     * @dataProvider sortFieldProvider
     */
    public function all_sort_fields_return_200(string $field, string $order): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?sortBy={$field}&sortOrder={$order}", $this->authHeader($admin));
        $response->assertStatus(200);
    }

    /** @test */
    public function invalid_sort_field_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?sortBy=password", $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function invalid_sort_order_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson("{$this->route}?sortOrder=random", $this->authHeader($admin));
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Sensitive field protection
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function sensitive_fields_are_not_exposed(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create();

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $response->assertStatus(200);

        foreach ($response->json('data') as $user) {
            $this->assertArrayNotHasKey('password', $user);
            $this->assertArrayNotHasKey('remember_token', $user);
            $this->assertArrayNotHasKey('two_factor_secret', $user);
            $this->assertArrayNotHasKey('two_factor_recovery_codes', $user);
        }
    }

    /** @test */
    public function response_includes_both_status_fields(): void
    {
        $admin = $this->makeAdmin();
        User::factory()->create(['account_status' => 'SUSPENDED', 'status' => 'active']);

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $data = $response->json('data');
        $suspended = collect($data)->firstWhere('status', 'SUSPENDED');
        $this->assertNotNull($suspended);
        $this->assertArrayHasKey('subscription_status', $suspended);
    }
}
