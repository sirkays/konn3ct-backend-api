<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Validated query parameters for GET /api/v1/admin/financials/transactions
 */
class TransactionListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page'        => 'integer|min:1',
            'limit'       => 'integer|min:1|max:100',
            'status'      => 'nullable|string|max:90',
            'paymentType' => 'nullable|string|max:150',
            'gateway'     => 'nullable|string|max:255',
            'startDate'   => 'nullable|date',
            'endDate'     => 'nullable|date|after_or_equal:startDate',
        ];
    }

    public function messages(): array
    {
        return [
            'endDate.after_or_equal' => 'The endDate must not be earlier than startDate.',
            'startDate.date'         => 'The startDate must be a valid date or ISO-8601 value.',
            'endDate.date'           => 'The endDate must be a valid date or ISO-8601 value.',
            'limit.max'              => 'The limit may not be greater than 100.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'code'    => 'VALIDATION_ERROR',
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422)->header('Cache-Control', 'no-store')
        );
    }
}
