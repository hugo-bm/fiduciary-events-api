<?php

namespace App\Services;

use App\Models\Operation;
use App\Enums\OperationTypeEnum;
use Illuminate\Support\Facades\DB;

/**
 * Class OperationService
 *
 * Handles business logic for operations.
 */
class OperationService
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Create a new operation
     *
     * @param array $data
     * @param int $userId
     * @return Operation
     */
    public function create(array $data, int $userId): Operation
    {
        return DB::transaction(function () use ($data, $userId) {

            $operation = Operation::create([
                'issuer_id' => $data['issuer_id'],
                'asset_code' => $data['asset_code'],
                'operation_type' => OperationTypeEnum::from($data['operation_type']),
            ]);

            $this->auditService->log(
                $userId,
                'CREATE_OPERATION',
                null,
                $operation->toArray()
            );

            return $operation;
        });
    }

    /**
     * Update operation
     *
     * @param Operation $operation
     * @param array $data
     * @param int $userId
     * @return Operation
     */
    public function update(Operation $operation, array $data, int $userId): Operation
    {
        return DB::transaction(function () use ($operation, $data, $userId) {

            $before = $operation->toArray();

            $updateData = [];

            if (isset($data['asset_code'])) {
                $updateData['asset_code'] = $data['asset_code'];
            }

            if (isset($data['operation_type'])) {
                $updateData['operation_type'] = OperationTypeEnum::from($data['operation_type']);
            }

            $operation->update($updateData);

            $this->auditService->log(
                $userId,
                'UPDATE_OPERATION',
                $before,
                $operation->toArray()
            );

            return $operation;
        });
    }
}