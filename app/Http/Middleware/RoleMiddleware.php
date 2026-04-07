<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->attributes->get('authenticated_user');

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

         // Converte role do banco para enum
        $userRole = UserRoleEnum::from($user->role);

        // Converte roles da rota para enum
        $allowedRoles = array_map(
            fn ($role) => UserRoleEnum::from($role),
            $roles
        );

        if (!$userRole->canAccess($allowedRoles)) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}
