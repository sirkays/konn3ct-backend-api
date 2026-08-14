<?php

namespace App\Services\Payment;

use App\Contracts\PaymentCheckoutProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PaystackCheckoutService
 *
 * Implements checkout initialization via the Paystack Transaction API.
 * Price and currency are always supplied by the caller (locked server-side
 * from prereg.amount). The client NEVER supplies these values.
 *
 * Ref: https://paystack.com/docs/api/transaction/#initialize
 */
class PaystackCheckoutService implements PaymentCheckoutProvider
{
    public function providerName(): string
    {
        return 'paystack';
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
        $secretKey = config('paystack.secretKey');

        if (empty($secretKey)) {
            throw new \RuntimeException('Paystack secret key is not configured.');
        }

        $response = Http::withToken($secretKey)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email'     => $userEmail,
                'amount'    => $amountMinor,       // minor units (kobo)
                'currency'  => $currency,
                'reference' => $localReference,
                'metadata'  => [
                    'event_id'   => $eventId,
                    'user_id'    => $userId,
                    'source'     => 'konn3ct_event_checkout',
                ],
            ]);

        if (!$response->successful()) {
            $errMessage = $response->json('message') ?? ('HTTP ' . $response->status());
            Log::error('PaystackCheckoutService: initialization failed', [
                'status'  => $response->status(),
                'message' => $errMessage,
            ]);
            throw new \RuntimeException('Paystack checkout initialization failed: ' . $errMessage);
        }

        $body = $response->json();

        if (!($body['status'] ?? false)) {
            $msg = $body['message'] ?? 'Paystack returned a failure status.';
            throw new \RuntimeException('Paystack returned a failure status: ' . $msg);
        }

        return [
            'provider'        => $this->providerName(),
            'payment_url'     => $body['data']['authorization_url'] ?? '',
            'access_code'     => $body['data']['access_code']       ?? null,
            'local_reference' => $localReference,
        ];
    }
}
