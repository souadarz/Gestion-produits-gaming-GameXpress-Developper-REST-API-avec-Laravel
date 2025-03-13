<?php

// use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\authController;
use App\Http\Controllers\Api\V1\Admin\ProductController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/admin')->group(function () {
    Route::post('/register', [authController::class , 'register']);
    Route::post('/login', [authController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [authController::class, 'logout']);
        Route::get('/dashboard', [AdminDashboardController::class, 'AdminDashboard']);
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        // Route::apiResource('/products',ProductController::class);

    });
});
// Route::get('v1/admin/products', [ProductController::class, 'index']);
