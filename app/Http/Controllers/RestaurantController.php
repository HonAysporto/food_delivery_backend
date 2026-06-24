<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    $request->validate([
        'name' => 'required|string|max:255',
    'address' => 'required|string',
    'phone' => 'required|string|max:20',
    'description' => 'required|string',
    'image' => 'nullable|string',
    ]);

    $restaurant = Restaurant::create([
         'owner_id' => auth()->id(),
    'name' => $request->name,
    'address' => $request->address,
    'phone' => $request->phone,
    'description' => $request->description,
    'image' => $request->image,
    'rating' => 0,
    ]);

    return response()->json([
        'message' => 'Restaurant created successfully',
        'restaurant' => $restaurant
    ]);
}

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
