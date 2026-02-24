<?php

declare(strict_types=1);

namespace App\Infrastructure\Dal;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Wallet[] $wallets
 */
class User extends Model
{
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }
}
