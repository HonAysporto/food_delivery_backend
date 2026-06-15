<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

     Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders', [OrderController::class, 'index']);

});




Route::get('/restaurants', function () {
    return \App\Models\Restaurant::withCount('foods')->get();
});

// 🍔 All foods (with relations)
Route::get('/foods', function () {
    return \App\Models\Food::with(['restaurant', 'category'])->get();
});

// 📂 Categories per restaurant
Route::get('/restaurants/{id}/categories', function ($id) {
    return \App\Models\Category::where('restaurant_id', $id)->get();
});

// 🍕 Foods per restaurant (VERY IMPORTANT for Glovo-style UI)
Route::get('/restaurants/{id}/foods', function ($id) {
    return \App\Models\Food::where('restaurant_id', $id)
        ->with(['category'])
        ->get();
});