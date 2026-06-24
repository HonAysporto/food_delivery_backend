<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories belonging to the owner's restaurant
     */
    public function index()
    {
        $restaurant = Restaurant::where(
            'owner_id',
            auth()->id()
        )->first();

        if (!$restaurant) {
            return response()->json([]);
        }

        return Category::where(
            'restaurant_id',
            $restaurant->id
        )->latest()->get();
    }

    /**
     * Create category
     */
    public function store(Request $request)
    {
        $restaurant = Restaurant::where(
            'owner_id',
            auth()->id()
        )->first();

        if (!$restaurant) {
            return response()->json([
                'message' => 'Restaurant not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}