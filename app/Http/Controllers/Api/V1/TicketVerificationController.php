<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EventTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * TicketVerificationController
 *
 * Verifies a ticket number via a signed URL.
 *
 * GET /api/v1/tickets/{number}/verify
 *
 * SECURITY:
 *   - The URL must carry a valid Laravel signature (signed route).
 *   - The ticket_number is non-sequential and non-enumerable.
 *   - This endpoint returns ONLY: validity, event title, event date.
 *   - It NEVER returns: email, payment details, replay tokens, or join URLs.
 */
class TicketVerificationController extends Controller
{
    /**
     * Verify a ticket by its number.
     * This endpoint is publicly accessible but requires a valid signed URL.
     *
     * @param  Request $request
     * @param  string  $number  Ticket number (e.g. TICK-2026-ABCDEFGHIJKL)
     * @return JsonResponse
     */
    public function verify(Request $request, string $number): JsonResponse
    {
        // Validate the Laravel signed URL signature.
        if (!$request->hasValidSignature()) {
            return response()->json([
                'valid'   => false,
                'message' => 'This verification link is invalid or has expired.',
            ], 403);
        }

        // Look up the ticket.
        $transaction = EventTransaction::where('ticket_number', $number)
            ->with('event') // load related prereg event
            ->first();

        if (!$transaction) {
            return response()->json([
                'valid'   => false,
                'message' => 'Ticket not found.',
            ], 404);
        }

        $isPaid = $transaction->status === EventTransaction::STATUS_PAID;

        // Only return minimal information — no PII, no payment details.
        return response()->json([
            'valid'       => $isPaid,
            'status'      => $transaction->status,
            'event_title' => optional($transaction->event)->title,
            'event_date'  => optional($transaction->event)->date,
        ], 200, [
            'Cache-Control' => 'no-store',
        ]);
    }
}
