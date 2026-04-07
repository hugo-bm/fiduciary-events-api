<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreObligationRequest
 *
 * Handles validation for creating obligations.
 */
class StoreObligationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false; //ACL via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'operation_id' => 'required|exists:operations,id',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'status' => 'required|in:PENDING,DELIVERED,CANCELLED',
        ];
    }
}
