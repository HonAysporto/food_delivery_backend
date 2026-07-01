<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\Review;
use App\Models\AdminWallet;
use App\Models\Rider;



class AdminController extends Controller
{

public function analytics()
{
    $wallet = AdminWallet::first();

    return response()->json([

        'total_users' => User::count(),

        'total_restaurants' => Restaurant::count(),

        'total_riders' => Rider::count(),

        'total_foods' => Food::count(),

        'total_orders' => Order::count(),

        'total_reviews' => Review::count(),

        'wallet' => $wallet,
        'total_platform_revenue' => Order::sum('total'),

    ]);
}

    public function restaurants()
{
    return Restaurant::with('owner')
        ->latest()
        ->get();
}



public function wallet()
{
    return AdminWallet::first();
}





public function suspendRestaurant(
    Restaurant $restaurant
)
{
    $restaurant->update([
        'status' => 'suspended'
    ]);

    return response()->json([
        'message' => 'Restaurant suspended'
    ]);
}




public function activateRestaurant(
    Restaurant $restaurant
)
{
    $restaurant->update([
        'status' => 'active'
    ]);

    return response()->json([
        'message' => 'Restaurant activated'
    ]);
}

public function users()
{
    return User::latest()->get();
}

public function suspendUser(
    User $user
)
{
    $user->update([
        'status' => 'suspended'
    ]);

    return response()->json([
        'message' => 'User suspended'
    ]);
}


public function activateUser(
    User $user
)
{
    $user->update([
        'status' => 'active'
    ]);

    return response()->json([
        'message' => 'User activated'
    ]);
}

public function orders()
{
    return Order::with([
        'user',
        'items.food'
    ])
    ->latest()
    ->get();
}

public function showOrder(
    Order $order
)
{
    return $order->load([
        'user',
        'items.food'
    ]);
}



}