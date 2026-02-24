<?php

declare(strict_types=1);

namespace App\Application\Repositories\Wallet;

use App\Infrastructure\Dal\Wallet;

class WalletRepository
{
    public function lockForUpdate(int $walletId): Wallet
    {
        /** @var Wallet $wallet */
        $wallet = Wallet::query()
            ->where('id', $walletId)
            ->lockForUpdate()
            ->firstOrFail();

        return $wallet;
    }

    public function save(Wallet $wallet): void
    {
        $wallet->save();
    }
}
