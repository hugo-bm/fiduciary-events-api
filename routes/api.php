<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OperationController;
use App\Http\Controllers\Api\IssuerController;
use App\Http\Controllers\Api\ObligationController;
use App\Http\Controllers\Api\AuditLogController;

Route::middleware(['api.key'])->group(function () {

    // Public read access
    Route::get('/issuers', [IssuerController::class, 'index']);
    Route::get('/operations', [OperationController::class, 'index']);
    Route::get('/obligations', fn () => 'list obligations');

    // Audit Logs
    Route::middleware(['role:ADMIN,AUDITOR'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
    });

    // Admin only
    Route::middleware(['role:ADMIN'])->group(function () {
         // Issuers / Operations
        Route::post('/issuers', [IssuerController::class, 'store']);
        Route::put('/issuers/{issuer}', [IssuerController::class, 'update']);

        Route::post('/operations', [OperationController::class, 'store']);
        Route::put('/operations/{operation}', [OperationController::class, 'update']);

        // Obligations
        Route::post('/obligations', [ObligationController::class, 'store']);

    });

    Route::middleware(['role:ADMIN,ANALYST'])->group(function () {
        // Update obligation status
        Route::patch('/obligations/{obligation}/status', [ObligationController::class, 'updateStatus']);
    });

});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
