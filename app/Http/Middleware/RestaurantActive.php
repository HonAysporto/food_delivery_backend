<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Restaurant;

class RestaurantActive
{
    public function handle($request, Closure $next)
    {
        $restaurant = Restaurant::where(
            'owner_id',
            auth()->id()
        )->first();

        if (
            $restaurant &&
            $restaurant->status === 'suspended'
        ) {
            return response()->json([
                'message' =>
                'Restaurant suspended by admin'
            ], 403);
        }

        return $next($request);
    }
}