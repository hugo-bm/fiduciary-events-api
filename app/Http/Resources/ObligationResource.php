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
     * Calculate risk level based on due date
     */
    private function calculateRiskLevel(): string
    {
        $dueDate = $this->due_date;
        $today = now();

        if ($dueDate <= $today) {
            return 'CRITICAL';
        }

        if ($dueDate <= $today->copy()->addDays(14)) {
            return 'WARNING';
        }

        return 'NORMAL';
    }

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
            'risk_level' => $this->calculateRiskLevel(),
        ];
    }
}
