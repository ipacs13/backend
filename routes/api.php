<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Products\ProductController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);

        //Tanan API nga need mo login ang user dapat ibutang dani
        Route::middleware('auth:api')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/user', [AuthController::class, 'user']);
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
        });
    });
});
