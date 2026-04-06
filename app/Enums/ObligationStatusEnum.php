<?php

namespace App\Enums;

/**
 * Enum ObligationStatusEnum
 *
 * Represents the lifecycle status of an obligation.
 */
enum ObligationStatusEnum: string
{
    case PENDING = 'PENDING';
    case DELIVERED = 'DELIVERED';
    case CANCELED = 'CANCELED';
}
