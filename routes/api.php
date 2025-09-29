<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Application\User\Controllers\Api\AuthController;
use Application\Product\Controllers\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/register', 'register')->name('api.auth.register');
        Route::post('/login', 'login')->name('api.auth.login');
        Route::post('/verify-email', 'verifyEmail')->name('api.auth.verify-email');
        Route::post('/resend-verification-code', 'reSendVerificationCode')->name('api.auth.resend-verification-code');
    });

    Route::apiResource('products', ProductController::class)->middleware('jwt.auth');
});
