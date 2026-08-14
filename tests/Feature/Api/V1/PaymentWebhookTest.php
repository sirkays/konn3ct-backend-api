<?php

namespace Tests\Feature\Api\V1;

use App\Models\EventTransaction;
use App\Models\FulfillmentLog;
use App\Models\PreRegModel;
use App\Models\PreRegUserModel;
use App\Models\ProcessedWebhook;
use App\Models\User;
use App\Events\PaymentSucceeded;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

/**
 * PaymentWebhookTest
 *
 * Tests the new unified payment webhook endpoint for Paystack and Stripe.
 * Covers HMAC verification, idempotency, PENDING→PAID transition, entitlement provisioning.
 */
class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $paystackSecret = 'test-paystack-secret-for-webhook';
    private User $user;
    private PreRegModel $event;
    private EventTransaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('paystack.secretKey', $this->paystackSecret);
        Config::set('stripe.webhook_secret', 'whsec_test_fake_stripe_secret');

        $this->user = User::factory()->create(['email' => 'buyer@test.com']);

        $this->event = PreRegModel::create([
            'user_id'   => $this->user->id,
            'room_id'   => 1,
            'title'     => 'Test Paid Webinar',
            'reference' => 'WEB-2026-PAY',
            'host_name' => 'Test Host',
            'date'      => '2026-09-01',
            'time'      => '10:00',
            'timezone'  => 'UTC',
            'about'     => 'Test event',
            'free'      => 0,
            'amount'    => '500000',
            'currency'  => 'NGN',
        ]);

        // Create a PENDING prereg_users record
        PreRegUserModel::create([
            'prereg_id' => $this->event->id,
            'name'      => 'Test Buyer',
            'email'     => 'buyer@test.com',
            'phone'     => '+234800000099',
            'paid'      => 0,
        ]);

        // Create a PENDING transaction
        $this->transaction = new EventTransaction([
            'user_id'         => $this->user->id,
            'event_id'        => $this->event->id,
            'amount_minor'    => 500000,
            'currency'        => 'NGN',
            'provider'        => 'paystack',
            'local_reference' => 'KNT-TEST-REF-0001',
            'status'          => EventTransaction::STATUS_PENDING,
            'pricing_snapshot' => 'placeholder',
        ]);
        $this->transaction->setPricingSnapshot(['amount_minor' => 500000, 'currency' => 'NGN']);
        $this->transaction->save();
    }

    private function makePaystackPayload(string $reference = 'KNT-TEST-REF-0001'): array
    {
        return [
            'event' => 'charge.success',
            'data'  => [
                'status'    => 'success',
                'reference' => $reference,
                'amount'    => 500000,
                'currency'  => 'NGN',
                'customer'  => ['email' => 'buyer@test.com'],
            ],
        ];
    }

    private function signPayload(array $payload): string
    {
        return hash_hmac('sha512', json_encode($payload), $this->paystackSecret);
    }

    /**
     * Paystack: missing signature header returns 400 (unknown provider).
     */
    public function test_missing_signature_header_returns_400()
    {
        $response = $this->postJson('/api/v1/webhooks/payment', $this->makePaystackPayload());

        $response->assertStatus(400);
    }

    /**
     * Paystack: wrong HMAC signature returns 401.
     */
    public function test_wrong_paystack_hmac_returns_401()
    {
        $response = $this->withHeaders([
            'X-Paystack-Signature' => 'totally-wrong-signature-value',
        ])->postJson('/api/v1/webhooks/payment', $this->makePaystackPayload());

        $response->assertStatus(401);
    }

    /**
     * Paystack: valid HMAC + charge.success provisions entitlement.
     */
    public function test_valid_paystack_webhook_provisions_entitlement()
    {
        Event::fake([PaymentSucceeded::class]);

        $payload = $this->makePaystackPayload();
        $sig     = $this->signPayload($payload);

        $response = $this->withHeaders(['X-Paystack-Signature' => $sig])
            ->postJson('/api/v1/webhooks/payment', $payload);

        $response->assertStatus(200);

        // Transaction transitioned to PAID
        $this->assertDatabaseHas('event_transactions', [
            'id'     => $this->transaction->id,
            'status' => 'paid',
        ]);

        // prereg_users.paid=1 set
        $this->assertDatabaseHas('prereg_users', [
            'prereg_id' => $this->event->id,
            'email'     => 'buyer@test.com',
            'paid'      => 1,
        ]);

        // Webhook recorded as processed
        $this->assertDatabaseHas('processed_webhooks', [
            'provider'   => 'paystack',
            'event_type' => 'charge.success',
        ]);

        // PaymentSucceeded event fired
        Event::assertDispatched(PaymentSucceeded::class);
    }

    /**
     * Paystack: duplicate webhook (same reference) is safely ignored.
     */
    public function test_duplicate_paystack_webhook_is_idempotent()
    {
        Event::fake([PaymentSucceeded::class]);

        $payload = $this->makePaystackPayload();
        $sig     = $this->signPayload($payload);
        $headers = ['X-Paystack-Signature' => $sig];

        // First call — should succeed
        $this->withHeaders($headers)->postJson('/api/v1/webhooks/payment', $payload)->assertStatus(200);

        // Second call with same payload — should be idempotent
        $this->withHeaders($headers)->postJson('/api/v1/webhooks/payment', $payload)->assertStatus(200);

        // Only one processed_webhooks record
        $this->assertDatabaseCount('processed_webhooks', 1);

        // Only dispatched once
        Event::assertDispatchedTimes(PaymentSucceeded::class, 1);
    }

    /**
     * Paystack: FAILED→PAID transition is not allowed.
     */
    public function test_failed_to_paid_transition_is_rejected()
    {
        // Set transaction to failed
        $this->transaction->update(['status' => EventTransaction::STATUS_FAILED]);

        $payload = $this->makePaystackPayload();
        $sig     = $this->signPayload($payload);

        $this->withHeaders(['X-Paystack-Signature' => $sig])
            ->postJson('/api/v1/webhooks/payment', $payload)
            ->assertStatus(200); // Returns 200 (ok / already processed)

        // Status remains FAILED — no PAID transition
        $this->assertDatabaseHas('event_transactions', [
            'id'     => $this->transaction->id,
            'status' => 'failed',
        ]);

        // prereg_users.paid remains 0
        $this->assertDatabaseHas('prereg_users', [
            'prereg_id' => $this->event->id,
            'email'     => 'buyer@test.com',
            'paid'      => 0,
        ]);
    }

    /**
     * Paystack: non-success status event is ignored.
     */
    public function test_non_charge_success_event_is_ignored()
    {
        $payload = ['event' => 'charge.failed', 'data' => ['status' => 'failed', 'reference' => 'KNT-TEST-REF-0001']];
        $sig     = hash_hmac('sha512', json_encode($payload), $this->paystackSecret);

        $response = $this->withHeaders(['X-Paystack-Signature' => $sig])
            ->postJson('/api/v1/webhooks/payment', $payload);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('processed_webhooks', ['provider' => 'paystack']);
    }

    /**
     * Fulfillment log is initialized when webhook succeeds.
     */
    public function test_fulfillment_log_is_initialized_on_success()
    {
        Event::fake([PaymentSucceeded::class]);

        $payload = $this->makePaystackPayload();
        $sig     = $this->signPayload($payload);

        $this->withHeaders(['X-Paystack-Signature' => $sig])
            ->postJson('/api/v1/webhooks/payment', $payload);

        $this->assertDatabaseHas('fulfillment_log', [
            'event_transaction_id' => $this->transaction->id,
            'receipt_generated'    => 0,
            'email_sent'           => 0,
            'odoo_notified'        => 0,
        ]);
    }

    /**
     * Webhook with no matching transaction returns 200 (graceful).
     */
    public function test_webhook_with_no_matching_transaction_returns_200()
    {
        $payload = $this->makePaystackPayload('COMPLETELY-UNKNOWN-REF-XYZ');
        $sig     = $this->signPayload($payload);

        $response = $this->withHeaders(['X-Paystack-Signature' => $sig])
            ->postJson('/api/v1/webhooks/payment', $payload);

        $response->assertStatus(200);
    }
}
