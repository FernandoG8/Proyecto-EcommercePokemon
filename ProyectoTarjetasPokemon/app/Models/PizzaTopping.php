<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaTopping extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'is_active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_topping', 'pizza_topping_id', 'product_id');
    }
}