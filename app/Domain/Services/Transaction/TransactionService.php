<?php

declare(strict_types=1);

namespace App\Domain\Services\Transaction;

use App\Application\Repositories\Transaction\TransactionRepository;
use App\Application\Repositories\Wallet\WalletRepository;
use App\Domain\Models\Transaction\Enum\TransactionStatusEnum;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TransactionService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly TransactionRepository $transactionRepository
    ) {}

    public function confirmTransaction(string $operationId, ?string $txHash = null): void
    {
        DB::transaction(function () use ($operationId, $txHash) {
            $transaction = $this->transactionRepository->findByOperationIdRequiredAndLock($operationId);

            $wallet = $this->walletRepository->lockForUpdate($transaction->wallet_id);

            $wallet->applyReserved($transaction->amount);

            $transaction->status = TransactionStatusEnum::CONFIRMED;
            $transaction->tx_hash = $txHash;

            $transaction->save();
        });
    }

    public function failTransaction(string $operationId): void
    {
        DB::transaction(function () use ($operationId) {
            $transaction = $this->transactionRepository->findByOperationIdRequiredAndLock($operationId);

            if ($transaction->status === TransactionStatusEnum::CONFIRMED) {
                throw new RuntimeException("Cannot fail confirmed transaction: $operationId");
            }

            $wallet = $this->walletRepository->lockForUpdate($transaction->wallet_id);

            $wallet->releaseReserved($transaction->amount);

            $transaction->status = TransactionStatusEnum::FAILED;

            $transaction->save();
        });
    }
}
