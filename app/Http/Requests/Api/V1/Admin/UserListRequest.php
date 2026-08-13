<?php

namespace App\Http\Requests\Api\V1\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Validated query parameters for GET /api/v1/admin/users
 */
class UserListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled by admin.jwt and admin.permission middleware
    }

    public function rules(): array
    {
        return [
            'page'      => 'integer|min:1',
            'limit'     => 'integer|min:1|max:100',
            'search'    => 'nullable|string|max:255',
            'role'      => 'nullable|string|max:50',
            'status'    => 'nullable|string|max:20',
            'sortBy'    => 'nullable|string|in:id,name,email,role,status,createdAt,lastUsed',
            'sortOrder' => 'nullable|string|in:asc,desc',
        ];
    }

    public function messages(): array
    {
        return [
            'sortBy.in'    => 'The sortBy value must be one of: id, name, email, role, status, createdAt, lastUsed.',
            'sortOrder.in' => 'The sortOrder value must be asc or desc.',
            'limit.max'    => 'The limit may not be greater than 100.',
            'page.min'     => 'The page must be at least 1.',
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

    /**
     * Trim the search term before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('search')) {
            $this->merge(['search' => trim($this->input('search', ''))]);
        }
    }
}
