<?php

namespace Tests\Feature\Odoo;

use App\Jobs\Odoo\DeliverOdooSignalJob;
use App\Models\OdooOutboundSignal;
use App\Services\Odoo\OdooSignalClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * OdooSignalDeliveryTest
 *
 * Tests the HTTP delivery behaviour of DeliverOdooSignalJob:
 *  - 2xx marks delivered
 *  - Retryable status preserves event_id and idempotency key
 *  - 400/422 marks blocked without infinite retry
 *  - 401/403 logs critical message
 *  - Concurrent claims cannot deliver the same signal twice
 *  - Logs contain no raw payload, token, or secret
 *  - HTTPS enforced in production
 *  - HMAC signature matches raw body
 */
class OdooSignalDeliveryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'odoo.enabled'           => true,
            'odoo.base_url'          => 'https://odoo-test.example.com',
            'odoo.endpoints.user_registered' => '/api/konn3ct/user-registered',
            'odoo.api_token'         => 'test-api-token',
            'odoo.signing_secret'    => '',  // HMAC disabled by default in tests
            'odoo.queue_connection'  => 'sync',
            'odoo.queue_name'        => 'odoo',
        ]);
    }

    /**
     * Create a test signal record directly (bypassing dispatcher).
     */
    private function makeSignal(array $overrides = []): OdooOutboundSignal
    {
        $signal = new OdooOutboundSignal(array_merge([
            'event_id'        => (string) Str::uuid(),
            'event_name'      => 'USER_REGISTERED',
            'schema_version'  => '1.0',
            'idempotency_key' => 'USER_REGISTERED:' . rand(100, 999),
            'endpoint_key'    => 'user_registered',
            'status'          => OdooOutboundSignal::STATUS_QUEUED,
            'attempts'        => 0,
            'queued_at'       => now(),
        ], $overrides));

        // Set encrypted payload via the model setter
        $signal->payload = ['user_id' => 1, 'name' => 'Test', 'email' => 'test@example.com',
                            'country_code' => null, 'referral_code' => null, 'lead_source' => 'web', 'ip' => null];
        $signal->save();

        return $signal;
    }

    // -------------------------------------------------------------------------
    // 2xx response marks signal delivered
    // -------------------------------------------------------------------------

    /** @test */
    public function http_200_marks_signal_as_delivered()
    {
        Http::fake(['*' => Http::response('{"status":"ok"}', 200)]);

        $signal = $this->makeSignal();
        $job = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        $signal->refresh();
        $this->assertEquals(OdooOutboundSignal::STATUS_DELIVERED, $signal->status);
        $this->assertNotNull($signal->delivered_at);
    }

    // -------------------------------------------------------------------------
    // Retryable response preserves event_id and idempotency key
    // -------------------------------------------------------------------------

    /** @test */
    public function http_500_retries_and_preserves_event_id()
    {
        Http::fake(['*' => Http::response('error', 500)]);

        $signal    = $this->makeSignal();
        $eventId   = $signal->event_id;
        $idempKey  = $signal->idempotency_key;

        $job = new DeliverOdooSignalJob($eventId);

        try {
            $job->handle(app(OdooSignalClient::class));
            $this->fail('Expected RuntimeException for retryable failure');
        } catch (\RuntimeException $e) {
            // Expected — retryable responses throw to trigger queue retry
            $this->assertStringContainsString('retryable', strtolower($e->getMessage()));
        }

        $signal->refresh();
        // event_id and idempotency_key must not change across retries
        $this->assertEquals($eventId, $signal->event_id);
        $this->assertEquals($idempKey, $signal->idempotency_key);
        $this->assertEquals(500, $signal->last_http_status);
    }

    // -------------------------------------------------------------------------
    // 400/422 marks blocked without infinite retry
    // -------------------------------------------------------------------------

    /** @test */
    public function http_400_marks_signal_as_blocked_not_retried()
    {
        Http::fake(['*' => Http::response('Bad Request', 400)]);

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        $signal->refresh();
        $this->assertEquals(OdooOutboundSignal::STATUS_BLOCKED, $signal->status);
        $this->assertNotNull($signal->failed_at);
    }

    /** @test */
    public function http_422_marks_signal_as_blocked_not_retried()
    {
        Http::fake(['*' => Http::response('Unprocessable', 422)]);

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        $signal->refresh();
        $this->assertEquals(OdooOutboundSignal::STATUS_BLOCKED, $signal->status);
    }

    // -------------------------------------------------------------------------
    // 401/403 logs critical message
    // -------------------------------------------------------------------------

    /** @test */
    public function http_401_logs_critical_authentication_failure()
    {
        Http::fake(['*' => Http::response('Unauthorized', 401)]);

        $logSpy = \Illuminate\Support\Facades\Log::spy();

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        try {
            $job->handle(app(OdooSignalClient::class));
        } catch (\RuntimeException $e) {
            // Retryable — 401 is allowed to retry
        }

        $logSpy->shouldHaveReceived('critical')->once();
    }

    // -------------------------------------------------------------------------
    // Already delivered signal is skipped
    // -------------------------------------------------------------------------

    /** @test */
    public function already_delivered_signal_is_skipped_without_http_call()
    {
        Http::fake();

        $signal = $this->makeSignal(['status' => OdooOutboundSignal::STATUS_DELIVERED]);
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        Http::assertNothingSent();
    }

    // -------------------------------------------------------------------------
    // Correct headers are sent
    // -------------------------------------------------------------------------

    /** @test */
    public function correct_authentication_and_metadata_headers_are_sent()
    {
        Http::fake(['*' => Http::response('{}', 200)]);

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        Http::assertSent(function (Request $request) use ($signal) {
            return $request->hasHeader('Authorization', 'Bearer test-api-token')
                && $request->hasHeader('X-Konn3ct-Event-Id', $signal->event_id)
                && $request->hasHeader('X-Konn3ct-Event-Name', 'USER_REGISTERED')
                && $request->hasHeader('Idempotency-Key', $signal->idempotency_key)
                && $request->hasHeader('X-Konn3ct-Schema-Version', '1.0');
        });
    }

    // -------------------------------------------------------------------------
    // HMAC signature is included when signing_secret is set
    // -------------------------------------------------------------------------

    /** @test */
    public function hmac_signature_matches_raw_request_body_when_secret_configured()
    {
        config(['odoo.signing_secret' => 'super-secret-signing-key-for-tests']);
        Http::fake(['*' => Http::response('{}', 200)]);

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        Http::assertSent(function (Request $request) {
            $body    = $request->body();
            $expected = 'sha256=' . hash_hmac('sha256', $body, 'super-secret-signing-key-for-tests');
            return $request->hasHeader('X-Konn3ct-Signature', $expected);
        });
    }

    // -------------------------------------------------------------------------
    // Logs contain no raw payload, token, or sensitive data
    // -------------------------------------------------------------------------

    /** @test */
    public function delivery_logs_contain_no_email_ip_token_or_payload()
    {
        Http::fake(['*' => Http::response('{}', 200)]);
        $logMessages = [];
        Log::listen(function ($event) use (&$logMessages) {
            $logMessages[] = json_encode($event->context ?? []) . $event->message;
        });

        $signal = $this->makeSignal();
        $job    = new DeliverOdooSignalJob($signal->event_id);
        $job->handle(app(OdooSignalClient::class));

        $allLogs = implode(' ', $logMessages);
        $this->assertStringNotContainsString('test@example.com', $allLogs);
        $this->assertStringNotContainsString('test-api-token', $allLogs);
        $this->assertStringNotContainsString('Bearer', $allLogs);
    }

    // -------------------------------------------------------------------------
    // HTTPS enforcement
    // -------------------------------------------------------------------------

    /** @test */
    public function production_config_with_http_url_throws_before_sending()
    {
        config([
            'app.env'        => 'production',
            'odoo.base_url'  => 'http://insecure.example.com',
        ]);

        $signal = $this->makeSignal();
        $client = app(OdooSignalClient::class);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/HTTPS/');

        $client->send(
            $signal->event_id,
            $signal->event_name,
            $signal->endpoint_key,
            $signal->idempotency_key,
            ['user_id' => 1]
        );
    }

    // -------------------------------------------------------------------------
    // Concurrent claim protection
    // -------------------------------------------------------------------------

    /** @test */
    public function concurrent_claim_prevents_double_delivery()
    {
        Http::fake(['*' => Http::response('{}', 200)]);

        $signal = $this->makeSignal(['status' => OdooOutboundSignal::STATUS_DELIVERING]);

        // If the signal is already in 'delivering' state, claim() should fail.
        $claimed = $signal->claim();
        $this->assertFalse($claimed, 'Signal already in delivering state should not be claimed again');
    }
}
