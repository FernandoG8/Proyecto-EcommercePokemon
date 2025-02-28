<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Pizzas', 'description' => 'Delicious pizzas']);
        Category::create(['name' => 'Beverages', 'description' => 'Refreshing drinks']);
        Category::create(['name' => 'Desserts', 'description' => 'Sweet treats']);
    }
}