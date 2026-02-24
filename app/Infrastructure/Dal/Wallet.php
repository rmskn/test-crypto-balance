<?php

declare(strict_types=1);

namespace App\Infrastructure\Dal;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $currency
 * @property float $balance
 * @property float $balance_reserved
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Transaction[] $transactions
 */
class Wallet extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getBalanceAvailable(): float
    {
        return round($this->balance - $this->balance_reserved, 8);
    }

    public function reserveAmount(float $amount): void
    {
        if ($this->getBalanceAvailable() < $amount) {
            throw new \RuntimeException('Insufficient available balance');
        }

        $this->balance_reserved += $amount;
        $this->save();
    }

    public function releaseReserved(float $amount): void
    {
        $this->balance_reserved -= $amount;

        $this->save();
    }

    public function applyReserved(float $amount): void
    {
        $this->balance_reserved -= $amount;
        $this->balance -= $amount;

        $this->save();
    }
}
