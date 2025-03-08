<?php
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request; // Añade esta línea


// Rutas públicas (sin autenticación requerida)
Route::get('/Inicio', function () {
    return view('index');
});

Route::get('/Menu', function () {
    return view('menu');
})->name('menu');

Route::get('/Registro', function () {
    return view('registro');
});

Route::get('/checkout' ,function(){
    return view('checkout');
});

Route::get('/pedidos' ,function(){
    return view('pedidos');
});




// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->prefix('admin')->group(function () {
    
    Route::get('/products', function () {
        return view('admin.products.index');
    });

    Route::get('/products/create', function () {
        return view('admin.products.create');
    });

    Route::get('/products/{product}/edit', function () {
        return view('admin.products.edit');
    });

    Route::get('/categories', function () {
        return view('admin.categories.index');
    });

    Route::get('/categories/create', function () {
        return view('admin.categories.create');
    });

    Route::get('/categories/{category}/edit', function () {
        return view('admin.categories.edit');
    });
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    });

    Route::get('/sizes', function () {
        return view('admin.pizza-sizes.index');
    });
    Route::get('/sizes/create', function () {
        return view('admin.pizza-sizes.create');
    });

    Route::get('/sizes/{size_id}/edit', function () {
        return view('admin.pizza-sizes.edit');
    });

    Route::get('/orders', function () {
        return view('admin.orders.index');
    });
    Route::get('/orders/{orderId}/edit', function () {
        return view('admin.orders.edit');
    });
});
