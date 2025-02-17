<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/datosPago', function () {
    $total = request('total', 0); // Obtener el total de la URL (valor predeterminado: 0)
    return view('datosPago', ['total' => $total]); // Pasar el total a la vista
});