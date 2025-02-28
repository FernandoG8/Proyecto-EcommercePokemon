<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PizzaTopping;
use App\Models\PizzaSize;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string',
            'selected_toppings' => 'nullable|array',
            'selected_toppings.*' => 'exists:pizza_toppings,id',
            'pizza_size_id' => 'nullable|exists:pizza_sizes,id',
        ]);

        $user = $request->user();
        $cart = $user->cart;
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => $user->id]);
        }

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is in stock
        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock available'], 422);
        }

        // Calculate price with toppings
        $unitPrice = $product->price;
        $selectedToppings = [];
        
        if ($request->has('selected_toppings') && count($request->selected_toppings) > 0) {
            $toppings = PizzaTopping::whereIn('id', $request->selected_toppings)->get();
            
            foreach ($toppings as $topping) {
                $unitPrice += $topping->price;
                $selectedToppings[] = [
                    'id' => $topping->id,
                    'name' => $topping->name,
                    'price' => $topping->price
                ];
            }
        }
        
        // Apply size multiplier if selected
        if ($request->has('pizza_size_id') && $request->pizza_size_id) {
            $size = PizzaSize::findOrFail($request->pizza_size_id);
            $unitPrice *= $size->price_multiplier;
        }

        // Create cart item
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'special_instructions' => $request->special_instructions,
            'selected_toppings' => $selectedToppings,
            'unit_price' => $unitPrice,
            'pizza_size_id' => $request->pizza_size_id,
        ]);

        // Reduce stock
        $product->decrement('stock', $request->quantity);

        return response()->json([
            'message' => 'Item added to cart',
            'cart_item' => $cartItem->load('product', 'size')
        ], 201);
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'special_instructions' => 'nullable|string',
        ]);

        // Check if cart item belongs to user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $product = $cartItem->product;
        $oldQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity ?? $oldQuantity;

        // Check if product is in stock for increased quantity
        if ($newQuantity > $oldQuantity) {
            $additionalQuantity = $newQuantity - $oldQuantity;
            if ($product->stock < $additionalQuantity) {
                return response()->json(['message' => 'Not enough stock available'], 422);
            }
            
            // Reduce stock for additional quantity
            $product->decrement('stock', $additionalQuantity);
        } elseif ($newQuantity < $oldQuantity) {
            // Return stock for decreased quantity
            $returnQuantity = $oldQuantity - $newQuantity;
            $product->increment('stock', $returnQuantity);
        }

        $cartItem->update($request->only(['quantity', 'special_instructions']));

        return response()->json([
            'message' => 'Cart item updated',
            'cart_item' => $cartItem->load('product', 'size')
        ]);
    }

    public function removeItem(Request $request, CartItem $cartItem)
    {
        // Check if cart item belongs to user
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Return stock
        $product = $cartItem->product;
        $product->increment('stock', $cartItem->quantity);

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request)
    {
        $cart = $request->user()->cart;
        
        if ($cart) {
            // Return stock for all items
            foreach ($cart->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
            
            // Delete all items
            $cart->items()->delete();
        }

        return response()->json(['message' => 'Cart cleared']);
    }
}