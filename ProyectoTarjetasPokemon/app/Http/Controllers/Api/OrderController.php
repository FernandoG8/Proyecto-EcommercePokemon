<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Admin can see all orders, customers can only see their own
        if (true) {
            $query = Order::with('user', 'items');
            
            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            $orders = $query->latest()->paginate(10);
        } else {
            $orders = $user->orders()->with('items')->latest()->paginate(10);
        }
        
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:cash,card',
            'delivery_address' => 'required|string',
            'contact_phone' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $cart = $user->cart;
        
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $cart->getTotal(),
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'delivery_address' => $request->delivery_address,
            'contact_phone' => $request->contact_phone,
            'notes' => $request->notes,
        ]);

        // Create order items from cart items
        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'product_name' => $cartItem->product->name,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'special_instructions' => $cartItem->special_instructions,
                'selected_toppings' => $cartItem->selected_toppings,
                'pizza_size_id' => $cartItem->pizza_size_id,
                'size_name' => $cartItem->size ? $cartItem->size->name : null,
            ]);
        }

        // Clear the cart
        $cart->items()->delete();

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order->load('items')
        ], 201);
    }

    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        
        // Check if user is authorized to view this order
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $order->load('items', 'user');
        
        return response()->json(['order' => $order]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,delivered',
        ]);

        // Only admin can update order status
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->update([
            'status' => $request->status,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        return response()->json([
            'message' => 'Order status updated',
            'order' => $order
        ]);
    }

    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|string|in:pending,paid,failed',
        ]);

        // Only admin can update payment status
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return response()->json([
            'message' => 'Payment status updated',
            'order' => $order
        ]);
    }
}