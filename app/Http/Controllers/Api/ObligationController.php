<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class ObligationController
 *
 * Handles obligation-related endpoints.
 */
class ObligationController extends Controller
{
    /**
     * Update obligation status
     *
     * @return JsonResponse
     */
    public function updateStatus(): JsonResponse
    {
        return response()->json([
            'message' => 'Not implemented yet'
        ]);
    }
}
