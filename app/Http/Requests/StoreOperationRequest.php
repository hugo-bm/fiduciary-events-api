<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Handles validation for creating operations.
 */
class StoreOperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ture; // Authorized via middleware
    }

    protected function prepareForValidation(): void
    {
        if ($this->operation_code) {
            $this->merge([
                'asset_code' => strtoupper($this->asset_code),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'issuer_id' => 'required|exists:issuers,id',
            'asset_code' => 'required|string|max:50|unique:operations,asset_code',
            'operation_type' => ['required', 'in:' . implode(',', array_column(OperationTypeEnum::cases(), 'value'))],
        ];
    }
}
