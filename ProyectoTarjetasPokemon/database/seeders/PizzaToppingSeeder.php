<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PizzaTopping;

class PizzaToppingSeeder extends Seeder
{
    public function run()
    {
        PizzaTopping::create(['name' => 'Extra Cheese', 'price' => 1.50, 'is_active' => true]);
        PizzaTopping::create(['name' => 'Pepperoni', 'price' => 1.75, 'is_active' => true]);
        PizzaTopping::create(['name' => 'Mushrooms', 'price' => 1.25, 'is_active' => true]);
    }
}