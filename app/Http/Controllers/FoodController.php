<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function ownerFoods()
{
    $restaurant = Restaurant::where(
        'owner_id',
        auth()->id()
    )->first();

    if (!$restaurant) {
        return response()->json([]);
    }

return Food::with('category')
    ->where(
        'restaurant_id',
        $restaurant->id
    )
    ->latest()
    ->get();

}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $restaurant = Restaurant::where(
        'owner_id',
        auth()->id()
    )->first();

 $request->validate([
    'name' => 'required',
    'price' => 'required|numeric',
    'description' => 'required',
    'category_id' => 'required|exists:categories,id',
    'image' => 'nullable|string',
]);

    $food = Food::create([
        'restaurant_id' => $restaurant->id,
        'category_id' => $request->category_id,
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'image' => $request->image,
    ]);

    return response()->json($food);
}

    /**
     * Display the specified resource.
     */
    public function show(Food $food)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Food $food)
    {
        //
    }

 public function update(Request $request, Food $food)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required',
        'category_id' => 'required',
    ]);

    $food->update($request->all());

    return response()->json([
        'message' => 'Food updated successfully',
        'food' => $food
    ]);
}

public function destroy(Food $food)
{
    $food->delete();

    return response()->json([
        'message' => 'Food deleted successfully'
    ]);
}
}
