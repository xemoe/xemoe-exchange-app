<?php

use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('wallet')->name('wallet.')->group(static function () {

    Route::get('', [WalletController::class, 'index'])->name('get');
    Route::post('', [WalletController::class, 'store'])->name('create');
    Route::delete('{id}', [WalletController::class, 'destroy'])->name('delete');

});
