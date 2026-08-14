<?php

namespace App\Services\Payment;

use App\Contracts\PaymentCheckoutProvider;

/**
 * PaymentProviderResolver
 *
 * Resolves the correct PaymentCheckoutProvider implementation for a given
 * provider name. Provider name is validated server-side from the checkout
 * request — never trusted from price/currency data.
 */
class PaymentProviderResolver
{
    /** @var array<string, PaymentCheckoutProvider> */
    private array $providers;

    public function __construct(
        PaystackCheckoutService $paystackService,
        StripeCheckoutService   $stripeService
    ) {
        $this->providers = [
            'paystack' => $paystackService,
            'stripe'   => $stripeService,
        ];
    }

    /**
     * Resolve a provider by canonical name ('paystack' or 'stripe').
     *
     * @param  string $provider
     * @return PaymentCheckoutProvider
     * @throws \InvalidArgumentException for unknown providers
     */
    public function resolve(string $provider): PaymentCheckoutProvider
    {
        $key = strtolower(trim($provider));

        if (!isset($this->providers[$key])) {
            throw new \InvalidArgumentException("Unsupported payment provider: [{$key}]");
        }

        return $this->providers[$key];
    }

    /**
     * Get list of supported provider names.
     *
     * @return string[]
     */
    public function supportedProviders(): array
    {
        return array_keys($this->providers);
    }
}
