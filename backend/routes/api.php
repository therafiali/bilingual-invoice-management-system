<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });


    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);

        Route::prefix('page')->group(function () {
            Route::post('add', [PageController::class, 'addPage']);
            Route::get('/{id?}', [PageController::class, 'getPage']);
        });
    });
});
