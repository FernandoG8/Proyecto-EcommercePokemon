<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PizzaToppingController;
use App\Http\Controllers\Api\PizzaSizeController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas v1 como canepa
Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Catálogo de productos (público)
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/sizes', [PizzaSizeController::class, 'index']);
});

// Rutas protegidas (requieren autenticación)
// Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Perfil de usuario
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    
    // Carrito de compras
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'addItem']);
    Route::put('/cart/{cartItem}', [CartController::class, 'updateItem']);
    Route::delete('/cart/{cartItem}', [CartController::class, 'removeItem']);
    Route::delete('/cart', [CartController::class, 'clear']);
    
    // Pedidos
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    
    // Rutas solo para administradores
    Route::middleware('admin')->group(function () {
        // Gestión de categorías
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
        
        // Gestión de productos
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);
        
        // Gestión de tamaños
        Route::post('/sizes', [PizzaSizeController::class, 'store']);
        Route::put('/sizes/{pizzaSize}', [PizzaSizeController::class, 'update']);
        Route::delete('/sizes/{pizzaSize}', [PizzaSizeController::class, 'destroy']);
        
        // Gestión de pedidos (solo admin)
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);
        Route::put('/orders/{order}/payment', [OrderController::class, 'updatePaymentStatus']);
    });
// });