<?php

declare(strict_types=1);

namespace App\Domain\Models\Transaction\Enum;

enum TransactionStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case FAILED = 'failed';
}
