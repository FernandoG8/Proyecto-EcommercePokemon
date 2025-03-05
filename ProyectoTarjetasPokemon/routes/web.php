<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

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
