<?php

use Illuminate\Http\Request;
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

Route::prefix('v1')->name('api.v1.')->group(function () {

    include __DIR__ . '/_api/v1/auth.php';

    //
    // Protected routes for authorized users
    // with token in header
    //
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('/user', static function (Request $request) {
            return $request->user();
        })->name('user');

        include __DIR__ . '/_api/v1/wallet.php';

    });
});
