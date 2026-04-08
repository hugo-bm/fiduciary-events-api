<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enum\UserRoleEnum;

/**
 * Handles user creation validation
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //autorized via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'role' => ['required', 'in:' . implode(',', array_column(UserRoleEnum::cases(), 'value'))],
            'is_active' => 'boolean',
            'operation_ids' => 'array',
            'operation_ids.*' => 'exists:operations,id',
        ];
    }
}
