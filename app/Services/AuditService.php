<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

/**
 * Class AuditService
 *
 * Handles audit logging for critical system actions.
 */
class AuditService
{
    /**
     * Log an action with before and after states.
     *
     * @param int $userId
     * @param string $action
     * @param array|null $before
     * @param array|null $after
     * @return void
     */
    public function log(int $userId, string $action, ?array $before = null, ?array $after = null): void
    {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'details' => [
                'before' => $before,
                'after' => $after,
            ],
        ]);
    }

    /**
     * Execute a transaction and log it atomically.
     *
     * @param callable $callback
     * @param int $userId
     * @param string $action
     * @return mixed
     */
    public function logWithTransaction(
        callable $callback,
        int $userId,
        string $action
    ): mixed {
        return DB::transaction(function () use ($callback, $userId, $action) {

            $result = $callback();

            // Wait for the callback to return.:
            // ['before' => [...], 'after' => [...]]
            $this->log(
                $userId,
                $action,
                $result['before'] ?? null,
                $result['after'] ?? null
            );

            return $result;
        });
    }
}