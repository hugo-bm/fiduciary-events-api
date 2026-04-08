<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOperationRequest;
use App\Http\Requests\UpdateOperationRequest;
use App\Http\Resources\OperationResource;
use App\Models\Operation;
use App\Services\OperationService;
use Illuminate\Http\JsonResponse;

/**
 * Class OperationController
 *
 * Handles operation endpoints.
 */
class OperationController extends Controller
{
    /**
     * List operations
     */
    public function index(): JsonResponse
    {
        $operations = Operation::paginate(15);

        return response()->json([
            'data' => OperationResource::collection($operations),
            'meta' => [
                'current_page' => $operations->currentPage(),
                'last_page' => $operations->lastPage(),
                'per_page' => $operations->perPage(),
                'total' => $operations->total(),
            ]
        ]);
    }

    /**
     * Create operation
     */
    public function store(
        StoreOperationRequest $request,
        OperationService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $operation = $service->create(
            $request->validated(),
            $user->id
        );

        return response()->json([
            'message' => 'Operation created successfully',
            'data' => new OperationResource($operation)
        ], 201);
    }

    /**
     * Update operation
     */
    public function update(
        UpdateOperationRequest $request,
        Operation $operation,
        OperationService $service
    ): JsonResponse {

        $user = $request->attributes->get('authenticated_user');

        $operation = $service->update(
            $operation,
            $request->validated(),
            $user->id
        );

        return response()->json([
            'message' => 'Operation updated successfully',
            'data' => new OperationResource($operation)
        ]);
    }
}
