<?php

namespace App\Enums;

/**
 * Enum UserRoleEnum
 *
 * Defines system access roles.
 */
enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case ANALYST = 'ANALYST';
    case AUDITOR = 'AUDITOR';

    /**
     * Determine if the current role is allowed to access based on a list of permitted roles.
     *
     * This method checks whether the current enum instance exists within
     * the provided array of allowed roles.
     *
     * @param UserRoleEnum[] $allowedRoles Array of roles allowed to access
     *
     * @return bool True if the current role is in the allowed list, false otherwise
     */
    public function canAccess(array $allowedRole): bool
    {
        return in_array($this, $allowedRole, true);
    }
}

