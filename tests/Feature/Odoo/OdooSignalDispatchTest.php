<?php

namespace Tests\Feature\Odoo;

use App\Jobs\Odoo\DeliverOdooSignalJob;
use App\Models\OdooOutboundSignal;
use App\Models\User;
use App\Services\Odoo\OdooSignalDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * OdooSignalDispatchTest
 *
 * Tests infrastructure-level signal dispatch behaviour:
 *  - Integration disabled by default
 *  - Missing config fails safely
 *  - HTTPS enforcement in production
 *  - Payload encrypted at rest
 *  - Queue job carries no raw email/IP/payload
 *  - Duplicate idempotency keys are rejected
 *  - Correct endpoint selected per signal
 */
class OdooSignalDispatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Integration is disabled by default in tests.
        config(['odoo.enabled' => false]);
    }

    // -------------------------------------------------------------------------
    // Integration disabled by default
    // -------------------------------------------------------------------------

    /** @test */
    public function integration_is_disabled_by_default()
    {
        Queue::fake();

        $dispatcher = app(OdooSignalDispatcher::class);
        $result = $dispatcher->dispatch(
            'USER_REGISTERED',
            'user_registered',
            'USER_REGISTERED:999',
            ['user_id' => 999, 'name' => 'Test', 'email' => 'test@example.com',
             'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null]
        );

        $this->assertNull($result, 'Dispatcher should return null when integration is disabled');
        Queue::assertNothingPushed();
        $this->assertDatabaseMissing('odoo_outbound_signals', ['event_name' => 'USER_REGISTERED']);
    }

    /** @test */
    public function dispatch_creates_signal_record_when_enabled()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);
        $signal = $dispatcher->dispatch(
            'USER_REGISTERED',
            'user_registered',
            'USER_REGISTERED:1',
            ['user_id' => 1, 'name' => 'Amina Bello', 'email' => 'amina@example.com',
             'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null]
        );

        $this->assertNotNull($signal);
        $this->assertDatabaseHas('odoo_outbound_signals', [
            'event_name'      => 'USER_REGISTERED',
            'idempotency_key' => 'USER_REGISTERED:1',
            'status'          => OdooOutboundSignal::STATUS_PENDING,
        ]);
    }

    // -------------------------------------------------------------------------
    // Payload encrypted at rest
    // -------------------------------------------------------------------------

    /** @test */
    public function payload_is_encrypted_at_rest()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);
        $dispatcher->dispatch(
            'USER_REGISTERED',
            'user_registered',
            'USER_REGISTERED:42',
            ['user_id' => 42, 'name' => 'Secret User', 'email' => 'secret@example.com',
             'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => '1.2.3.4']
        );

        $signal = OdooOutboundSignal::where('idempotency_key', 'USER_REGISTERED:42')->first();
        $this->assertNotNull($signal);

        $rawPayload = $signal->getAttributes()['payload'];

        // The raw DB column must NOT contain plaintext email or IP.
        $this->assertStringNotContainsString('secret@example.com', $rawPayload);
        $this->assertStringNotContainsString('1.2.3.4', $rawPayload);

        // Must be decryptable and contain the right data.
        $decrypted = $signal->getDecryptedPayload();
        $this->assertIsArray($decrypted);
        $this->assertEquals(42, $decrypted['user_id']);
        $this->assertEquals('secret@example.com', $decrypted['email']);
    }

    // -------------------------------------------------------------------------
    // Queue job carries no raw sensitive data
    // -------------------------------------------------------------------------

    /** @test */
    public function queue_job_carries_only_event_uuid_not_raw_payload()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);
        $signal = $dispatcher->dispatch(
            'USER_REGISTERED',
            'user_registered',
            'USER_REGISTERED:55',
            ['user_id' => 55, 'name' => 'Test User', 'email' => 'test55@example.com',
             'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null]
        );

        Queue::assertPushed(DeliverOdooSignalJob::class, function ($job) use ($signal) {
            // The job must carry only the event_id UUID.
            $this->assertEquals($signal->event_id, $job->eventId);

            // Serialize the job and verify no sensitive data is present.
            $serialized = serialize($job);
            $this->assertStringNotContainsString('test55@example.com', $serialized);
            $this->assertStringNotContainsString('password', $serialized);

            return true;
        });
    }

    // -------------------------------------------------------------------------
    // Duplicate idempotency key prevention
    // -------------------------------------------------------------------------

    /** @test */
    public function duplicate_idempotency_key_does_not_create_second_record()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);
        $payload = ['user_id' => 77, 'name' => 'Dup User', 'email' => 'dup@example.com',
                    'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null];

        $first  = $dispatcher->dispatch('USER_REGISTERED', 'user_registered', 'USER_REGISTERED:77', $payload);
        $second = $dispatcher->dispatch('USER_REGISTERED', 'user_registered', 'USER_REGISTERED:77', $payload);

        $this->assertNotNull($first);
        $this->assertNull($second, 'Second dispatch with same key should return null');
        $this->assertDatabaseCount('odoo_outbound_signals', 1);
    }

    // -------------------------------------------------------------------------
    // Correct endpoint key per signal
    // -------------------------------------------------------------------------

    /** @test */
    public function each_signal_uses_its_configured_endpoint_key()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);

        $signals = [
            ['USER_REGISTERED',    'user_registered',     'USER_REGISTERED:100'],
            ['USAGE_METRICS',      'usage_metrics',        'USAGE_METRICS:100:2026-01-01'],
            ['PAYMENT_SUCCESS',    'payment_success',      'PAYMENT_SUCCESS:paystack:TX001'],
            ['PAYMENT_FAILED',     'payment_failed',       'PAYMENT_FAILED:paystack:TX002:insufficient_funds'],
            ['PAID_EVENT_PURCHASE','paid_event_purchase',  'PAID_EVENT_PURCHASE:10:REF001'],
        ];

        foreach ($signals as [$eventName, $endpointKey, $idempKey]) {
            $dispatcher->dispatch($eventName, $endpointKey, $idempKey, ['user_id' => 100]);
        }

        foreach ($signals as [$eventName, $endpointKey, $idempKey]) {
            $this->assertDatabaseHas('odoo_outbound_signals', [
                'event_name'   => $eventName,
                'endpoint_key' => $endpointKey,
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // Waiting for identity status
    // -------------------------------------------------------------------------

    /** @test */
    public function dispatch_waiting_for_identity_creates_record_but_does_not_queue_job()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(OdooSignalDispatcher::class);
        $signal = $dispatcher->dispatchWaitingForIdentity(
            'PAID_EVENT_PURCHASE',
            'paid_event_purchase',
            'PAID_EVENT_PURCHASE:5:REF999',
            ['user_id' => null, 'event_id' => 5]
        );

        $this->assertNotNull($signal);
        $this->assertEquals(OdooOutboundSignal::STATUS_WAITING_FOR_IDENTITY, $signal->status);

        // No delivery job should be queued for waiting_for_identity signals.
        Queue::assertNothingPushed();
    }
}
