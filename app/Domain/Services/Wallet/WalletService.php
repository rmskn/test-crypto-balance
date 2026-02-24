<?php

declare(strict_types=1);

namespace App\Domain\Services\Wallet;

use App\Application\Repositories\Transaction\TransactionRepository;
use App\Application\Repositories\Wallet\WalletRepository;
use App\Domain\Models\Transaction\Create\TransactionCreateModel;
use App\Domain\Models\Transaction\Enum\TransactionStatusEnum;
use App\Domain\Models\Transaction\Enum\TransactionTypeEnum;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WalletService
{
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly TransactionRepository $transactionRepository,
    ) {}

    public function deposit(
        int $walletId,
        float $amount,
        string $operationId,
        string $txHash
    ): void {
        if ($amount < 0) {
            throw new RuntimeException('Insufficient funds');
        }

        DB::transaction(function () use ($walletId, $amount, $operationId, $txHash) {
            if ($this->transactionRepository->existsByOperationId($operationId)) {
                return; // duplicate operation
            }

            $wallet = $this->walletRepository->lockForUpdate($walletId);

            $wallet->balance += $amount;
            $this->walletRepository->save($wallet);

            $model = new TransactionCreateModel(
                $wallet->id,
                TransactionTypeEnum::DEPOSIT,
                $amount,
                TransactionStatusEnum::PENDING,
                $operationId,
                $txHash
            );

            $this->transactionRepository->create($model);
        });
    }

    public function withdraw(
        int $walletId,
        float $amount,
        string $operationId,
        TransactionTypeEnum $type = TransactionTypeEnum::WITHDRAW
    ): void {
        if ($amount < 0) {
            throw new RuntimeException('Insufficient funds');
        }

        DB::transaction(function () use ($walletId, $amount, $operationId, $type) {

            if ($this->transactionRepository->existsByOperationId($operationId)) {
                return; // duplicate
            }

            $wallet = $this->walletRepository->lockForUpdate($walletId);

            $wallet->reserveAmount($amount);

            $model = new TransactionCreateModel(
                walletId: $wallet->id,
                type: $type,
                amount: $amount,
                status: TransactionStatusEnum::PENDING,
                operationId: $operationId
            );

            $this->transactionRepository->create($model);
        });
    }
}
