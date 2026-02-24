<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Domain\Models\Wallet\Requests\WalletDepositRequest;
use App\Domain\Models\Wallet\Requests\WalletWithdrawRequest;
use App\Domain\Services\Wallet\WalletService;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletService $walletService
    ) {}

    public function deposit(int $wallet, WalletDepositRequest $request): JsonResponse
    {
        $this->walletService->deposit(
            $wallet,
            $request->getAmount(),
            $request->getTxHash(),
            $request->getOperationId()
        );

        return response()->json();
    }

    public function withdraw(int $wallet, WalletWithdrawRequest $request): JsonResponse
    {
        $this->walletService->withdraw(
            $wallet,
            $request->getAmount(),
            $request->getOperationId()
        );

        return response()->json();
    }
}
