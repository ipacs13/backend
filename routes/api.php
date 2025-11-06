<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Controllers\Api\Otp\OtpController;
use App\Http\Controllers\Api\User\UserController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        //Tanan API nga need mo login ang user dapat ibutang dani
        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
            Route::post('/signature', [UserController::class, 'uploadSignature']);
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'store']);

            Route::prefix('export')->group(function () {
                Route::get('/pdf', [ProductController::class, 'exportPdf']);
                Route::get('/excel', [ProductController::class, 'exportExcel']);
            });

            Route::prefix('{product}')->group(function () {
                Route::get('/', [ProductController::class, 'show']);
                Route::put('/', [ProductController::class, 'update']);
                Route::delete('/', [ProductController::class, 'destroy']);
            });
        });
    });

    Route::prefix('otp')->group(function () {
        Route::post('/send', [OtpController::class, 'send']);
        Route::post('/verify', [OtpController::class, 'verify']);
    });
});
