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
            
            // Consulta base
            $query = Order::with(['items' => function($q) {
                $q->select(
                    'id',
                    'order_id',
                    'product_name',
                    'quantity',
                    'unit_price',
                    'size_name'
                );
            }]);
            
            // Filtrar por usuario actual (no admin)
            $query->where('user_id', $user->id);
            
            // Aplicar filtros si existen
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            if ($request->has('date')) {
                $query->whereDate('created_at', $request->date);
            }
            
            // Obtener pedidos ordenados por fecha más reciente
            $orders = $query->orderBy('created_at', 'desc')->get();
            
            // Transformar los datos para la respuesta
            $formattedOrders = $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_amount' => $order->total_amount,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                    'items' => $order->items->map(function($item) {
                        return [
                            'product_name' => $item->product_name,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                            'size_name' => $item->size_name,
                            'subtotal' => $item->quantity * $item->unit_price
                        ];
                    })
                ];
            });
    
            return response()->json([
                'data' => $formattedOrders
            ]);
    
        } catch (Exception $e) {
            \Log::error('Error en index de ordenes: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al obtener los pedidos',
                'message' => $e->getMessage()
            ], 500);

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
        try {
            $user = $request->user();
            $cart = $user->cart;

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['message' => 'El carrito está vacío.'], 422);
            }

        $cart = $user->cart;
        Log::info('Carrito:', ['cart' => $cart ? 'encontrado' : 'no encontrado']);

        if (!$cart) {
            return response()->json(['message' => 'No se encontró el carrito.'], 422);
        }

        Log::info('Items en carrito:', ['count' => $cart->items->count()]);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'El carrito está vacío.'], 422);
        }

        // Get cart total before creating order
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

        Log::info('Orden creada:', ['order_id' => $order->id]);

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

        Log::info('Items de orden creados');

        // Clear cart after creating order
        $cart->items()->delete();
        Log::info('Carrito limpiado');

        $order->load('items');
        
        return response()->json([
            'message' => 'Pedido realizado exitosamente.',
            'order' => $order
        ], 201);

    } catch (QueryException $e) {
        Log::error('Error en store de ordenes (QueryException): ' . $e->getMessage());
        return response()->json(['error' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
    } catch (Exception $e) {
        Log::error('Error general en store de ordenes: ' . $e->getMessage());
        return response()->json(['error' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
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