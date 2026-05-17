<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductImportController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('products', ProductController::class)
        ->only(['index', 'store', 'show', 'destroy']);
        Route::post('/products/import', [ProductImportController::class, 'store']);

Route::get('/imports/{id}', [ProductImportController::class, 'show']);
});

