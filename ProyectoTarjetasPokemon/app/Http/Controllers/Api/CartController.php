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

class CartController extends Controller
{
    /**
     * Obtiene el carrito del usuario autenticado.
     */
    public function index(Request $request)
    {
        try {
            $cart = $request->user()->cart;
            
            if (!$cart) {
                $cart = Cart::create(['user_id' => $request->user()->id]);
            }
            
            $cart->load('items.product', 'items.size');
            
            return response()->json([
                'cart' => $cart,
                'items' => $cart->items,
                'total' => $cart->getTotal()
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el carrito.'], 500);
        }
    }

    /**
     * Agrega un producto al carrito del usuario.
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string',
            'pizza_size_id' => 'nullable|exists:pizza_sizes,id',
        ]);

        try {
            DB::beginTransaction();
            
            $user = $request->user();
            $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);
            
            $product = Product::findOrFail($request->product_id);
            
            if ($product->stock < $request->quantity) {
                return response()->json(['error' => 'Stock insuficiente.'], 422);
            }

            $unitPrice = $product->price;
            
            if ($request->has('pizza_size_id') && $request->pizza_size_id) {
                $size = PizzaSize::findOrFail($request->pizza_size_id);
                $unitPrice *= $size->price_multiplier;
            }

            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'special_instructions' => $request->special_instructions,
                'unit_price' => $unitPrice,
                'pizza_size_id' => $request->pizza_size_id,
            ]);

            $product->decrement('stock', $request->quantity);
            
            DB::commit();
            
            return response()->json(['message' => 'Producto agregado al carrito.', 'cart_item' => $cartItem->load('product', 'size')], 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al agregar el producto al carrito.'], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error inesperado.'], 500);
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
