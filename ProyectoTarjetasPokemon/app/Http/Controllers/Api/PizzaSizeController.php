<?php

namespace App\Http\Controllers\Api;

use App\Models\PizzaSize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PizzaSizeController extends Controller
{
    public function index()
    {
        $sizes = PizzaSize::where('is_active', true)->get();
        return response()->json(['sizes' => $sizes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pizza_sizes',
            'price_multiplier' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $size = PizzaSize::create($request->all());

        return response()->json(['size' => $size, 'message' => 'Size created successfully'], 201);
    }

    public function show(PizzaSize $pizzaSize)
    {
        return response()->json(['size' => $pizzaSize]);
    }

    public function update(Request $request, PizzaSize $pizzaSize)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:pizza_sizes,name,' . $pizzaSize->id,
            'price_multiplier' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $pizzaSize->update($request->all());

        return response()->json(['size' => $pizzaSize, 'message' => 'Size updated successfully']);
    }

    public function destroy(PizzaSize $pizzaSize)
    {
        $pizzaSize->delete();
        return response()->json(['message' => 'Size deleted successfully']);
    }
}