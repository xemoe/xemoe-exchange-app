<?php

use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\RegisterController;
use Illuminate\Support\Facades\Route;

//
// Guest route for user register and login
//
Route::group(['middleware' => 'guest:api'], static function () {
    //
    // User register
    //
    Route::post('/register', [RegisterController::class, 'register'])->name('register');

    //
    // User login and receive token
    //
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});
