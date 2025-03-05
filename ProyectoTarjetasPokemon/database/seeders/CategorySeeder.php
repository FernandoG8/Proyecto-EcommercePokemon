<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create([
            'name' => 'Pizzas',
            'description' => 'Delicious pizzas',
            'slug' => Str::slug('Pizzas'),
        ]);

        Category::create([
            'name' => 'Beverages',
            'description' => 'Refreshing drinks',
            'slug' => Str::slug('Beverages'),
        ]);

        Category::create([
            'name' => 'Desserts',
            'description' => 'Sweet treats',
            'slug' => Str::slug('Desserts'),
        ]);
    }
}