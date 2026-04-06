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
}
