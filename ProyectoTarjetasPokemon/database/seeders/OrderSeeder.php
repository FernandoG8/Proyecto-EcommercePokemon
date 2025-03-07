<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $order = Order::create([
            'user_id' => 2, // Assuming this is the customer user
            'order_number' => 'ORD-123456',
            'total_amount' => 10.99,
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'delivery_address' => '456 Customer Ave',
            'contact_phone' => '0987654321',
            'notes' => 'Please deliver quickly.',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1, // Assuming this is the Margherita Pizza
            'product_name' => 'Margherita Pizza',
            'quantity' => 1,
            'unit_price' => 8.99,
            'special_instructions' => null,
            'pizza_size_id' => 2, // Assuming this is Medium
        ]);
    }
}