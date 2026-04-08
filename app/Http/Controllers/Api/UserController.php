<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * Handles user management endpoints
 */
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::with('operations')->paginate(15);

        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'total' => $users->total()
            ]
        ]);
    }

    public function store(StoreUserRequest $request, UserService $service): JsonResponse
    {
        $admin = $request->attributes->get('authenticated_user');

        $result = $service->create($request->validated(), $admin->id);

        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource($result['user']),
            'api_key' => $result['api_key'], 
            'warning' => 'Store this API key securely. It will not be shown again.'
        ], 201);
    }

    public function update(UpdateUserRequest $request, User $user, UserService $service): JsonResponse
    {
        $admin = $request->attributes->get('authenticated_user');

        $user = $service->update($user, $request->validated(), $admin->id);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => new UserResource($user),
        ]);
    }

    public function updateStatus(UpdateUserStatusRequest $request, User $user, UserService $service): JsonResponse
    {
        $admin = $request->attributes->get('authenticated_user');

        $user = $service->updateStatus(
            $user,
            $request->validated()['is_active'],
            $admin->id
        );

        return response()->json([
            'message' => 'User status updated',
            'data' => new UserResource($user),
        ]);
    }
}
