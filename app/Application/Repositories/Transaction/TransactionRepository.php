<?php

declare(strict_types=1);

namespace App\Application\Repositories\Transaction;

use App\Domain\Models\Transaction\Create\TransactionCreateModel;
use App\Infrastructure\Dal\Transaction;

class TransactionRepository
{
    public function findByOperationIdRequiredAndLock(string $operationId): Transaction
    {
        /** @var Transaction $result */
        $result =  Transaction::query()
            ->where('operation_id', $operationId)
            ->lockForUpdate()
            ->firstOrFail();

        return $result;
    }

    public function existsByOperationId(string $operationId): bool
    {
        return Transaction::query()
            ->where('operation_id', $operationId)
            ->exists();
    }

    public function create(TransactionCreateModel $model): Transaction
    {
        return Transaction::create($model);
    }
}
