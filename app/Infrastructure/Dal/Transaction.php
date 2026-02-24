<?php

declare(strict_types=1);

namespace App\Infrastructure\Dal;

use App\Domain\Models\Transaction\Create\TransactionCreateModel;
use App\Domain\Models\Transaction\Enum\TransactionStatusEnum;
use App\Domain\Models\Transaction\Enum\TransactionTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $wallet_id
 * @property TransactionTypeEnum $type
 * @property float $amount
 * @property TransactionStatusEnum $status
 * @property string|null $tx_hash
 * @property string $operation_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Wallet $wallet
 */
class Transaction extends Model
{
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public static function create(TransactionCreateModel $model): Transaction
    {
        $eloquentModel = new Transaction();

        $eloquentModel->setWallet($model->walletId);
        $eloquentModel->setType($model->type);
        $eloquentModel->setOperationId($model->operationId);
        $eloquentModel->setAmount($model->amount);
        $eloquentModel->setStatus($model->status);
        $eloquentModel->setTxHash($model->txHash);
        $eloquentModel->setCreatedAt(Carbon::now());
        $eloquentModel->setUpdatedAt(Carbon::now());

        $eloquentModel->save();

        return $eloquentModel;
    }

    public function setWallet(int $value): void
    {
        $this->wallet_id = $value;
    }

    public function setType(TransactionTypeEnum $value): void
    {
        $this->type = $value;
    }

    public function setAmount(float $value): void
    {
        $this->amount = $value;
    }

    public function setOperationId(string $value): void
    {
        $this->operation_id = $value;
    }

    public function setStatus(TransactionStatusEnum $value): void
    {
        $this->status = $value;
    }

    public function setTxHash(?string $value): void
    {
        $this->tx_hash = $value;
    }
}
