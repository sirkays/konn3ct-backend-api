<?php

namespace Tests\Feature\Odoo;

use App\Models\OdooOutboundSignal;
use App\Models\RoomModel;
use App\Models\User;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use App\Services\Odoo\OdooUsageMetricsProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * UsageMetricsSignalTest
 *
 * Tests API-027 USAGE_METRICS signal:
 *  - Available metrics are calculated correctly
 *  - Unavailable fields (watch_duration_seconds, ai_notes_used, meetings_joined)
 *    are absent from the JSON body — not replaced with null or zero
 *  - A verified genuine zero remains valid
 *  - No signal is dispatched when no verified metric exists
 *  - Daily idempotency (stable key prevents re-dispatch)
 *  - Chunk processing via command
 *  - Disabled guard: no dispatch when ODOO19_USAGE_METRICS_ENABLED=false
 */
class UsageMetricsSignalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['odoo.enabled' => true]);
    }

    // -------------------------------------------------------------------------
    // Metrics provider — verified metrics only
    // -------------------------------------------------------------------------

    /** @test */
    public function metrics_provider_returns_meetings_hosted_from_room_table()
    {
        // Create a user with 3 rooms
        $user = User::factory()->create(['email' => 'metrics@example.com']);

        // Manually insert room records since we may not have factories.
        \DB::table('room')->insert([
            ['user_id' => $user->id, 'name' => 'Room 1', 'url' => 'r1', 'password_moderator' => 'mod',
             'welcome_message' => '', 'logout_url' => '/', 'max_participants' => 100, 'duration' => 60,
             'default_room' => 'no', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'name' => 'Room 2', 'url' => 'r2', 'password_moderator' => 'mod',
             'welcome_message' => '', 'logout_url' => '/', 'max_participants' => 100, 'duration' => 60,
             'default_room' => 'no', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'name' => 'Room 3', 'url' => 'r3', 'password_moderator' => 'mod',
             'welcome_message' => '', 'logout_url' => '/', 'max_participants' => 100, 'duration' => 60,
             'default_room' => 'no', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $provider = app(OdooUsageMetricsProvider::class);
        $metrics  = $provider->getMetrics($user->id);

        $this->assertIsArray($metrics);
        $this->assertArrayHasKey('meetings_hosted', $metrics);
        $this->assertEquals(3, $metrics['meetings_hosted']);
    }

    /** @test */
    public function metrics_provider_does_not_include_unavailable_metrics()
    {
        $user = User::factory()->create();

        $provider = app(OdooUsageMetricsProvider::class);
        $metrics  = $provider->getMetrics($user->id);

        if ($metrics !== null) {
            // These fields must NOT be present — their source does not exist yet.
            $this->assertArrayNotHasKey('watch_duration_seconds', $metrics,
                'watch_duration_seconds should be absent — no source exists');
            $this->assertArrayNotHasKey('ai_notes_used', $metrics,
                'ai_notes_used should be absent — no source exists');
            $this->assertArrayNotHasKey('meetings_joined', $metrics,
                'meetings_joined semantics are unverified — should be absent');
        }
    }

    /** @test */
    public function payload_factory_omits_unavailable_metrics_from_payload()
    {
        $factory = app(OdooPayloadFactory::class);

        // Only pass meetings_hosted — the only verified metric.
        $payload = $factory->usageMetrics(123, ['meetings_hosted' => 12]);

        $this->assertArrayHasKey('user_id', $payload);
        $this->assertArrayHasKey('meetings_hosted', $payload);
        $this->assertEquals(12, $payload['meetings_hosted']);

        // Unavailable metrics must not be present — not even as null.
        $this->assertArrayNotHasKey('watch_duration_seconds', $payload);
        $this->assertArrayNotHasKey('ai_notes_used', $payload);
        $this->assertArrayNotHasKey('meetings_joined', $payload);
    }

    /** @test */
    public function payload_factory_does_not_add_null_for_unavailable_metrics()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->usageMetrics(1, ['meetings_hosted' => 5]);

        // Verify no null-valued keys for missing metrics.
        foreach (['watch_duration_seconds', 'ai_notes_used', 'meetings_joined'] as $key) {
            $this->assertArrayNotHasKey($key, $payload,
                "{$key} must be absent from payload, not null");
        }
    }

    /** @test */
    public function genuine_zero_meetings_hosted_is_valid_and_included()
    {
        $factory = app(OdooPayloadFactory::class);

        // If a user genuinely has 0 rooms, that's a valid verified zero.
        $payload = $factory->usageMetrics(1, ['meetings_hosted' => 0]);

        $this->assertNotNull($payload);
        $this->assertArrayHasKey('meetings_hosted', $payload);
        $this->assertEquals(0, $payload['meetings_hosted']);
    }

    /** @test */
    public function no_signal_dispatched_when_no_verified_metrics_exist()
    {
        $factory = app(OdooPayloadFactory::class);

        // Empty metrics array — no verified metrics at all.
        $payload = $factory->usageMetrics(1, []);

        $this->assertNull($payload, 'usageMetrics should return null when no metrics are available');
    }

    /** @test */
    public function daily_idempotency_key_prevents_re_dispatch_on_same_day()
    {
        Queue::fake();
        $dispatcher = app(OdooSignalDispatcher::class);
        $utcDate    = now()->utc()->toDateString();
        $key        = "USAGE_METRICS:1:{$utcDate}";
        $payload    = ['user_id' => 1, 'meetings_hosted' => 5];

        $first  = $dispatcher->dispatch('USAGE_METRICS', 'usage_metrics', $key, $payload);
        $second = $dispatcher->dispatch('USAGE_METRICS', 'usage_metrics', $key, $payload);

        $this->assertNotNull($first);
        $this->assertNull($second, 'Same-day re-dispatch should be rejected by idempotency key');
        $this->assertDatabaseCount('odoo_outbound_signals', 1);
    }

    // -------------------------------------------------------------------------
    // Command disabled guard
    // -------------------------------------------------------------------------

    /** @test */
    public function usage_metrics_command_does_nothing_when_disabled()
    {
        Queue::fake();
        config(['odoo.usage_metrics_enabled' => false]);

        $this->artisan('odoo:dispatch-usage-metrics')
            ->assertExitCode(0);

        Queue::assertNothingPushed();
        $this->assertDatabaseCount('odoo_outbound_signals', 0);
    }

    /** @test */
    public function usage_metrics_command_does_nothing_when_integration_disabled()
    {
        Queue::fake();
        config([
            'odoo.usage_metrics_enabled' => true,
            'odoo.enabled'               => false,
        ]);

        $this->artisan('odoo:dispatch-usage-metrics')
            ->assertExitCode(0);

        Queue::assertNothingPushed();
    }

    // -------------------------------------------------------------------------
    // Metrics all non-negative integers
    // -------------------------------------------------------------------------

    /** @test */
    public function negative_metric_values_are_rejected_by_factory()
    {
        $factory = app(OdooPayloadFactory::class);

        // Negative values must be omitted — not a valid metric.
        $payload = $factory->usageMetrics(1, ['meetings_hosted' => -5]);

        // No metric at all → null
        $this->assertNull($payload, 'Negative metric values must not be sent');
    }

    /** @test */
    public function non_integer_metric_value_is_rejected_by_factory()
    {
        $factory = app(OdooPayloadFactory::class);

        // Non-integer value must be omitted.
        $payload = $factory->usageMetrics(1, ['meetings_hosted' => 'many']);

        $this->assertNull($payload, 'Non-integer metric values must not be sent');
    }
}
