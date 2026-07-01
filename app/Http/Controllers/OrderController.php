<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class OrderController extends Controller
{

public function index(Request $request)
{
    return Order::where('user_id', auth()->id())
        ->with([
            'items.food'
        ])
        ->latest()
        ->get();
}

public function store(Request $request)

{
    $firstFood = \App\Models\Food::find(
    $request->items[0]['id']
);

$restaurantId = $firstFood->restaurant_id;
   
    try {
         
      $order = Order::create([

    'user_id'=>auth()->id(),

     'restaurant_id' => $restaurantId,

    'total'=>$request->total,

    'status'=>'pending',

    'delivery_name'=>$request->customer['fullName'],

    'delivery_phone'=>$request->customer['phone'],

    'delivery_address'=>$request->customer['address'],

]);

        foreach ($request->items as $item) {

        $food = \App\Models\Food::find(
    $item['id']
);

if (!$food->is_available) {

    return response()->json([
        'message' =>
        $food->name .
        ' is out of stock'
    ], 422);

}

            OrderItem::create([
                'order_id' => $order->id,
                'food_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

        }

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);

    }
}



public function ownerOrders()
{
    $restaurant = Restaurant::where(
        'owner_id',
        auth()->id()
    )->first();

    if (!$restaurant) {
        return response()->json([]);
    }

    return Order::whereHas(
        'items.food',
        function ($query) use ($restaurant) {
            $query->where(
                'restaurant_id',
                $restaurant->id
            );
        }
    )
    ->with([
        'user',
        'items.food'
    ])
    ->latest()
    ->get();
}


public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required'
    ]);

    $flow = [
        'pending' => 'accepted',
        'accepted' => 'preparing',
        'preparing' => 'ready_for_pickup',
        'ready_for_pickup' => 'on_the_way',
        'on_the_way' => 'delivered',

    ];

    if ($order->status === 'delivered') {
        return response()->json([
            'message' => 'Order already delivered'
        ], 422);
    }

    if (
        isset($flow[$order->status]) &&
        $flow[$order->status] !== $request->status
    ) {
        return response()->json([
            'message' => 'Invalid status transition'
        ], 422);
    }

    $order->update([
        'status' => $request->status
    ]);

    return response()->json([
        'message' => 'Status updated',
        'order' => $order
    ]);
}

public function analytics()
{
    $restaurant = Restaurant::where('owner_id', auth()->id())->first();

    if (!$restaurant) {
        return response()->json([
            'total_orders' => 0,
            'total_revenue' => 0,
            'total_foods' => 0,
            'pending_orders' => 0,
            'delivered_orders' => 0,
        ]);
    }

    $orders = Order::whereHas('items.food', function ($query) use ($restaurant) {
        $query->where('restaurant_id', $restaurant->id);
    })->get();

    return response()->json([
        'total_orders' => $orders->count(),
        'total_revenue' => ($orders->sum('total') - $orders->sum('total') * 0.05) - $orders->sum('total') * 0.05,
        'total_foods' => $restaurant->foods()->count(),
        'pending_orders' => $orders->where('status', 'pending')->count(),
        'delivered_orders' => $orders->where('status', 'delivered')->count(),
    ]);
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
  

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
