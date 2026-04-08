<?php

namespace App\Services;

use App\Models\Obligation;
use Illuminate\Support\Facades\DB;
Use App\Enums\ObligationStatusEnum;
Use App\Enums\UserRoleEnum;

class ObligationService
{
    public function __construct(
        private AuditService $auditService
    ) {}


    /**
     * Create a new obligation with audit logging
     */
    public function create(array $data, int $userId): Obligation
    {
        $this->ensureUserHasAccessToOperation($data['operation_id'], $user, [UserRoleEnum::ADMIN]);
        return DB::transaction(function () use ($data, $userId) {

            $obligation = Obligation::create([
                'operation_id' => $data['operation_id'],
                'title' => $data['title'],
                'due_date' => $data['due_date'],
                'status' => $data['status'],
                'delivered_at' => null,
            ]);

            
            $this->auditService->log(
                $userId,
                'CREATE_OBLIGATION',
                null,
                $obligation->toArray()
            );

            return $obligation;
        });
    }

    public function updateStatus(
        Obligation $obligation,
        string $status,
        int $userId
    ): Obligation {
        $this->ensureUserHasAccessToOperation($obligation->operation_id, $user, [UserRoleEnum::ANALYST, UserRoleEnum::ADMIN]);
        return DB::transaction(function () use ($obligation, $status, $userId) {

            $before = $obligation->toArray();

            $obligation->status = $status;

            if ($status === ObligationStatusEnum::DELIVERED) {
                $obligation->delivered_at = now();
            }

            $obligation->save();

            $after = $obligation->toArray();


            $this->auditService->log(
                $userId,
                'UPDATE_OBLIGATION_STATUS',
                $before,
                $after
            );

            return $obligation;
        });
    }


    private function ensureUserHasAccessToOperation(int $operationId, $user, array $roles): void
    {
        $userRole = UserRoleEnum::from($user->role);

        $allowedRoles = array_map(
            fn ($role) => UserRoleEnum::from($role),
            $roles
        );

        if (!$userRole->canAccess($allowedRoles)) {
            return;
        }

        $hasAccess = $user->operations()
            ->where('operations.id', $operationId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Forbidden: operation not assigned to user');
        }
    }
}