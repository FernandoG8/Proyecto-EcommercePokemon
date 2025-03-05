<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    /**
     * Obtiene todas las categorías.
     */
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json(['categories' => $categories]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener las categorías.'], 500);
        }
    }

    /**
     * Crea una nueva categoría.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        try {
            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
            ]);

            return response()->json(['category' => $category, 'message' => 'Categoría creada exitosamente.'], 201);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al crear la categoría.'], 500);
        }
    }

    /**
     * Muestra una categoría específica.
     */
    public function show(Category $category)
    {
        try {
            return response()->json(['category' => $category]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener la categoría.'], 500);
        }
    }

    /**
     * Actualiza una categoría existente.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        try {
            $data = $request->only(['name', 'description']);
            
            if ($request->has('name')) {
                $data['slug'] = Str::slug($request->name);
            }

            $category->update($data);

            return response()->json(['category' => $category, 'message' => 'Categoría actualizada exitosamente.']);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al actualizar la categoría.'], 500);
        }
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json(['message' => 'Categoría eliminada exitosamente.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar la categoría.'], 500);
        }
    }
}
