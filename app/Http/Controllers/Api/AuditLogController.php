<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;

/**
 * Class AuditLogController
 *
 * Handles audit log read-only endpoints.
 */
class AuditLogController extends Controller
{
    /**
     * List audit logs with pagination
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $logs = AuditLog::query()
            ->select([
                'id',
                'user_id',
                'action',
                'details_json',
                'created_at',
            ])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => AuditLogResource::collection($logs),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ]
        ]);
    }    
}
