<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CnpjRule;

class UpdateIssuerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorized via middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'name' => 'sometimes|string|max:255',
        'cnpj' => [
            'sometimes',
            'string',
            'size:14',
            new CnpjRule(),
            'unique:issuers,cnpj,' . $this->route('issuer')->id,
        ],
    ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->cnpj) {
            $this->merge([
                'cnpj' => preg_replace('/\D/', '', $this->cnpj),
            ]);
        }
    }
}
