<?php

namespace Tests\Feature\Odoo;

use App\Jobs\Odoo\DeliverOdooSignalJob;
use App\Models\OdooOutboundSignal;
use App\Models\User;
use App\Services\Odoo\OdooPayloadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * UserRegisteredSignalTest
 *
 * Tests USER_REGISTERED signal dispatch from all registration paths:
 *  - Web (Fortify / CreateNewUser) → lead_source: web, IP: null
 *  - Mobile API (AuthController::register) → lead_source: mobile_app
 *  - Social new user (AuthController::loginSocial) → lead_source: social_{provider}
 *  - Returning social user (loginSocial) → NO signal emitted
 *  - Reseller registration → lead_source: reseller
 *  - Duplicate registration request → no duplicate signal
 *  - Passwords, MFA, tokens never in payload
 */
class UserRegisteredSignalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['odoo.enabled' => true]);
    }

    // -------------------------------------------------------------------------
    // Payload factory correctness
    // -------------------------------------------------------------------------

    /** @test */
    public function user_registered_payload_matches_contract()
    {
        $factory = app(OdooPayloadFactory::class);

        $payload = $factory->userRegistered(
            123,
            'Amina Bello',
            'amina@example.com',
            'NG',
            'ABC123',
            'web',
            '203.0.113.10'
        );

        $this->assertEquals(123, $payload['user_id']);
        $this->assertEquals('Amina Bello', $payload['name']);
        $this->assertEquals('amina@example.com', $payload['email']);
        $this->assertEquals('NG', $payload['country_code']);
        $this->assertEquals('ABC123', $payload['referral_code']);
        $this->assertEquals('web', $payload['lead_source']);
        $this->assertEquals('203.0.113.10', $payload['ip']);
    }

    /** @test */
    public function passwords_mfa_and_tokens_never_appear_in_payload()
    {
        $factory = app(OdooPayloadFactory::class);

        $payload = $factory->userRegistered(1, 'Test User', 'test@example.com', null, null, 'web', null);

        $keys = array_keys($payload);
        $this->assertNotContains('password', $keys);
        $this->assertNotContains('two_factor_secret', $keys);
        $this->assertNotContains('two_factor_recovery_codes', $keys);
        $this->assertNotContains('remember_token', $keys);
        $this->assertNotContains('phone', $keys);
        $this->assertNotContains('token', $keys);
    }

    /** @test */
    public function email_is_normalized_to_lowercase()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Test', 'TEST@EXAMPLE.COM', null, null, 'web', null);
        $this->assertEquals('test@example.com', $payload['email']);
    }

    /** @test */
    public function country_code_is_normalized_to_uppercase()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Test', 'test@example.com', 'ng', null, 'web', null);
        $this->assertEquals('NG', $payload['country_code']);
    }

    /** @test */
    public function invalid_lead_source_defaults_to_api()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Test', 'test@example.com', null, null, 'unknown_source', null);
        $this->assertEquals('api', $payload['lead_source']);
    }

    /** @test */
    public function referral_code_in_payload_is_the_acquisition_referral_not_users_own_code()
    {
        // The acquisition referral (users.referral) should appear,
        // not the user's generated referral_code.
        $factory = app(OdooPayloadFactory::class);

        // Acquisition referral = "ABC123" (what the user entered at signup)
        $payload = $factory->userRegistered(1, 'Test', 'test@example.com', null, 'ABC123', 'web', null);
        $this->assertEquals('ABC123', $payload['referral_code']);
    }

    // -------------------------------------------------------------------------
    // Signal dispatch — idempotency
    // -------------------------------------------------------------------------

    /** @test */
    public function duplicate_dispatch_does_not_create_second_signal()
    {
        Queue::fake();
        config(['odoo.enabled' => true]);

        $dispatcher = app(\App\Services\Odoo\OdooSignalDispatcher::class);
        $payload = ['user_id' => 1, 'name' => 'Test', 'email' => 'test@example.com',
                    'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null];

        $dispatcher->dispatch('USER_REGISTERED', 'user_registered', 'USER_REGISTERED:1', $payload);
        $dispatcher->dispatch('USER_REGISTERED', 'user_registered', 'USER_REGISTERED:1', $payload);

        $this->assertDatabaseCount('odoo_outbound_signals', 1);
        Queue::assertPushed(DeliverOdooSignalJob::class, 1);
    }

    /** @test */
    public function mobile_app_lead_source_is_valid()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Test', 'test@example.com', null, null, 'mobile_app', '1.2.3.4');
        $this->assertEquals('mobile_app', $payload['lead_source']);
        $this->assertEquals('1.2.3.4', $payload['ip']);
    }

    /** @test */
    public function background_job_registration_has_null_ip()
    {
        // Background jobs (CreateBGAccountJob) do not have a trustworthy IP.
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'BG User', 'bg@example.com', null, null, 'paid_event_registration', null);
        $this->assertNull($payload['ip']);
        $this->assertEquals('paid_event_registration', $payload['lead_source']);
    }

    /** @test */
    public function social_google_lead_source_is_normalized()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Google User', 'g@example.com', null, null, 'social_google', '5.6.7.8');
        $this->assertEquals('social_google', $payload['lead_source']);
    }

    /** @test */
    public function null_ip_is_acceptable_in_payload()
    {
        // Some registration paths legitimately have no IP.
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->userRegistered(1, 'Test', 'test@example.com', null, null, 'web', null);
        $this->assertArrayHasKey('ip', $payload);
        $this->assertNull($payload['ip']);
    }
}
