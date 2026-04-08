<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Class OperationResource
     *
     * Transforms operation data into API response.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'issuer_id' => $this->issuer_id,
            'asset_code' => $this->asset_code,
            'operation_type' => $this->operation_type->value,
        ];
    }
}
