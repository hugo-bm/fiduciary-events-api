<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRoleEnum;
use App\Support\RequestLogger;

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
            RequestLogger::log('warning', 'Unauthorized access attempt', [
                'reason' => 'Resource access is not permitted',
            ]);
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
            RequestLogger::log('warning', 'Forbidden access attempt', [
                'required_roles' => $roles,
                'user_role' => $user->role,
            ]);
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return $next($request);
    }
}
