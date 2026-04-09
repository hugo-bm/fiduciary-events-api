<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiKeyAuthMiddleware;
use App\Http\Middleware\RoleMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Support\RequestLogger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            // Authentication
            'api.key' => \App\Http\Middleware\ApiKeyAuthMiddleware::class,

            // Authorization
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            RequestLogger::log('warning', 'Route not found');

            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found',
            ], 404);
        });

        // 500
        $exceptions->render(function (\Throwable $e, $request) {

            if (app()->runningInConsole()) {
                \Log::error($e);
                return;
            }

            RequestLogger::log('error', 'Unhandled exception', [
                'exception' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An internal error occurred. Please try again later.',
            ], 500);
        });
    })->create();
