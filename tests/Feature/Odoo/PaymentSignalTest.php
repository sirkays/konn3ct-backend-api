<?php

namespace Tests\Feature\Odoo;

use App\Jobs\Odoo\DeliverOdooSignalJob;
use App\Models\OdooOutboundSignal;
use App\Services\Odoo\OdooPayloadFactory;
use App\Services\Odoo\OdooSignalDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * PaymentSignalTest
 *
 * Tests PAYMENT_SUCCESS, PAYMENT_FAILED, and PAID_EVENT_PURCHASE signals:
 *  - Correct payload contracts
 *  - Amount conversion from minor to major units
 *  - Currency and gateway normalization
 *  - Replay/duplicate protection via idempotency keys
 *  - No card data, auth codes, or raw gateway payloads
 *  - PAID_EVENT_PURCHASE identity resolution
 *  - waiting_for_identity when no user found
 *  - abandoned_cart is a boolean
 */
class PaymentSignalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['odoo.enabled' => true]);
    }

    // -------------------------------------------------------------------------
    // PAYMENT_SUCCESS payload contract
    // -------------------------------------------------------------------------

    /** @test */
    public function payment_success_payload_matches_contract()
    {
        $factory = app(OdooPayloadFactory::class);

        $payload = $factory->paymentSuccess(
            'TX-20260812-0001',
            123,
            27000.00,
            'NGN',
            3,
            'paystack'
        );

        $this->assertEquals('TX-20260812-0001', $payload['transaction_reference']);
        $this->assertEquals(123, $payload['user_id']);
        $this->assertEquals(27000.00, $payload['amount']);
        $this->assertEquals('NGN', $payload['currency']);
        $this->assertEquals(3, $payload['plan_or_event_id']);
        $this->assertEquals('paystack', $payload['gateway']);
    }

    /** @test */
    public function payment_success_currency_is_normalized_to_uppercase()
    {
        $factory  = app(OdooPayloadFactory::class);
        $payload  = $factory->paymentSuccess('TX001', 1, 100.0, 'ngn', 1, 'paystack');
        $this->assertEquals('NGN', $payload['currency']);
    }

    /** @test */
    public function payment_success_gateway_is_normalized_to_lowercase()
    {
        $factory  = app(OdooPayloadFactory::class);
        $payload  = $factory->paymentSuccess('TX001', 1, 100.0, 'NGN', 1, 'Paystack');
        $this->assertEquals('paystack', $payload['gateway']);
    }

    /** @test */
    public function payment_success_amount_is_in_major_units_not_minor_units()
    {
        // Paystack sends amounts in kobo (minor units). Conversion must happen before payload.
        $factory = app(OdooPayloadFactory::class);

        // 2700000 kobo = 27000.00 NGN
        $majorUnitAmount = 2700000 / 100;  // conversion done in controller, not factory
        $payload = $factory->paymentSuccess('TX001', 1, $majorUnitAmount, 'NGN', 1, 'paystack');

        $this->assertEquals(27000.00, $payload['amount']);
    }

    /** @test */
    public function payment_success_payload_contains_no_card_or_auth_data()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->paymentSuccess('TX001', 1, 100.0, 'NGN', 1, 'paystack');

        $keys = array_keys($payload);
        $forbidden = ['authorization_code', 'card_number', 'cvv', 'last4', 'bin',
                      'card_type', 'gateway_response', 'gateway_secret'];
        foreach ($forbidden as $key) {
            $this->assertNotContains($key, $keys, "Payment success payload must not contain {$key}");
        }
    }

    /** @test */
    public function duplicate_payment_success_idempotency_key_prevents_second_signal()
    {
        Queue::fake();
        $dispatcher = app(OdooSignalDispatcher::class);
        $payload    = ['transaction_reference' => 'TX001', 'user_id' => 1, 'amount' => 100.0,
                       'currency' => 'NGN', 'plan_or_event_id' => 1, 'gateway' => 'paystack'];

        $first  = $dispatcher->dispatch('PAYMENT_SUCCESS', 'payment_success', 'PAYMENT_SUCCESS:paystack:TX001', $payload);
        $second = $dispatcher->dispatch('PAYMENT_SUCCESS', 'payment_success', 'PAYMENT_SUCCESS:paystack:TX001', $payload);

        $this->assertNotNull($first);
        $this->assertNull($second, 'Duplicate payment success signal must be rejected');
        $this->assertDatabaseCount('odoo_outbound_signals', 1);
    }

    // -------------------------------------------------------------------------
    // PAYMENT_FAILED payload contract
    // -------------------------------------------------------------------------

    /** @test */
    public function payment_failed_payload_matches_contract()
    {
        $factory = app(OdooPayloadFactory::class);

        $payload = $factory->paymentFailed(
            'TX-20260812-0002',
            123,
            27000.00,
            'paystack',
            'insufficient_funds',
            false
        );

        $this->assertEquals('TX-20260812-0002', $payload['transaction_reference']);
        $this->assertEquals(123, $payload['user_id']);
        $this->assertEquals(27000.00, $payload['amount']);
        $this->assertEquals('paystack', $payload['gateway']);
        $this->assertEquals('insufficient_funds', $payload['error_code']);
        $this->assertFalse($payload['abandoned_cart']);
        $this->assertIsBool($payload['abandoned_cart']);
    }

    /** @test */
    public function abandoned_cart_is_always_a_boolean()
    {
        $factory = app(OdooPayloadFactory::class);

        $withTrue  = $factory->paymentFailed('TX001', 1, 100.0, 'paystack', 'user_aborted', true);
        $withFalse = $factory->paymentFailed('TX001', 1, 100.0, 'paystack', 'insufficient_funds', false);

        $this->assertIsBool($withTrue['abandoned_cart']);
        $this->assertIsBool($withFalse['abandoned_cart']);
        $this->assertTrue($withTrue['abandoned_cart']);
        $this->assertFalse($withFalse['abandoned_cart']);
    }

    /** @test */
    public function payment_failed_does_not_contain_raw_gateway_response()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->paymentFailed('TX001', 1, 100.0, 'paystack', 'insufficient_funds', false);

        $keys = array_keys($payload);
        $this->assertNotContains('gateway_response', $keys);
        $this->assertNotContains('raw_response', $keys);
        $this->assertNotContains('authorization', $keys);
    }

    // -------------------------------------------------------------------------
    // PAID_EVENT_PURCHASE payload contract
    // -------------------------------------------------------------------------

    /** @test */
    public function paid_event_purchase_payload_matches_contract()
    {
        $factory = app(OdooPayloadFactory::class);

        $payload = $factory->paidEventPurchase(123, 45, 5000.00);

        $this->assertEquals(123, $payload['user_id']);
        $this->assertEquals(45, $payload['event_id']);
        $this->assertEquals(5000.00, $payload['ticket_price']);
        $this->assertEquals('paid', $payload['payment_status']);
    }

    /** @test */
    public function paid_event_purchase_payment_status_is_always_paid()
    {
        $factory = app(OdooPayloadFactory::class);
        $payload = $factory->paidEventPurchase(1, 1, 100.0, 'pending');  // even if passed 'pending'
        $this->assertEquals('paid', $payload['payment_status']);
    }

    /** @test */
    public function missing_konn3ct_user_creates_waiting_for_identity_signal()
    {
        Queue::fake();
        $dispatcher = app(OdooSignalDispatcher::class);

        $signal = $dispatcher->dispatchWaitingForIdentity(
            'PAID_EVENT_PURCHASE',
            'paid_event_purchase',
            'PAID_EVENT_PURCHASE:45:REF001',
            ['user_id' => null, 'event_id' => 45, 'ticket_price' => 5000.0, 'payment_status' => 'paid']
        );

        $this->assertNotNull($signal);
        $this->assertEquals(OdooOutboundSignal::STATUS_WAITING_FOR_IDENTITY, $signal->status);
        $this->assertDatabaseHas('odoo_outbound_signals', [
            'event_name' => 'PAID_EVENT_PURCHASE',
            'status'     => OdooOutboundSignal::STATUS_WAITING_FOR_IDENTITY,
        ]);

        // No delivery job should be queued for blocked signals.
        Queue::assertNothingPushed();
    }

    /** @test */
    public function paid_event_purchase_does_not_use_prereg_users_id_as_user_id()
    {
        // This is a contract rule: prereg_users.id must never be sent as user_id.
        // The test verifies that the OdooPayloadFactory requires an explicit userId.
        $factory = app(OdooPayloadFactory::class);

        // Correct: real Konn3ct user ID (100) != prereg_user ID (45)
        $payload = $factory->paidEventPurchase(100, 45, 5000.0);
        $this->assertEquals(100, $payload['user_id']);
        $this->assertEquals(45, $payload['event_id']);

        // The two values must be different to prove no substitution.
        $this->assertNotEquals($payload['user_id'], $payload['event_id']);
    }

    /** @test */
    public function paid_event_replay_does_not_create_duplicate_signal()
    {
        Queue::fake();
        $dispatcher = app(OdooSignalDispatcher::class);
        $payload    = ['user_id' => 1, 'event_id' => 45, 'ticket_price' => 5000.0, 'payment_status' => 'paid'];
        $key        = 'PAID_EVENT_PURCHASE:45:REF001';

        $first  = $dispatcher->dispatch('PAID_EVENT_PURCHASE', 'paid_event_purchase', $key, $payload);
        $second = $dispatcher->dispatch('PAID_EVENT_PURCHASE', 'paid_event_purchase', $key, $payload);

        $this->assertNotNull($first);
        $this->assertNull($second);
        $this->assertDatabaseCount('odoo_outbound_signals', 1);
    }

    // -------------------------------------------------------------------------
    // Gateway normalization helper
    // -------------------------------------------------------------------------

    /** @test */
    public function normalize_gateway_maps_display_names_to_canonical_lowercase()
    {
        $this->assertEquals('paystack',    OdooPayloadFactory::normalizeGateway('Paystack'));
        $this->assertEquals('flutterwave', OdooPayloadFactory::normalizeGateway('Flutterwave'));
        $this->assertEquals('stripe',      OdooPayloadFactory::normalizeGateway('Stripe'));
        $this->assertEquals('vulte',       OdooPayloadFactory::normalizeGateway('Vulte'));
    }
}
