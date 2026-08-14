<?php

namespace App\Contracts;

/**
 * PaymentCheckoutProvider
 *
 * Contract for Paystack and Stripe checkout initialization.
 * Both providers resolve price server-side from the prereg model.
 * Neither accepts amount, currency, or eventId from the frontend.
 */
interface PaymentCheckoutProvider
{
    /**
     * Initialize a payment checkout session/link for a given event.
     *
     * @param  int    $userId           Authenticated user's ID
     * @param  string $userEmail        Authenticated user's email
     * @param  int    $eventId          prereg.id (server-resolved, never client-supplied)
     * @param  int    $amountMinor      Price in minor units (locked at checkout time)
     * @param  string $currency         ISO 4217 currency code (locked at checkout time)
     * @param  string $localReference   Our internal unique reference
     * @return array{
     *     provider: string,
     *     payment_url: string,
     *     access_code: string|null,
     *     local_reference: string
     * }
     * @throws \RuntimeException on provider error
     */
    public function initialize(
        int    $userId,
        string $userEmail,
        int    $eventId,
        int    $amountMinor,
        string $currency,
        string $localReference
    ): array;

    /**
     * The canonical provider name ('paystack' or 'stripe').
     */
    public function providerName(): string;
}
