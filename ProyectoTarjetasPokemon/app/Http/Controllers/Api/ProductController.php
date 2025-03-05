<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
    /**
     * Obtiene una lista paginada de productos con filtros opcionales.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // 1. Validación de parámetros
            $validator = Validator::make($request->all(), [
                'category_slug' => 'sometimes|string|exists:categories,slug',
                'search' => 'sometimes|string|max:255',
                'active' => 'sometimes|boolean',
                'per_page' => 'sometimes|integer|min:1|max:100'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $validated = $validator->validated();
    
            // 2. Construcción de query
            $query = Product::with('category')
            //validated confirma qu sea category_slug
                ->when(isset($validated['category_slug']), function ($q) use ($validated) {
                    // Esto funciona con ?category_slug="nombredelSlug"
                    $q->whereHas('category', function ($subQuery) use ($validated) {
                        $subQuery->where('slug', $validated['category_slug']);
                    });
                })

                ->when(isset($validated['search']), function ($q) use ($validated) {
                  //funciona pasando   ?search= "coincidencia"
                    $q->where(function ($subQuery) use ($validated) {
                        $subQuery->where('name', 'like', "%{$validated['search']}%")
                                ->orWhere('description', 'like', "%{$validated['search']}%");
                    });
                })
                ->when(isset($validated['active']), function ($q) use ($validated) {
                    // filtra los prodcutos activos con ?active=true
                    $q->where('is_active', $validated['active']);
                });
    
            // 3. Paginación configurable
            //validamos con ?per_page="cantidad de productos traidos"
            $perPage = $validated['per_page'] ?? 10;
            $products = $query->paginate($perPage);
    
             // 4. Transformación usando API Resources
        return response()->json([
            'data' => ProductResource::collection($products),
            'message' => 'Products retrieved successfully',
            'meta' => [
                'filters' => $validated,
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'last_page' => $products->lastPage()
                ]
            ]
        ],200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        Log::error('Product fetch error', ['exception' => $e]);
        return response()->json([
            'message' => 'Error retrieving products'
        ], 500);
    }
    }

    /**
     * Crea un nuevo producto.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category_id' => 'required|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            $validatedData['slug'] = Str::slug($validatedData['name']);
            $validatedData['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('products/images', 'public');
            }

            $product = Product::create($validatedData);
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

            return response()->json(['product' => $product, 'message' => 'Product created successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating product', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra un producto específico.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        $product->load('category');
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return response()->json(['product' => $product]);
    }

    /**
     * Actualiza un producto existente.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|numeric|min:0',
                'stock' => 'sometimes|integer|min:0',
                'category_id' => 'sometimes|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_active' => 'boolean',
            ]);

            if ($request->has('name')) {
                $validatedData['slug'] = Str::slug($request->name);
            }

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $validatedData['image'] = $request->file('image')->store('products/images', 'public');
            }

            $product->update($validatedData);
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

            return response()->json(['product' => $product, 'message' => 'Product updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation Error', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating product', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Elimina un producto.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting product', 'error' => $e->getMessage()], 500);
        }
    }
}
