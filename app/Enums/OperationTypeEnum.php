<?php

namespace App\Enums;

/**
 * Enum OperationTypeEnum
 *
 * Represents types of financial operations.
 */
enum OperationTypeEnum: string
{
    case DEBENTURE = 'DEBENTURE';
    case CRI = 'CRI';
    case CRA = 'CRA';
}
