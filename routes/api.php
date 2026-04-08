<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OperationController;
use App\Http\Controllers\Api\IssuerController;
use App\Http\Controllers\Api\ObligationController;
use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['api.key'])->group(function () {

    // Public read access
    Route::get('/issuers', [IssuerController::class, 'index']);
    Route::get('/operations', [OperationController::class, 'index']);
    Route::get('/obligations', [ObligationController::class, 'index']);

    // Audit routes
    Route::middleware(['role:ADMIN,AUDITOR'])->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
        Route::get('/users', [UserController::class, 'index']);
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

        // Users

        Route::post('/users', [UserController::class, 'store']);
        Route::patch('/users/{user}', [UserController::class, 'update']);
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus']);

    });

    Route::middleware(['role:ADMIN,ANALYST'])->group(function () {
        // Update obligation status
        Route::patch('/obligations/{obligation}/status', [ObligationController::class, 'updateStatus']);
    });

});