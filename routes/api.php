<?php

use App\Presentation\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('wallets')->group(function () {
    Route::post('{wallet}/deposit', [WalletController::class, 'deposit']);
    Route::post('{wallet}/withdraw', [WalletController::class, 'withdraw']);
});
