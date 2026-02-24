<?php

declare (strict_types=1);

namespace App\Domain\Models\Wallet\Requests;

class WalletDepositRequest extends BaseWalletRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'txHash' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getTxHash(): string
    {
        return (string)$this->input('txHash');
    }
}
