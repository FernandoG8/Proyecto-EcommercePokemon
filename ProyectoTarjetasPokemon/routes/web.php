<?php

use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return view('index');
});

// Rutas para la API de Pokémon
Route::get('/api/pokemon', [PokemonController::class, 'index']);
Route::get('/api/pokemon/type/{type}', [PokemonController::class, 'getByType']);
Route::get('/api/pokemon/search', [PokemonController::class, 'search']);
Route::get('/api/pokemon/{id}', [PokemonController::class, 'show']);