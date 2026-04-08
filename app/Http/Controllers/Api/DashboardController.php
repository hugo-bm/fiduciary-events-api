<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;

/**
 * Class DashboardController
 *
 * Handles dashboard endpoints.
 */
class DashboardController extends Controller
{
    private function buildMeta(): array
    {
        return [
            'generated_at' => now()->toISOString(), // ISO 8601
            'timezone' => config('app.timezone'),
        ];
    }

    public function summary(DashboardService $service): JsonResponse
    {
        $user = request()->attributes->get('authenticated_user');

        $data = $service->getSummary($user);

        return response()->json([
            'data' => $data,
            'meta' => $this->buildMeta(),
        ]);
    }
}
