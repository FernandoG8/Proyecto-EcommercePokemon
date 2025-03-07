<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Pizzas
        Product::create([
            'name' => 'Queso',
            'slug' => 'queso-pizza',
            'description' => 'Deliciosa pizza con extra quesito derretido, perfecta para los amantes del queso.',
            'price' => 60,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Hawaiana',
            'slug' => 'hawaiana-pizza',
            'description' => 'Jugoso jamón, dulce piña y mucho quesito. ¡Una combinación tropical!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Peperoni',
            'slug' => 'peperoni-pizza',
            'description' => 'Peperoni picante y mucho quesito. ¡Un clásico que nunca falla!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Mexicana',
            'slug' => 'mexicana-pizza',
            'description' => 'Jamón, frijoles, chorizo, elote, cebolla, jitomate, jalapeños, aguacate y quesito. ¡Un sabor bien mexicano!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Al Pastor',
            'slug' => 'al-pastor-pizza',
            'description' => 'Carne al pastor, pimiento, cebolla, piña, jalapeño y quesito. ¡Un toque de sabor único!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Maximo',
            'slug' => 'maximo-pizza',
            'description' => 'Jamón, pepperoni, pimiento, cebolla, champiñones y quesito. ¡El máximo sabor en cada bocado!',
            'price' => 75,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Chocking',
            'slug' => 'chocking-pizza',
            'description' => 'Jamón, salchicha, chorizo, champiñones, cebolla, aceitunas, pimiento y quesito. ¡Te dejará sin palabras!',
            'price' => 75,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Choriqueso',
            'slug' => 'choriqueso-pizza',
            'description' => 'Chorizo asadito y extra quesito derretido. ¡Un sabor intenso!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Doggo',
            'slug' => 'doggo-pizza',
            'description' => 'Salchicha, tocino crujiente, tomate, jalapeño, cebolla, catsup, mostaza y quesito. ¡Explosión de sabores!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Chicago',
            'slug' => 'chicago-pizza',
            'description' => 'Salchicha, chorizo, salami y mucho quesito. ¡Para los más atrevidos!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Mister Pep',
            'slug' => 'mister-pep-pizza',
            'description' => 'Peperoni, champiñones frescos y quesito derretido. ¡Un clásico irresistible!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Jarocha',
            'slug' => 'jarocha-pizza',
            'description' => 'Atún, champiñones, cebolla, jalapeño, tomate y quesito. ¡Un sabor único!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Napolitana',
            'slug' => 'napolitana-pizza',
            'description' => 'Jamón, salchicha, tocino, pimiento morrón, champiñones y quesito. ¡Una fiesta de sabores!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Vegetariana',
            'slug' => 'vegetariana-pizza',
            'description' => 'Piña, champiñones, aceitunas, pimiento, cebolla, jitomate, elote y quesito. ¡Saludable y deliciosa!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Meat',
            'slug' => 'meat-pizza',
            'description' => 'Jamón, salchicha, tocino, chorizo y quesito. ¡Para los amantes de la carne!',
            'price' => 70,
            'stock' => 50,
            'category_id' => 1,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        // Hotdogs
        Product::create([
            'name' => 'Clásico',
            'slug' => 'clasico-hotdog',
            'description' => 'Salchicha super jumbo envuelta en tocino, con tomate, cebolla, chile en vinagre y un toque de mostaza y catsup. ¡Un clásico que nunca pasa de moda!',
            'price' => 59,
            'stock' => 50,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Peperoni',
            'slug' => 'peperoni-hotdog',
            'description' => 'Salchicha super jumbo envuelta en tocino, con peperoni y queso mozzarella derretido. ¡Ideal para los amantes del queso!',
            'price' => 76,
            'stock' => 50,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'BBQ',
            'slug' => 'bbq-hotdog',
            'description' => 'Salchicha super jumbo con cebolla caramelizada, salsa BBQ y queso mozzarella. ¡Un sabor ahumado y dulce!',
            'price' => 76,
            'stock' => 50,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'El Gobernante',
            'slug' => 'el-gobernante-hotdog',
            'description' => 'Salchicha super jumbo envuelta en tocino, con cebolla caramelizada, aguacate y queso mozzarella. ¡Un manjar digno de un rey!',
            'price' => 76,
            'stock' => 50,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Tamtoc',
            'slug' => 'tamtoc-hotdog',
            'description' => 'Salchicha super jumbo con piña asada, cebolla caramelizada y rodajas de jalapeño natural. ¡Un toque tropical y picante!',
            'price' => 69,
            'stock' => 50,
            'category_id' => 2,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        // Hamburguesas
        Product::create([
            'name' => 'Clásica',
            'slug' => 'clasica-hamburguesa',
            'description' => 'Hamburguesa de arracherra con tocino crujiente, queso derretido, tomate, lechuga y cebolla. ¡Un clásico que nunca falla!',
            'price' => 85,
            'stock' => 50,
            'category_id' => 3,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Hawaiana',
            'slug' => 'hawaiana-hamburguesa',
            'description' => 'Hamburguesa de arracherra con tocino, piña, jamón, tomate, lechuga y cebolla. ¡Un toque dulce y salado!',
            'price' => 85,
            'stock' => 50,
            'category_id' => 3,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Mexicana',
            'slug' => 'mexicana-hamburguesa',
            'description' => 'Hamburguesa de arracherra con tocino, piña, jamón, tomate, lechuga y cebolla. ¡Un sabor bien mexicano!',
            'price' => 85,
            'stock' => 50,
            'category_id' => 3,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        // Papas
        Product::create([
            'name' => 'A la Francesa',
            'slug' => 'a-la-francesa-papas',
            'description' => 'Papas a la francesa crujientes y doradas. ¡Perfectas para acompañar cualquier platillo!',
            'price' => 45,
            'stock' => 45,
            'category_id' => 4,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Gajo',
            'slug' => 'gajo-papas',
            'description' => 'Papas gajo bien sazonadas y horneadas. ¡Un toque rústico y delicioso!',
            'price' => 55,
            'stock' => 50,
            'category_id' => 4,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Coca-Cola',     //6 tacos ,  5 bebidas,  4 papas, 3 hamburguesas, 2 hotdogs, 1 pizza
            'slug' => 'cocacola-bebidas',
            'description' => 'Refresco de cola frío y burbujeante. ¡El complemento perfecto para tu comida!',
            'price' => 20,
            'stock' => 50,
            'category_id' => 5,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Sprite',
            'slug' => 'sprite-bebidas',
            'description' => 'Refresco de limón frío y burbujeante. ¡El toque cítrico que necesitas!',
            'price' => 20,
            'stock' => 50,
            'category_id' => 5,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Fanta',
            'slug' => 'fanta-bebidas',
            'description' => 'Refresco de naranja frío y burbujeante. ¡El sabor tropical que te encanta!',
            'price' => 20,
            'stock' => 50,
            'category_id' => 5,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Agua Natural',
            'slug' => 'agua-bebidas',
            'description' => 'Agua purificada fría y refrescante. ¡La opción más saludable!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 5,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Jamaica',
            'slug' => 'jamaica-bebidas',
            'description' => 'Agua de jamaica fría y refrescante. ¡El sabor tradicional de México!',
            'price' => 20,
            'stock' => 50,
            'category_id' => 5,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        // Tacos

        product::create([
            'name' => 'Pastor',
            'slug' => 'pastor-tacos',
            'description' => 'Taco de carne al pastor con piña, cebolla y cilantro. ¡El sabor tradicional de México!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Bistec',
            'slug' => 'bistec-tacos',
            'description' => 'Taco de bistec con cebolla, cilantro y limón. ¡El sabor de la carne asada!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Carnitas',
            'slug' => 'carnitas-tacos',
            'description' => 'Taco de carnitas con cebolla, cilantro y limón. ¡El sabor de la carne de cerdo!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Barbacoa',
            'slug' => 'barbacoa-tacos',
            'description' => 'Taco de barbacoa con cebolla, cilantro y limón. ¡El sabor de la carne de res!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Chorizo',
            'slug' => 'chorizo-tacos',
            'description' => 'Taco de chorizo con cebolla, cilantro y limón. ¡El sabor del chorizo mexicano!',
            'price' => 15,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);

        product::create([
            'name' => 'Campechano',
            'slug' => 'campechano-tacos',
            'description' => 'Taco de bistec y chorizo con cebolla, cilantro y limón. ¡El sabor de la carne asada y el chorizo!',
            'price' => 20,
            'stock' => 50,
            'category_id' => 6,
            'image' => 'path/to/image.jpg',
            'is_active' => true,
        ]);


            
    }
}