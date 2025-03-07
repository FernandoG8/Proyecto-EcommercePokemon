<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Pizzas',
                'description' => 'Nuestras deliciosas pizzas',
                'slug' => 'pizza',  // Cambiado para coincidir con los slugs de productos
                'is_active' => true
            ],
            [
                'name' => 'Hot Dogs',
                'description' => 'Los mejores hot dogs',
                'slug' => 'hotdog',  // Cambiado para coincidir con los slugs de productos
                'is_active' => true
            ],
            [
                'name' => 'Hamburguesas',
                'description' => 'Las mejores hamburguesas',
                'slug' => 'hamburguesa',  // Cambiado para coincidir con los slugs de productos
                'is_active' => true
            ],
            [
                'name' => 'Papas',
                'description' => 'Las mejores papas',
                'slug' => 'papas',
                'is_active' => true
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Refrescos y bebidas',
                'slug' => 'bebidas',
                'is_active' => true
            ],
            [
                'name' => 'Tacos',
                'description' => 'Los mejores tacos',
                'slug' => 'tacos',
                'is_active' => true
            ]

        ];
        //6 tacos ,  5 bebidas,  4 papas, 3 hamburguesas, 2 hotdogs, 1 pizza
    
        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}	