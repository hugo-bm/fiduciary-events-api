<?php

namespace App\Services;

use App\Models\Issuer;
use Illuminate\Support\Facades\DB;

/**
 * Class IssuerService
 *
 * Handles business rules for issuers.
 */
class IssuerService
{
    public function __construct(
        private AuditService $auditService
    ) {}

    /**
     * Create a new issuer
     */
    public function create(array $data, int $userId): Issuer
    {
        return DB::transaction(function () use ($data, $userId) {

            $issuer = Issuer::create([
                'name' => $data['name'],
                'cnpj' => $data['cnpj'],
            ]);

            $this->auditService->log(
                $userId,
                'CREATE_ISSUER',
                null,
                $issuer->toArray()
            );

            return $issuer;
        });
    }

    /**
     * Update issuer
     */
    public function update(Issuer $issuer, array $data, int $userId): Issuer
    {
        return DB::transaction(function () use ($issuer, $data, $userId) {

            $before = $issuer->toArray();

            $issuer->update($data);

            $after = $issuer->toArray();

            $this->auditService->log(
                $userId,
                'UPDATE_ISSUER',
                $before,
                $after
            );

            return $issuer;
        });
    }
}
