<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use App\Enums\UserRoleEnum;

/**
 * Class UpdateUserRequest
 *
 * Handles validation for updating users.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if user can make this request
     */
    public function authorize(): bool
    {
        return true; // Autorized via middleware
    }

    
    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'role' => [
                'sometimes',
                'in:' . implode(',', array_column(UserRoleEnum::cases(), 'value')),
            ],
            'is_active' => 'sometimes|boolean',

            // operations (only ANALYST)
            'operation_ids' => 'sometimes|array',
            'operation_ids.*' => 'exists:operations,id',
        ];
    }

    /**
     * Custom validation logic
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // If the role is ANALYST -> operation_ids required
            if ($this->role === UserRoleEnum::ANALYST->value) {
                if (!$this->has('operation_ids')) {
                    $validator->errors()->add(
                        'operation_ids',
                        'Operation assignment is required for ANALYST role.'
                    );
                }
            }
        });
    }
}