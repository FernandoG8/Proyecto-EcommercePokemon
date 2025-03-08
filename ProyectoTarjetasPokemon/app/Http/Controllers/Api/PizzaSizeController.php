<?php

namespace App\Http\Controllers\Api;

use App\Models\PizzaSize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class PizzaSizeController extends Controller
{
    /**
     * Obtiene los tamaños de pizza activos.
     */
    public function index()
    {
        try {
            $sizes = PizzaSize::all();
            return response()->json(['sizes' => $sizes]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener los tamaños de pizza.'], 500);
        }
    }

    /**
     * Crea un nuevo tamaño de pizza.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pizza_sizes',
            'price_multiplier' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $size = PizzaSize::create($request->all());
            return response()->json(['size' => $size, 'message' => 'Tamaño de pizza creado exitosamente'], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al crear el tamaño de pizza.'], 500);
        }
    }

    /**
     * Muestra un tamaño de pizza específico.
     */
    public function show(PizzaSize $pizzaSize)
    {
        try {
            return response()->json(['size' => $pizzaSize]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el tamaño de pizza.'], 500);
        }
    }

    /**
     * Actualiza un tamaño de pizza existente.
     */
    public function update(Request $request, PizzaSize $pizzaSize)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:pizza_sizes,name,' . $pizzaSize->id,
            'price_multiplier' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            $pizzaSize->update($request->all());
            return response()->json(['size' => $pizzaSize, 'message' => 'Tamaño de pizza actualizado exitosamente']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al actualizar el tamaño de pizza.'], 500);
        }
    }

    /**
     * Elimina un tamaño de pizza.
     */
    public function destroy(PizzaSize $pizzaSize)
    {
        try {
            $pizzaSize->delete();
            return response()->json(['message' => 'Tamaño de pizza eliminado exitosamente']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al eliminar el tamaño de pizza.'], 500);
        }
    }
}
