<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Validated request body for POST /api/v1/admin/users/{id}/ban
 *
 * The confirmationCode must exactly equal 'CONFIRM BAN' (case-sensitive).
 * This is enforced via a custom validation rule, not in rules() to keep
 * the error message precise.
 */
class BanUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason'           => 'required|string|max:1000',
            'confirmationCode' => ['required', 'string', function ($attribute, $value, $fail) {
                // Case-sensitive exact match — strcmp preserves case
                if (strcmp($value, 'CONFIRM BAN') !== 0) {
                    $fail('The confirmation code must exactly equal "CONFIRM BAN" (case-sensitive).');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required'           => 'A reason for the ban is required.',
            'reason.max'                => 'The reason may not exceed 1,000 characters.',
            'confirmationCode.required' => 'A confirmation code is required.',
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
