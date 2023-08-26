<?php

use App\Http\Controllers\Api\V1\FiatTradingPairController;
use Illuminate\Support\Facades\Route;

Route::prefix('fiat/trading_pairs')->name('fiat.trading_pairs.')->group(static function () {

    Route::get('', [FiatTradingPairController::class, 'index'])->name('index');

});
