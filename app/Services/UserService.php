<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\UserRoleEnum;

/**
 * Handles user management logic
 */
class UserService
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Create user with API key and optional operation assignment
     */
    public function create(array $data, int $adminId): array
    {
        return DB::transaction(function () use ($data, $adminId) {

            // Generate api key
            $plainApiKey = Str::random(40);
            $requestRole = UserRoleEnum::from($data['role']);

            $user = User::create([
                'name' => $data['name'],
                'role' => $requestRole->value,
                'is_active' =>  true,
                'api_key' => bcrypt($plainApiKey),
            ]);

            // Link operations (if it's an ANALYST)
            if ($requestRole === UserRoleEnum::ANALYST && !empty($data['operation_ids'])) {
                $user->operations()->sync($data['operation_ids']);
            }

            $this->auditService->log(
                $adminId,
                'CREATE_USER',
                null,
                $user->toArray()
            );

            return [
                'user' => $user,
                'api_key' => $plainApiKey, // only retruns here
            ];
        });
    }

    /**
     * Update user and reassign operations
     */
    public function update(User $user, array $data, int $adminId): User
    {
        return DB::transaction(function () use ($user, $data, $adminId) {

            $before = $user->toArray();

            $user->update($data);

            if (UserRoleEnum::from($user->role) === UserRoleEnum::ANALYST) {
                $user->operations()->sync($data['operation_ids'] ?? []);
            }

            $this->auditService->log(
                $adminId,
                'UPDATE_USER',
                $before,
                $user->toArray()
            );

            return $user;
        });
    }

    /**
     * Activate / deactivate user
     */
    public function updateStatus(User $user, bool $status, int $adminId): User
    {
        return DB::transaction(function () use ($user, $status, $adminId) {

            $before = $user->toArray();

            $user->update(['is_active' => $status]);

            $this->auditService->log(
                $adminId,
                'UPDATE_USER_STATUS',
                $before,
                $user->toArray()
            );

            return $user;
        });
    }
}