<?php

declare(strict_types = 1);

namespace App\Domain\Models\Transaction\Create;

use App\Domain\Models\Transaction\Enum\TransactionStatusEnum;
use App\Domain\Models\Transaction\Enum\TransactionTypeEnum;

class TransactionCreateModel
{
    public function __construct(
        public int $walletId,
        public TransactionTypeEnum $type,
        public float $amount,
        public TransactionStatusEnum $status,
        public string $operationId,
        public ?string $txHash = null
    ) {}
}
