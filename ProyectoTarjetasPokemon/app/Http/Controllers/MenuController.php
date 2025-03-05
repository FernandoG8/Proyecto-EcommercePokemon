<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function getMenu()
    {
        // Renderiza la vista menu.blade.php y devuelve su contenido como respuesta JSON
        $menuContent = view('menu')->render();
        return response()->json(['content' => $menuContent]);
    }
}