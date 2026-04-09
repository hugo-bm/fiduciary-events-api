<?php

namespace App\Services;

use App\Models\Obligation;
use Illuminate\Support\Facades\DB;
Use App\Enums\ObligationStatusEnum;
Use App\Enums\UserRoleEnum;
use App\Services\AuditService;

class ObligationService
{
    public function __construct(
        private AuditService $auditService
    ) {}


    /**
     * Create a new obligation with audit logging
     */
    public function create(array $data, $user): Obligation
    {
        $this->ensureUserHasAccessToOperation($data['operation_id'], $user, ["ADMIN"]);
        return DB::transaction(function () use ($data, $user) {

            $obligation = Obligation::create([
                'operation_id' => $data['operation_id'],
                'title' => $data['title'],
                'due_date' => $data['due_date'],
                'status' => ObligationStatusEnum::PENDING->value,
                'delivered_at' => null,
            ]);

            
            $this->auditService->log(
                $user->id,
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
        $user
    ): Obligation {
        $this->ensureUserHasAccessToOperation($obligation->operation_id, $user, ["ANALYST", "ADMIN"]);
        return DB::transaction(function () use ($obligation, $status, $user) {
            
            $before = $obligation->toArray();

            $obligation->status = $status;

            if ($status === ObligationStatusEnum::DELIVERED) {
                $obligation->delivered_at = now();
            }

            $obligation->save();

            $after = $obligation->toArray();

            $this->auditService->log(
                $user->id,
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
            abort(403, 'Forbidden');
        }

        if ($userRole !== UserRoleEnum::ADMIN)
        {
            $hasAccess = $user->operations()
            ->where('operations.id', $operationId)
            ->exists();

            if (!$hasAccess ) {
                abort(403, 'Forbidden: operation not assigned to user');
            }
        }
    }
}