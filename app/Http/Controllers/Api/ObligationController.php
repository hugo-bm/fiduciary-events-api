<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdateObligationStatusRequest;
use App\Models\Obligation;
use App\Services\ObligationService;
use App\Http\Resources\ObligationResource;

/**
 * Class ObligationController
 *
 * Handles obligation endpoints.
 */
class ObligationController extends Controller
{

    /**
     * Create a new obligation
     */
    public function store(
        StoreObligationRequest $request,
        ObligationService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $obligation = $service->create(
            $request->validated(),
            $user->id
        );

        return response()->json([
            'message' => 'Obligation created successfully',
            'data' => new ObligationResource($obligation)
        ], 201);
    }

    /**
     * Update obligation status
     *
     * @return JsonResponse
     */
     public function updateStatus(
        UpdateObligationStatusRequest $request,
        Obligation $obligation,
        ObligationService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $updatedObligation = $service->updateStatus(
            $obligation,
            $request->input('status'),
            $user->id
        );

        return response()->json([
            'message' => 'Obligation status updated successfully',
            'data' => new ObligationResource($updatedObligation)
        ]);
    }
}
