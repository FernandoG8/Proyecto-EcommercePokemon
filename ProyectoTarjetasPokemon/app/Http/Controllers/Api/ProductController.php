<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Listar productos con filtros y paginación.
     */
    public function index(Request $request)
    {
        $query = Product::with('category'); // Cargar la relación con categorías

        // Filtrar por categoría
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Buscar por nombre o descripción
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtrar solo productos activos
        if ($request->has('active') && $request->active) {
            $query->where('is_active', true);
        }

        // Obtener productos paginados
        $products = $query->paginate(10);

        // Agregar la URL pública de la imagen a cada producto
        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
            return $product;
        });

        return response()->json($products);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(Request $request)
{
    // Validar la solicitud
    $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|string|unique:products',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0', // Asegúrate de que esta línea esté corregida
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|string|max:2048',
        'is_active' => 'boolean',
    ]);

    // Extraer los datos validados
    $data = $request->only(['name', 'slug', 'description', 'price', 'stock', 'category_id', 'image', 'is_active']);
    
    // Crear el producto
    $product = Product::create($data);

    return response()->json(['product' => $product, 'message' => 'Product created successfully'], 201);
}

    /**
     * Mostrar un producto específico.
     */
    public function show(Product $product)
    {
        // Cargar la relación con categorías
        $product->load('category');

        // Agregar la URL pública de la imagen
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json(['product' => $product]);
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');

        if ($request->has('name')) {
            $data['slug'] = Str::slug($request->name);
        }

        // Manejar la subida de imágenes
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('Products/images', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return response()->json(['product' => $product, 'message' => 'Product updated successfully']);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product)
    {
        // Eliminar la imagen si existe
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}