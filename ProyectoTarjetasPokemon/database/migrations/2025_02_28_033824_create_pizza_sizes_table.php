<?php
// 2023_01_01_000010_create_pizza_sizes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pizza_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Small, Medium, Large, etc.
            $table->decimal('price_multiplier', 5, 2)->default(1.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pizza_sizes');
    }
};