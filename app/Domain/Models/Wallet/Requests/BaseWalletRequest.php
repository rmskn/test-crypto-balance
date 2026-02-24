<?php

declare (strict_types=1);

namespace App\Domain\Models\Wallet\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseWalletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'operationId' => ['required', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getAmount(): float
    {
        return (float)$this->input('amount');
    }

    public function getOperationId(): string
    {
        return (string)$this->input('operationId');
    }
}
