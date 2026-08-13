<?php

namespace Tests\Feature\Api\V1\Admin;

use App\Http\Middleware\VisitLogMiddleware;
use App\Models\User;
use App\Models\PaymentModel;
use App\Services\Admin\AdminJwtService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests for GET /api/v1/admin/financials/transactions
 */
class FinancialTransactionsTest extends TestCase
{
    use RefreshDatabase;

    protected AdminJwtService $jwtService;
    protected string $route = '/api/v1/admin/financials/transactions';

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

    protected function createPayment(array $overrides = []): PaymentModel
    {
        return PaymentModel::create(array_merge([
            'user_id'          => User::factory()->create()->id,
            'type'             => 'Subscription',
            'plan'             => 1,
            'gateway'          => 'Paystack',
            'currency'         => 'NGN',
            'status'           => 'success',
            'amount'           => 5000,
            'date'             => now(),
            'reference'        => 'REF-' . uniqid(),
            'gateway_reference' => 'GW-' . uniqid(),
            'gateway_response' => json_encode(['id' => 'txn_123', 'status' => 'success']),
            'duration'         => '30',
        ], $overrides));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Authentication
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function unauthenticated_request_returns_401(): void
    {
        $this->getJson($this->route)->assertStatus(401);
    }

    /** @test */
    public function missing_financials_read_permission_returns_403(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson($this->route, $this->authHeader($admin, ['users:read']));
        $response->assertStatus(403)->assertJson(['code' => 'FORBIDDEN']);
    }

    /** @test */
    public function financials_read_permission_grants_access(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson($this->route, $this->authHeader($admin, ['financials:read']));
        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Pagination
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function returns_correct_pagination_meta(): void
    {
        $admin = $this->makeAdmin();
        foreach (range(1, 5) as $i) {
            $this->createPayment();
        }
        $response = $this->getJson("{$this->route}?page=1&limit=2", $this->authHeader($admin));
        $response->assertStatus(200)
            ->assertJsonPath('meta.page', 1)
            ->assertJsonPath('meta.limit', 2)
            ->assertJsonPath('meta.total', 5)
            ->assertJsonPath('meta.total_pages', 3)
            ->assertJsonPath('meta.has_next', true)
            ->assertJsonPath('meta.has_previous', false);
    }

    /** @test */
    public function empty_result_has_correct_meta(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson($this->route, $this->authHeader($admin));
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 0)
            ->assertJsonPath('meta.total_pages', 0)
            ->assertJsonPath('meta.has_next', false)
            ->assertJsonPath('meta.has_previous', false);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Filters
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function status_filter_returns_matching_records(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['status' => 'success']);
        $this->createPayment(['status' => 'failed']);

        $response = $this->getJson("{$this->route}?status=success", $this->authHeader($admin));
        $response->assertStatus(200)->assertJsonPath('meta.total', 1);
        $this->assertEquals('success', $response->json('data.0.status'));
    }

    /** @test */
    public function payment_type_filter_returns_matching_records(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['type' => 'Subscription']);
        $this->createPayment(['type' => 'Donation']);

        $response = $this->getJson("{$this->route}?paymentType=Subscription", $this->authHeader($admin));
        $response->assertStatus(200)->assertJsonPath('meta.total', 1);
        $this->assertEquals('Subscription', $response->json('data.0.payment_type'));
    }

    /** @test */
    public function gateway_filter_returns_matching_records(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['gateway' => 'Paystack']);
        $this->createPayment(['gateway' => 'Flutterwave']);

        $response = $this->getJson("{$this->route}?gateway=Paystack", $this->authHeader($admin));
        $response->assertStatus(200)->assertJsonPath('meta.total', 1);
        $this->assertEquals('Paystack', $response->json('data.0.gateway'));
    }

    /** @test */
    public function date_range_filter_is_inclusive(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['date' => '2026-01-01 10:00:00']);
        $this->createPayment(['date' => '2026-06-15 10:00:00']);
        $this->createPayment(['date' => '2026-12-31 23:59:59']);

        $response = $this->getJson(
            "{$this->route}?startDate=2026-01-01&endDate=2026-06-15",
            $this->authHeader($admin)
        );
        $response->assertStatus(200)->assertJsonPath('meta.total', 2);
    }

    /** @test */
    public function end_date_earlier_than_start_date_returns_422(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson(
            "{$this->route}?startDate=2026-06-15&endDate=2026-01-01",
            $this->authHeader($admin)
        );
        $response->assertStatus(422)->assertJson(['code' => 'VALIDATION_ERROR']);
    }

    /** @test */
    public function combined_filters_work_together(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['status' => 'success', 'gateway' => 'Paystack', 'date' => '2026-05-01 00:00:00']);
        $this->createPayment(['status' => 'failed', 'gateway' => 'Paystack', 'date' => '2026-05-01 00:00:00']);
        $this->createPayment(['status' => 'success', 'gateway' => 'Flutterwave', 'date' => '2026-05-01 00:00:00']);

        $response = $this->getJson(
            "{$this->route}?status=success&gateway=Paystack",
            $this->authHeader($admin)
        );
        $response->assertStatus(200)->assertJsonPath('meta.total', 1);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Ordering and response fields
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function default_order_is_date_desc_id_desc(): void
    {
        $admin = $this->makeAdmin();
        $p1 = $this->createPayment(['date' => '2026-01-01 00:00:00']);
        $p2 = $this->createPayment(['date' => '2026-06-01 00:00:00']);

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $response->assertStatus(200);
        $ids = collect($response->json('data'))->pluck('id')->toArray();
        $this->assertEquals($p2->id, $ids[0]);
        $this->assertEquals($p1->id, $ids[1]);
    }

    /** @test */
    public function response_includes_required_fields(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment();
        $response = $this->getJson($this->route, $this->authHeader($admin));
        $data = $response->json('data.0');
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('reference', $data);
        $this->assertArrayHasKey('gateway_reference', $data);
        $this->assertArrayHasKey('amount', $data);
        $this->assertArrayHasKey('currency', $data);
        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('gateway', $data);
        $this->assertArrayHasKey('payment_type', $data);
        $this->assertArrayHasKey('user_id', $data);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('raw_webhook_payload', $data);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Gateway response decoding
    // ─────────────────────────────────────────────────────────────────────────

    /** @test */
    public function valid_json_gateway_response_is_decoded(): void
    {
        $admin = $this->makeAdmin();
        $payload = ['id' => 'txn_123', 'status' => 'success', 'amount' => 5000];
        $this->createPayment(['gateway_response' => json_encode($payload)]);

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $rawPayload = $response->json('data.0.raw_webhook_payload');
        $this->assertIsArray($rawPayload);
        $this->assertEquals('txn_123', $rawPayload['id']);
    }

    /** @test */
    public function invalid_json_gateway_response_is_handled_safely(): void
    {
        $admin = $this->makeAdmin();
        $this->createPayment(['gateway_response' => 'not-valid-json{{{']);

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $rawPayload = $response->json('data.0.raw_webhook_payload');
        // Should be a safe fallback, not null (unless it is) and not crash
        $this->assertNotNull($rawPayload);
        $this->assertArrayHasKey('__type', $rawPayload);
        $this->assertEquals('raw_non_json_legacy_value', $rawPayload['__type']);
    }

    /** @test */
    public function null_gateway_response_returns_null_payload(): void
    {
        $admin = $this->makeAdmin();
        // gateway_response column is NOT NULL in migration, but test with empty string
        $this->createPayment(['gateway_response' => '']);

        $response = $this->getJson($this->route, $this->authHeader($admin));
        $rawPayload = $response->json('data.0.raw_webhook_payload');
        $this->assertNull($rawPayload);
    }

    /** @test */
    public function gateway_response_not_exposed_by_default_model_serialization(): void
    {
        // Verify PaymentModel::$hidden still contains gateway_response
        $pm = new PaymentModel();
        $this->assertContains('gateway_response', $pm->getHidden());
    }

    /** @test */
    public function response_has_cache_control_no_store(): void
    {
        $admin = $this->makeAdmin();
        $response = $this->getJson($this->route, $this->authHeader($admin));
        // Framework middleware may append ', private' — verify no-store is present
        $cacheControl = $response->headers->get('Cache-Control', '');
        $this->assertStringContainsString('no-store', $cacheControl);
    }
}
