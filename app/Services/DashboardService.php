<?php

namespace App\Services;

use App\Models\Obligation;

/**
 * Class DashboardService
 *
 * Handles dashboard aggregation logic.
 */
class DashboardService
{
    /**
     * Get summary of obligations by risk level
     */
    public function getSummary($user): array
    {
        $query = Obligation::query()->pending();

        // 🔐 aplicar escopo
        $query = Obligation::visibleTo($user)->pending();

        return [
            'critical' => (clone $query)->overdue()->count(),
            'warning' => (clone $query)->warning()->count(),
            'normal' => (clone $query)->normal()->count(),
        ];
    }
}