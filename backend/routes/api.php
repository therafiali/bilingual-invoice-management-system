<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\invoice\InvoiceController;
use App\Http\Controllers\Pages\PageContentController;
use App\Http\Controllers\Pages\PageController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\StorageController;
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
            Route::delete('/{id}', [PageController::class, 'deletePage']);
            Route::put('/{id}', [PageController::class, 'editPage']);
        });

        Route::prefix('content')->group(function () {
            Route::post('add', [PageContentController::class, 'addPageContent']);
            Route::put('/{id}', [PageContentController::class, 'updatePageContent']);
            Route::delete('/{id}', [PageContentController::class, 'deletePageContent']);
            Route::get('/{id}', [PageContentController::class, 'getPageContent']);
        });

        Route::prefix('storage')->group(function () {
            Route::post('/', [StorageController::class, 'uploadFiles']);
            Route::delete('/', [StorageController::class, 'deleteFile']);
        });

        Route::prefix('qr')->group(function () {
            Route::post('/', [QrCodeController::class, 'generateQr']);
        });

        Route::prefix('invoices')->group(function () {
            Route::post('/generate', [InvoiceController::class, 'generateInvoice']);
        });
    });
});
