<?php

namespace App\Services;

use App\Models\Obligation;
use Illuminate\Support\Facades\DB;
Use App\Enums\ObligationStatusEnum;

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
        return DB::transaction(function () use ($data, $userId) {

            $obligation = Obligation::create([
                'operation_id' => $data['operation_id'],
                'title' => $data['title'],
                'due_date' => $data['due_date'],
                'status' => $data['status'],
                'delivered_at' => $data['status'] === 'COMPLETED' ? now() : null,
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
}