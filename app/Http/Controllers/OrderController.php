<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function store(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'total' => 'required|numeric',
    ]);

    $order = Order::create([
        'user_id' => $request->user()->id,
        'total' => $request->total,
        'status' => 'pending',
    ]);

    foreach ($request->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'food_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);
    }

    return response()->json([
        'message' => 'Order placed successfully',
        'order' => $order
    ]);
}
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    return Order::where('user_id', $request->user()->id)
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
