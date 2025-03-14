<?php

// use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\Api\V1\Admin\AdminDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\authController;
use App\Http\controllers\Api\V1\Admin\CategoryController;
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
        Route::middleware(['role:super_admin|product_manager'])->group(function(){
            Route::get('/products', [ProductController::class, 'index'])->name('products.index');
            Route::post('/products', [ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
            Route::put('/products/{id}', [ProductController::class, 'update'])->name('product.update');
            Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
            Route::post('/categories', [categoryController::class, 'store'])->name('categories.store');
            Route::get('/categories/{id}', [categoryController::class, 'show'])->name('category.show');
            Route::put('/categories/{id}', [categoryController::class, 'update'])->name('category.update');
            Route::delete('/categories/{id}', [categoryController::class, 'destroy'])->name('category.destroy');
        // Route::apiResource('/products',ProductController::class);
        });
    });
});
// Route::get('v1/admin/products', [ProductController::class, 'index']);
