<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id', 
        'product_id', 
        'quantity', 
        'special_instructions', 
        'selected_toppings', 
        'unit_price',
        'pizza_size_id'
    ];

    protected $casts = [
        'selected_toppings' => 'array',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(PizzaSize::class, 'pizza_size_id');
    }
}