<?php
// 2023_01_01_000011_add_size_id_to_cart_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('pizza_size_id')->nullable()->constrained();
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['pizza_size_id']);
            $table->dropColumn('pizza_size_id');
        });
    }
};