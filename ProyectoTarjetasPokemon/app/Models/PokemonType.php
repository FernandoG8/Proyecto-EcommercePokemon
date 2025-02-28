<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonType extends Model
{
    use HasFactory;

    protected $table = 'pokemon_types';

    protected $fillable = [
        'product_id',
        'type_name',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
