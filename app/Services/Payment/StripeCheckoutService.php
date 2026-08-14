<?php

namespace App\Services\Payment;

use App\Contracts\PaymentCheckoutProvider;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

/**
 * StripeCheckoutService
 *
 * Implements checkout initialization via the Stripe Checkout Session API.
 * Price and currency are always supplied by the caller (locked server-side
 * from prereg.amount). The client NEVER supplies these values.
 *
 * Ref: https://stripe.com/docs/api/checkout/sessions/create
 */
class StripeCheckoutService implements PaymentCheckoutProvider
{
    public function providerName(): string
    {
        return 'stripe';
    }

    /**
     * @inheritDoc
     */
    public function initialize(
        int    $userId,
        string $userEmail,
        int    $eventId,
        int    $amountMinor,
        string $currency,
        string $localReference
    ): array {
        $secretKey = config('stripe.secret');

        if (empty($secretKey)) {
            throw new \RuntimeException('Stripe secret key is not configured.');
        }

        $successUrl = config('stripe.checkout_success_url');
        $cancelUrl  = config('stripe.checkout_cancel_url');

        if (empty($successUrl) || empty($cancelUrl)) {
            throw new \RuntimeException('Stripe checkout redirect URLs are not configured.');
        }

        Stripe::setApiKey($secretKey);

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'mode'                 => 'payment',
                'customer_email'       => $userEmail,
                'client_reference_id'  => $localReference,
                'success_url'          => $successUrl . '?ref=' . $localReference,
                'cancel_url'           => $cancelUrl,
                'line_items'           => [
                    [
                        'price_data' => [
                            'currency'     => strtolower($currency),
                            'unit_amount'  => $amountMinor,
                            'product_data' => [
                                'name' => 'Event Ticket',
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'metadata' => [
                    'event_id'        => (string) $eventId,
                    'user_id'         => (string) $userId,
                    'local_reference' => $localReference,
                    'source'          => 'konn3ct_event_checkout',
                ],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('StripeCheckoutService: initialization failed', [
                'code' => $e->getStripeCode(),
                // Never log the full exception message (may contain PII)
            ]);
            throw new \RuntimeException('Stripe checkout initialization failed.');
        }

        return [
            'provider'        => $this->providerName(),
            'payment_url'     => $session->url,
            'access_code'     => $session->id, // Stripe session ID acts as access code
            'local_reference' => $localReference,
        ];
    }
}
