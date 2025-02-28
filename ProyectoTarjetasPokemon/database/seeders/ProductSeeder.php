<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Margherita Pizza',
            'description' => 'Classic pizza with tomato sauce and mozzarella cheese.',
            'price' => 8.99,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Coke',
            'description' => 'Refreshing cola drink.',
            'price' => 1.99,
            'stock' => 100,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);
    }
}