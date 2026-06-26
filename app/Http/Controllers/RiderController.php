<?php

namespace App\Http\Controllers;
use App\Models\Rider;
use App\Models\Order;

use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'phone' => 'required',
        'vehicle_type' => 'required',
        'license_number' => 'nullable',
    ]);

    $rider = Rider::create([
        'user_id' => auth()->id(),
        'phone' => $request->phone,
        'vehicle_type' => $request->vehicle_type,
        'license_number' => $request->license_number,
    ]);

    return response()->json($rider);
}

public function availableOrders()
{
    return Order::where(
        'status',
        'ready_for_pickup'
    )
    ->whereNull('rider_id')
    ->with([
        'user',
        'items.food'
    ])
    ->latest()
    ->get();
}

public function acceptOrder(
    Order $order
)
{

    if ($order->rider_id) {

        return response()->json([
            'message'=>'Already accepted'
        ],400);

    }

    $order->update([

        'rider_id'=>auth()->id(),

        'status'=>'on_the_way'

    ]);

    return response()->json([
        'message'=>'Order accepted'
    ]);

}

public function myOrders()
{
    return Order::where(
        'rider_id',
        auth()->id()
    )
    ->with([
        'user',
        'items.food'
    ])
    ->latest()
    ->get();
}

public function deliverOrder(
    Order $order
)
{

    if($order->rider_id!=auth()->id()){

        return response()->json([
            'message'=>'Unauthorized'
        ],403);

    }

    $order->update([

        'status'=>'delivered'

    ]);

    return response()->json([
        'message'=>'Delivered'
    ]);

}
}
