<?php

use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('wallets')->name('wallets.')->group(static function () {

    Route::get('', [WalletController::class, 'index'])->name('index');
    Route::post('', [WalletController::class, 'store'])->name('store');
    Route::delete('{id}', [WalletController::class, 'destroy'])->name('destroy');

});
