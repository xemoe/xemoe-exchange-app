<?php

use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Support\Facades\Route;

//
// Guest route for user register and login
//
Route::middleware(['middleware' => 'guest:api'])->group(static function () {
    Route::prefix('auth')->name('auth.')->group(static function () {
        //
        // User register
        //
        Route::post('/register', [RegisterController::class, 'register'])->name('register');

        //
        // User login and receive token
        //
        Route::post('/login', [LoginController::class, 'login'])->name('login');
    });
});
