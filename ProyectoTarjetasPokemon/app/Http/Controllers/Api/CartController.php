<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PizzaSize;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Obtiene el carrito del usuario autenticado.
     */
    public function index(Request $request)
{
    try {
        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        $items = $cart->items()
            ->with(['product', 'size'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => (float)$item->unit_price,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'price' => (float)$item->unit_price // Add this line
                    ],
                    'size' => $item->size ? [
                        'id' => $item->size->id,
                        'name' => $item->size->name
                    ] : null,
                    'subtotal' => (float)$item->unit_price * $item->quantity
                ];
            });

        $total = $items->sum('subtotal');

        return response()->json([
            'items' => $items,
            'total' => $total
        ]);

    } catch (\Exception $e) {
        Log::error('Error loading cart: ' . $e->getMessage());
        return response()->json(['error' => 'Error al cargar el carrito'], 500);
    }
}



    /**
     * Agrega un producto al carrito del usuario.
     */
    public function addItem(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'special_instructions' => 'nullable|string',
                'pizza_size_id' => 'nullable|exists:pizza_sizes,id'
            ]);
    
            $cart = Cart::firstOrCreate([
                'user_id' => $request->user()->id
            ]);
    
            $cartItem = $cart->items()->create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'special_instructions' => $validated['special_instructions'] ?? null,
                'pizza_size_id' => $validated['pizza_size_id'] ?? null
            ]);
    
            return response()->json([
                'message' => 'Producto agregado al carrito',
                'cart_item' => $cartItem->load('product', 'size')
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Error adding item to cart: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al agregar el producto al carrito'
            ], 500);
        }
    }
    
    /**
     * Actualiza un producto en el carrito.
     */
    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'special_instructions' => 'nullable|string',
        ]);

        try {
            if ($cartItem->cart->user_id !== $request->user()->id) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }

            $product = $cartItem->product;
            $oldQuantity = $cartItem->quantity;
            $newQuantity = $request->quantity ?? $oldQuantity;

            if ($newQuantity > $oldQuantity && $product->stock < ($newQuantity - $oldQuantity)) {
                return response()->json(['error' => 'Stock insuficiente.'], 422);
            }

            if ($newQuantity > $oldQuantity) {
                $product->decrement('stock', $newQuantity - $oldQuantity);
            } elseif ($newQuantity < $oldQuantity) {
                $product->increment('stock', $oldQuantity - $newQuantity);
            }

            $cartItem->update($request->only(['quantity', 'special_instructions']));

            return response()->json(['message' => 'Producto actualizado.', 'cart_item' => $cartItem->load('product', 'size')]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto.'], 500);
        }
    }

    /**
     * Elimina un producto del carrito.
     */
    public function removeItem(Request $request, CartItem $cartItem)
    {
        try {
            if ($cartItem->cart->user_id !== $request->user()->id) {
                return response()->json(['error' => 'No autorizado.'], 403);
            }

            $cartItem->product->increment('stock', $cartItem->quantity);
            $cartItem->delete();

            return response()->json(['message' => 'Producto eliminado del carrito.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el producto.'], 500);
        }
    }

    /**
     * Vacía el carrito del usuario.
     */
    public function clear(Request $request)
    {
        try {
            $cart = $request->user()->cart;
            
            if ($cart) {
                foreach ($cart->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }
                $cart->items()->delete();
            }

            return response()->json(['message' => 'Carrito vaciado correctamente.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al vaciar el carrito.'], 500);
        }
    }
}
