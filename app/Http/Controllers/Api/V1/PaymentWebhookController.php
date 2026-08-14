<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\PaymentSucceeded;
use App\Http\Controllers\Controller;
use App\Models\EventTransaction;
use App\Models\FulfillmentLog;
use App\Models\PreRegUserModel;
use App\Models\ProcessedWebhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook as StripeWebhook;

/**
 * PaymentWebhookController
 *
 * Handles incoming Paystack and Stripe payment webhooks.
 *
 * SECURITY INVARIANTS:
 *   - Paystack: HMAC-SHA512 verified over the exact raw request body using hash_equals.
 *   - Stripe: Stripe\Webhook::constructEvent() with signing secret.
 *   - Webhook idempotency: processed_webhooks INSERT is inside the same DB::transaction
 *     as the entitlement provisioning. If provisioning rolls back, the webhook record
 *     is not persisted — the event can be safely retried.
 *   - Only PENDING → PAID transition is allowed.
 *   - prereg_users.paid=1 is only set after the transaction transitions to PAID.
 *
 * POST /api/v1/webhooks/payment
 */
class PaymentWebhookController extends Controller
{
    /**
     * Handle an incoming webhook from Paystack or Stripe.
     * Provider is determined from the X-Paystack-Signature or Stripe-Signature header.
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        $rawBody = $request->attributes->get('raw_body', $request->getContent());

        // --- Detect provider and verify signature ---
        if ($request->headers->has('X-Paystack-Signature')) {
            return $this->handlePaystack($request, $rawBody);
        }

        if ($request->headers->has('Stripe-Signature')) {
            return $this->handleStripe($request, $rawBody);
        }

        return response()->json(['message' => 'Unknown provider.'], 400);
    }

    // =========================================================================
    // Paystack
    // =========================================================================

    private function handlePaystack(Request $request, string $rawBody): JsonResponse
    {
        // 1. Verify HMAC-SHA512.
        $secret = config('paystack.secretKey');
        if (empty($secret)) {
            Log::critical('PaymentWebhookController: PAYSTACK_SECRET_KEY not configured');
            return response()->json(['message' => 'Configuration error.'], 500);
        }

        $expectedSig = hash_hmac('sha512', $rawBody, $secret);
        if (!hash_equals($expectedSig, $request->header('X-Paystack-Signature', ''))) {
            Log::warning('PaymentWebhookController: Paystack HMAC mismatch');
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        // 2. Decode payload.
        $payload = json_decode($rawBody, true);
        if (!$payload || ($payload['event'] ?? '') !== 'charge.success') {
            return response()->json(['message' => 'Ignored.'], 200);
        }

        $data       = $payload['data'] ?? [];
        $reference  = $data['reference'] ?? null;
        $status     = $data['status']    ?? null;

        if (!$reference || $status !== 'success') {
            return response()->json(['message' => 'Ignored.'], 200);
        }

        // 3. Build idempotency key.
        $idempotencyKey = ProcessedWebhook::buildIdempotencyKey('paystack', 'charge.success', $reference);

        return $this->processConfirmedPayment(
            provider: 'paystack',
            eventType: 'charge.success',
            providerEventId: $reference,
            idempotencyKey: $idempotencyKey,
            providerReference: $reference
        );
    }

    // =========================================================================
    // Stripe
    // =========================================================================

    private function handleStripe(Request $request, string $rawBody): JsonResponse
    {
        $webhookSecret = config('stripe.webhook_secret');
        if (empty($webhookSecret)) {
            Log::critical('PaymentWebhookController: STRIPE_WEBHOOK_SECRET not configured');
            return response()->json(['message' => 'Configuration error.'], 500);
        }

        try {
            $event = StripeWebhook::constructEvent(
                $rawBody,
                $request->header('Stripe-Signature', ''),
                $webhookSecret
            );
        } catch (SignatureVerificationException $e) {
            Log::warning('PaymentWebhookController: Stripe signature verification failed');
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        // Only process checkout.session.completed.
        if ($event->type !== 'checkout.session.completed') {
            return response()->json(['message' => 'Ignored.'], 200);
        }

        /** @var \Stripe\Checkout\Session $session */
        $session         = $event->data->object;
        $localReference  = $session->client_reference_id ?? null;
        $paymentStatus   = $session->payment_status       ?? null;

        if (!$localReference || $paymentStatus !== 'paid') {
            return response()->json(['message' => 'Ignored.'], 200);
        }

        $idempotencyKey = ProcessedWebhook::buildIdempotencyKey('stripe', 'checkout.session.completed', $event->id);

        return $this->processConfirmedPayment(
            provider: 'stripe',
            eventType: 'checkout.session.completed',
            providerEventId: $event->id,
            idempotencyKey: $idempotencyKey,
            providerReference: $localReference
        );
    }

    // =========================================================================
    // Shared provisioning
    // =========================================================================

    /**
     * Process a confirmed payment: idempotency check → PENDING→PAID → provision entitlement.
     * All three steps run in a single DB::transaction.
     */
    private function processConfirmedPayment(
        string $provider,
        string $eventType,
        string $providerEventId,
        string $idempotencyKey,
        string $providerReference
    ): JsonResponse {
        try {
            DB::transaction(function () use (
                $provider, $eventType, $providerEventId, $idempotencyKey, $providerReference
            ) {
                // Step A: Idempotency check inside the transaction.
                // If this webhook was already processed, we get a duplicate key exception
                // which Laravel converts to an exception that rolls back the transaction.
                if (ProcessedWebhook::alreadyProcessed($idempotencyKey)) {
                    throw new \RuntimeException('already_processed');
                }

                // Step B: Find the matching EventTransaction (by local_reference or provider_reference).
                $transaction = EventTransaction::where('local_reference', $providerReference)
                    ->orWhere('provider_reference', $providerReference)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    Log::warning('PaymentWebhookController: no matching transaction found', [
                        'provider'   => $provider,
                        'reference'  => $providerReference,
                    ]);
                    throw new \RuntimeException('no_transaction');
                }

                // Step C: PENDING → PAID transition (only valid if currently PENDING).
                $transitioned = $transaction->transitionToPaid($providerReference);
                if (!$transitioned) {
                    // Already PAID or FAILED — not an error, just skip.
                    throw new \RuntimeException('already_processed');
                }

                // Step D: Provision prereg_users entitlement (create if direct purchase).
                $user = $transaction->user;
                PreRegUserModel::updateOrCreate(
                    [
                        'prereg_id' => $transaction->event_id,
                        'email'     => strtolower($user->email),
                    ],
                    [
                        'name'                    => trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) ?: ($user->email),
                        'phone'                   => $user->phone ?? '',
                        'paid'                    => 1,
                        'paid_at'                 => now(),
                        'payment_provider'        => $provider,
                        'payment_reference'       => $providerReference,
                    ]
                );

                // Step E: Initialize fulfillment log.
                FulfillmentLog::initializeFor($transaction->id);

                // Step F: Record this webhook as processed INSIDE the transaction.
                // If steps C/D/E roll back, this also rolls back.
                ProcessedWebhook::create([
                    'provider'             => $provider,
                    'event_type'           => $eventType,
                    'provider_event_id'    => $providerEventId,
                    'idempotency_key'      => $idempotencyKey,
                    'event_transaction_id' => $transaction->id,
                    'processed_at'         => now(),
                ]);

                // Step G: Dispatch PaymentSucceeded after the transaction commits.
                // Fulfilment jobs are dispatched via ->afterCommit() listeners.
                event(new \App\Events\PaymentSucceeded($transaction));
            });
        } catch (\RuntimeException $e) {
            if (in_array($e->getMessage(), ['already_processed', 'no_transaction'])) {
                return response()->json(['message' => 'ok'], 200);
            }
            Log::error('PaymentWebhookController: provisioning failed', [
                'provider'  => $provider,
                'error'     => substr($e->getMessage(), 0, 300),
            ]);
            return response()->json(['message' => 'Processing error.'], 500);
        }

        return response()->json(['message' => 'ok'], 200);
    }
}
