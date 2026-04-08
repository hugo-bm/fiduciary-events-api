<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIssuerRequest;
use App\Http\Requests\UpdateIssuerRequest;
use App\Http\Resources\IssuerResource;
use App\Models\Issuer;
use App\Services\IssuerService;
use Illuminate\Http\JsonResponse;

/**
 * Class IssuerController
 *
 * Handles issuer endpoints.
 */
class IssuerController extends Controller
{
    
    /**
     * List all issuers
     */
    public function index(): JsonResponse
    {
        $issuers = Issuer::paginate(15);

        return response()->json([
            'data' => IssuerResource::collection($issuers),
            'meta' => [
                'current_page' => $issuers->currentPage(),
                'last_page' => $issuers->lastPage(),
                'per_page' => $issuers->perPage(),
                'total' => $issuers->total(),
            ]
        ]);
    }
        /**
     * Create a new issuer
     */
    public function store(
        StoreIssuerRequest $request,
        IssuerService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $issuer = $service->create(
            $request->validated(),
            $user->id
        );

        return response()->json([
            'message' => 'Issuer created successfully',
            'data' => new IssuerResource($issuer)
        ], 201);
    }

    /**
     * Update issuer
     */
    public function update(
        UpdateIssuerRequest $request,
        Issuer $issuer,
        IssuerService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $issuer = $service->update(
            $issuer,
            $request->validated(),
            $user->id
        );

        return response()->json([
            'message' => 'Issuer updated successfully',
            'data' => new IssuerResource($issuer)
        ]);
    }
    
}
