<?php
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request; // Añade esta línea


// Ruta principal
Route::get('/Inicio', function () {
    return view('index');
});
Route::get('/Menu', function () {
    return view('menu');
})->name('menu');
Route::get('/Registro', function () {
    return view('registro');
});
Route::get('/subirImagenes' ,function(){
    return view('subirImagenes');
});


Route::get('/checkout' ,function(){
    return view('checkout');
});

Route::get('/pedidos' ,function(){
    return view('pedidos');
});



Route::prefix('admin')->group(function () {
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
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    });

    Route::get('/sizes', function () {
        return view('admin.pizza-sizes.index');
    });

});
