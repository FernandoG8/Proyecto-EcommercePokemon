<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Obtiene los pedidos del usuario autenticado o todos si es administrador.
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->isAdmin()) {
                $query = Order::with(['user', 'items']);

                if ($request->has('status') && $request->status !== 'all') {
                    $query->where('status', $request->status);
                }
            } else {
                $query = Order::where('user_id', $user->id)
                    ->with(['items' => function ($q) {
                        $q->select(
                            'id',
                            'order_id',
                            'product_name',
                            'quantity',
                            'unit_price',
                            'size_name'
                        );
                    }]);

                if ($request->has('status') && $request->status !== 'all') {
                    $query->where('status', $request->status);
                }
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(10);

            return response()->json($orders);
        } catch (Exception $e) {
            Log::error('Error en index de órdenes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener los pedidos'], 500);
        }
    }

    /**
     * Crea un nuevo pedido a partir del carrito del usuario.
     */
    public function store(Request $request)
    {
        Log::info('Datos recibidos en store:', $request->all());

        try {
            $request->validate([
                'payment_method' => 'required|string|in:efectivo,tarjeta',
                'delivery_address' => 'required|string',
                'contact_phone' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $user = $request->user();
            Log::info('Usuario:', ['id' => $user->id]);

            $cart = $user->cart;

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['message' => 'El carrito está vacío.'], 422);
            }

            $total = $cart->getTotal();
            Log::info('Total del carrito:', ['total' => $total]);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'delivery_address' => $request->delivery_address,
                'contact_phone' => $request->contact_phone,
                'notes' => $request->notes,
            ]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->unit_price,
                    'special_instructions' => $cartItem->special_instructions ?? null,
                    'pizza_size_id' => $cartItem->pizza_size_id ?? null,
                    'size_name' => $cartItem->size ? $cartItem->size->name : null,
                ]);
            }

            $cart->items()->delete();
            Log::info('Carrito limpiado');

            $order->load('items');

            return response()->json([
                'message' => 'Pedido realizado exitosamente.',
                'order' => $order
            ], 201);
        } catch (QueryException $e) {
            Log::error('Error en store de órdenes (QueryException): ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el pedido'], 500);
        } catch (Exception $e) {
            Log::error('Error general en store de órdenes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al procesar el pedido'], 500);
        }
    }

    /**
     * Muestra un pedido específico.
     */
    public function show(Request $request, Order $order)
    {
        try {
            $user = $request->user();

            if (!$user->isAdmin() && $order->user_id !== $user->id) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            $order->load('items', 'user');
            return response()->json(['order' => $order]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el pedido.'], 500);
        }
    }

    /**
     * Actualiza el estado de un pedido (solo administradores).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,completed,cancelled,delivered',
        ]);

        try {
            if (!$request->user()->isAdmin()) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            $order->update([
                'status' => $request->status,
                'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
            ]);

            return response()->json([
                'message' => 'Estado del pedido actualizado.',
                'order' => $order
            ]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al actualizar el estado del pedido.'], 500);
        }
    }

    /**
     * Actualiza el estado de pago de un pedido (solo administradores).
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|string|in:pending,paid,failed',
        ]);

        try {
            if (!$request->user()->isAdmin()) {
                return response()->json(['message' => 'No autorizado.'], 403);
            }

            $order->update([
                'payment_status' => $request->payment_status,
            ]);

            return response()->json([
                'message' => 'Estado de pago actualizado.',
                'order' => $order
            ]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Error al actualizar el estado de pago.'], 500);
        }
    }
}
