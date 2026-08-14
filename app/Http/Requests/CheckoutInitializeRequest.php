<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CheckoutInitializeRequest
 *
 * Validates the incoming checkout initialization request.
 *
 * SECURITY:
 *   - Amount, currency, and price are NOT accepted from the client.
 *   - eventId is accepted from the client but is a public event identifier.
 *     The server verifies the event exists, is paid (free=0), and reads
 *     the price from the database — never from the request.
 *   - The authenticated user is identified via Auth::user() — never from request data.
 */
class CheckoutInitializeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // The prereg event ID. Must be a positive integer.
            // NOTE: this is used as a lookup key only. Price is read server-side.
            'event_id' => ['required', 'integer', 'min:1'],

            // Payment provider: 'paystack' or 'stripe'.
            'provider' => ['required', 'string', 'in:paystack,stripe'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'An event is required to proceed to checkout.',
            'event_id.integer'  => 'Invalid event identifier.',
            'provider.in'       => 'The payment provider must be paystack or stripe.',
        ];
    }
}
