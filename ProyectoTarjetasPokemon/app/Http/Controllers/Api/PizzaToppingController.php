<?php

namespace App\Http\Controllers\Api;

use App\Models\PizzaTopping;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PizzaToppingController extends Controller
{
    public function index()
    {
        $toppings = PizzaTopping::where('is_active', true)->get();
        return response()->json(['toppings' => $toppings]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pizza_toppings',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $topping = PizzaTopping::create($request->all());

        return response()->json(['topping' => $topping, 'message' => 'Topping created successfully'], 201);
    }

    public function show(PizzaTopping $pizzaTopping)
    {
        return response()->json(['topping' => $pizzaTopping]);
    }

    public function update(Request $request, PizzaTopping $pizzaTopping)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:pizza_toppings,name,' . $pizzaTopping->id,
            'price' => 'sometimes|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $pizzaTopping->update($request->all());

        return response()->json(['topping' => $pizzaTopping, 'message' => 'Topping updated successfully']);
    }

    public function destroy(PizzaTopping $pizzaTopping)
    {
        $pizzaTopping->delete();
        return response()->json(['message' => 'Topping deleted successfully']);
    }
}