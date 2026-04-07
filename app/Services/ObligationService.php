<?php

namespace App\Services;

use App\Models\Obligation;
use Illuminate\Support\Facades\DB;

class ObligationService
{
    public function __construct(
        private AuditService $auditService
    ) {}

    public function updateStatus(
        Obligation $obligation,
        string $status,
        int $userId
    ): Obligation {
        return DB::transaction(function () use ($obligation, $status, $userId) {

            $before = $obligation->toArray();

            $obligation->status = $status;

            if ($status === 'COMPLETED') {
                $obligation->delivered_at = now();
            }

            $obligation->save();

            $after = $obligation->toArray();

            // 🔥 LOG DENTRO DA TRANSAÇÃO
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