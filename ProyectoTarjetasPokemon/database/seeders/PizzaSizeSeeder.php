<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PizzaSize;

class PizzaSizeSeeder extends Seeder
{
    public function run()
    {
        PizzaSize::create([
            'name' => 'Small', 
            'price_multiplier' => 1.0
        ]);
        
        PizzaSize::create([
            'name' => 'Medium', 
            'price_multiplier' => 1.5
        ]);
        
        PizzaSize::create([
            'name' => 'Large', 
            'price_multiplier' => 2.0
        ]);
    }
}