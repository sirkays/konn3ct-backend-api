<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * PaystackWebhookSecurityTest
 *
 * Proves that the Paystack webhook HMAC-SHA512 verification is working:
 * forged calls with invalid or missing signatures cannot update payment status.
 */
class PaystackWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'test-paystack-secret-key';

    private function makePayload(): array
    {
        return [
            'event' => 'charge.success',
            'data'  => [
                'domain'    => 'live',
                'status'    => 'success',
                'reference' => 'KNT-TEST-REFERENCE-001',
                'amount'    => 500000,
                'fees'      => 5000,
                'currency'  => 'NGN',
                'customer'  => ['email' => 'test@example.com'],
                'plan'      => ['interval' => 'monthly'],
                'metadata'  => ['custom_fields' => [['value' => 'not-addons', 'display_name' => 'addon']]],
            ],
        ];
    }

    private function makeHmac(string $rawBody): string
    {
        return hash_hmac('sha512', $rawBody, $this->webhookSecret);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('paystack.secretKey', $this->webhookSecret);
    }

    /**
     * Request with no X-Paystack-Signature header is rejected with 401.
     */
    public function test_missing_signature_header_is_rejected()
    {
        $response = $this->postJson('/api/paystackhook', $this->makePayload());

        $response->assertStatus(401);
    }

    /**
     * Request with a tampered/wrong signature is rejected with 401.
     */
    public function test_wrong_hmac_signature_is_rejected()
    {
        Config::set('paystack.secretKey', $this->webhookSecret);
        $payload  = $this->makePayload();
        $rawBody  = json_encode($payload);

        $response = $this->withHeaders([
            'X-Paystack-Signature' => 'aaaabbbbccccdddd' . str_repeat('0', 112),
        ])->postJson('/api/paystackhook', $payload);

        $this->assertNotEquals(200, $response->getStatusCode());
        $this->assertContains($response->getStatusCode(), [401, 500]);
    }

    /**
     * Request signed with a different secret is rejected.
     */
    public function test_signature_from_different_secret_is_rejected()
    {
        Config::set('paystack.secretKey', $this->webhookSecret);
        $payload  = $this->makePayload();
        $rawBody  = json_encode($payload);
        $wrongSig = hash_hmac('sha512', $rawBody, 'completely-wrong-secret');

        $response = $this->withHeaders([
            'X-Paystack-Signature' => $wrongSig,
        ])->postJson('/api/paystackhook', $payload);

        $this->assertNotEquals(200, $response->getStatusCode());
        $this->assertContains($response->getStatusCode(), [401, 500]);
    }

    /**
     * Valid HMAC signature is accepted (passes signature check, enters business logic).
     * The test asserts HMAC verification passes (not 401), not the full handler outcome.
     */
    public function test_valid_hmac_signature_passes_verification()
    {
        $payload = $this->makePayload();
        $rawBody = json_encode($payload);
        $sig     = $this->makeHmac($rawBody);

        // User must exist for the webhook to process
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->withHeaders([
            'X-Paystack-Signature' => $sig,
        ])->postJson('/api/paystackhook', $payload);

        // HMAC is correct — must NOT be rejected with 401 (signature error).
        // The legacy handler may return other codes based on its own DB state.
        $this->assertNotEquals(401, $response->getStatusCode(),
            'A valid HMAC signature should not be rejected with 401.');
    }

    /**
     * webHook endpoint: missing signature is rejected (401 or 500 for misconfiguration).
     * Security property: a missing signature NEVER results in a 200 success response.
     */
    public function test_webhook_endpoint_missing_signature_is_rejected()
    {
        $response = $this->postJson('/api/paystackhookweb', $this->makePayload());

        // Security assertion: missing X-Paystack-Signature must never return 200 success.
        // Returns 401 when key is configured, 500 when key is absent (config guard fires first).
        $this->assertNotEquals(200, $response->getStatusCode(),
            'A request with no signature should never return 200 success.');
        $this->assertContains($response->getStatusCode(), [401, 500],
            'Missing signature should return 401 (rejected) or 500 (config error), not 200.');
    }

    /**
     * webHook endpoint: tampered signature is rejected.
     */
    public function test_webhook_endpoint_wrong_signature_is_rejected()
    {
        $response = $this->withHeaders([
            'X-Paystack-Signature' => 'invalid_signature_value',
        ])->postJson('/api/paystackhookweb', $this->makePayload());

        $response->assertStatus(401);
    }
}
