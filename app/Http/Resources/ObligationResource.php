<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ObligationResource
 *
 * Transforms obligation data into API response format.
 */
class ObligationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'operation_id' => $this->operation_id,
            'title' => $this->title,
            'status' => $this->status,
            'due_date' => $this->due_date,
            'delivered_at' => $this->delivered_at,
        ];
    }
}
