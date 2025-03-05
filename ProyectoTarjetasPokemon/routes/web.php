<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

// Ruta principal
Route::get('/Inicio', function () {
    return view('index');
});
Route::get('/Menu', function () {
    return view('menu');
});
Route::get('/Registro', function () {
    return view('registro');
});
Route::get('/subirImagenes' ,function(){
    return view('subirImagenes');
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

});
