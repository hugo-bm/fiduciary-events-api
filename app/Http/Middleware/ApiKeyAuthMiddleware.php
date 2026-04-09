<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Support\RequestLogger;

class ApiKeyAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            RequestLogger::log('warning', 'Unauthorized access attempt', [
                'reason' => 'Missing API key',
            ]);
            return response()->json([
                'message' => 'API key is required'
            ], 401);
        }

        $users = User::where('is_active', true)->select('id', 'api_key', 'role')->get();

        $authenticatedUser = null;

        foreach ($users as $user) {
            if (Hash::check($apiKey, $user->api_key)) {
                $authenticatedUser = $user;
                break;
            }
        }

        if (!$authenticatedUser) {
            RequestLogger::log('warning', 'Unauthorized access attempt', [
                'reason' => 'Invalid API key',
            ]);
            return response()->json([
                'message' => 'Invalid API key'
            ], 401);
        }

        // Attach user to request
        $request->attributes->set('authenticated_user', $authenticatedUser);
        
        return $next($request);
    }
}
