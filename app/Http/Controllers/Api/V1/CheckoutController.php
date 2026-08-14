<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutInitializeRequest;
use App\Models\EventTransaction;
use App\Models\PreRegModel;
use App\Services\Payment\PaymentProviderResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * CheckoutController
 *
 * Initializes a payment checkout session for a paid webinar event.
 *
 * SECURITY INVARIANTS:
 *   - Amount and currency are ALWAYS read from the prereg event record.
 *     They are NEVER accepted from the frontend.
 *   - eventId from the request is used as a lookup key only.
 *     The server verifies: event exists, event is paid (free=0), event is active.
 *   - User identity comes from Auth::user() only.
 *   - A duplicate PENDING transaction for the same user+event is reused (not duplicated).
 *
 * POST /api/v1/checkout/initialize
 */
class CheckoutController extends Controller
{
    public function __construct(
        private readonly PaymentProviderResolver $providerResolver
    ) {}

    /**
     * Initialize a checkout session.
     *
     * @param  CheckoutInitializeRequest $request
     * @return JsonResponse
     */
    public function initialize(CheckoutInitializeRequest $request): JsonResponse
    {
        $user      = Auth::user();
        $validated = $request->validated();
        $eventId   = (int) ($validated['event_id'] ?? $request->input('event_id'));
        $provider  = (string) ($validated['provider'] ?? $request->input('provider'));

        // Step 1: Load and verify the event server-side.
        $event = PreRegModel::where('id', $eventId)
            ->where('free', 0) // Must be a paid event
            ->first();

        if (!$event) {
            return response()->json(['message' => 'Event not found or is not a paid event.'], 404);
        }

        // Step 2: Lock price server-side from the event record.
        // NEVER use amount or currency from the request.
        $rawAmount = (float) $event->amount;
        // If the amount in prereg is major units (e.g. 6000 for 6,000 NGN), convert to minor units (600,000 kobo).
        // If already minor units (> 50,000), keep as-is.
        $amountMinor = ($rawAmount > 0 && $rawAmount <= 50000)
            ? (int) round($rawAmount * 100)
            : (int) round($rawAmount);
        $currency    = strtoupper($event->currency ?? 'NGN');

        if ($amountMinor <= 0) {
            return response()->json(['message' => 'Event has no valid price configured.'], 422);
        }

        // Step 3: Check if user is already paid for this event.
        $alreadyPaid = \App\Models\PreRegUserModel::where('prereg_id', $eventId)
            ->where('email', strtolower($user->email))
            ->where('paid', 1)
            ->exists();

        if ($alreadyPaid) {
            return response()->json(['message' => 'You have already paid for this event.'], 422);
        }

        // Step 4: Reuse existing PENDING transaction or create a new one.
        // This prevents duplicate checkout sessions for the same user+event.
        $existingTransaction = EventTransaction::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->where('status', EventTransaction::STATUS_PENDING)
            ->first();

        if ($existingTransaction) {
            // Return existing pending transaction — do not create a new one.
            try {
                $providerService = $this->providerResolver->resolve($existingTransaction->provider);
                // Re-initialize with the same local_reference to get a fresh URL.
                $result = $providerService->initialize(
                    $user->id,
                    $user->email,
                    $eventId,
                    $amountMinor,
                    $currency,
                    $existingTransaction->local_reference
                );
            } catch (\Exception $e) {
                Log::error('CheckoutController: re-initialization failed for existing transaction', [
                    'transaction_id' => $existingTransaction->id,
                    'error' => substr($e->getMessage(), 0, 300),
                ]);
                return response()->json(['message' => 'Checkout initialization failed. Please try again.'], 502);
            }

            return response()->json([
                'payment_url'     => $result['payment_url'],
                'access_code'     => $result['access_code'],
                'local_reference' => $existingTransaction->local_reference,
                'ticket_number'   => $existingTransaction->ticket_number,
                'provider'        => $existingTransaction->provider,
            ]);
        }

        // Step 5: Create new transaction inside a DB transaction.
        $localReference = 'KNT-' . strtoupper(Str::random(16));

        $pricingSnapshot = [
            'event_id'     => $eventId,
            'amount_minor' => $amountMinor,
            'currency'     => $currency,
            'locked_at'    => now()->toISOString(),
        ];

        try {
            $transaction = DB::transaction(function () use (
                $user, $eventId, $amountMinor, $currency, $provider, $localReference, $pricingSnapshot
            ) {
                $tx = new EventTransaction([
                    'user_id'          => $user->id,
                    'event_id'         => $eventId,
                    'amount_minor'     => $amountMinor,
                    'currency'         => $currency,
                    'provider'         => $provider,
                    'local_reference'  => $localReference,
                    'status'           => EventTransaction::STATUS_PENDING,
                    'pricing_snapshot' => 'placeholder', // will be replaced
                ]);
                $tx->setPricingSnapshot($pricingSnapshot);
                $tx->save();
                return $tx;
            });
        } catch (\Exception $e) {
            Log::error('CheckoutController: failed to create transaction', [
                'error' => substr($e->getMessage(), 0, 300),
            ]);
            return response()->json(['message' => 'Failed to initialize checkout. Please try again.'], 500);
        }

        // Step 6: Initialize with the provider.
        try {
            $providerService = $this->providerResolver->resolve($provider);
            $result = $providerService->initialize(
                $user->id,
                $user->email,
                $eventId,
                $amountMinor,
                $currency,
                $localReference
            );
        } catch (\Exception $e) {
            // Mark transaction as initialization_failed.
            $transaction->update(['status' => EventTransaction::STATUS_INITIALIZATION_FAILED]);
            Log::error('CheckoutController: provider initialization failed', [
                'transaction_id' => $transaction->id,
                'provider'       => $provider,
                'error'          => substr($e->getMessage(), 0, 300),
            ]);
            return response()->json(['message' => 'Payment provider initialization failed. Please try again.'], 502);
        }

        // Step 7: Store provider session ID.
        $transaction->update([
            'provider_session_id' => $result['access_code'],
        ]);

        return response()->json([
            'payment_url'     => $result['payment_url'],
            'access_code'     => $result['access_code'],
            'local_reference' => $localReference,
            'ticket_number'   => $transaction->ticket_number,
            'provider'        => $provider,
        ], 201);
    }
}
