<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'restaurant_id' => 'required',
            'rating' => 'required|min:1|max:5',
            'comment' => 'nullable',
        ]);

        $alreadyReviewed = Review::where(
            'order_id',
            $request->order_id
        )->where(
            'user_id',
            auth()->id()
        )->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'message' => 'Already reviewed'
            ], 422);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'order_id' => $request->order_id,
            'restaurant_id' => $request->restaurant_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review);
    }

public function restaurantReviews($id)
{
    $reviews = \App\Models\Review::with('user')
        ->where('restaurant_id', $id)
        ->latest()
        ->get();

    $average = \App\Models\Review::where('restaurant_id', $id)
        ->avg('rating');

    $count = \App\Models\Review::where('restaurant_id', $id)
        ->count();

    return response()->json([
        'reviews' => $reviews,
        'average_rating' => round($average, 1),
        'total_reviews' => $count,
    ]);
}
}