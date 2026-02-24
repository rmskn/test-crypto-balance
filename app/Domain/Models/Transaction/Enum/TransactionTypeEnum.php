<?php

declare(strict_types=1);

namespace App\Domain\Models\Transaction\Enum;

enum TransactionTypeEnum: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case FEE = 'fee';
}
