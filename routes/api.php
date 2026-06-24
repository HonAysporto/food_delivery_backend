<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/restaurants', function () {

    return \App\Models\Restaurant::withCount([
        'foods',
        'reviews'
    ])
    ->withAvg(
        'reviews',
        'rating'
    )
    ->get();

});

Route::get('/foods', function () {
    return \App\Models\Food::with(['restaurant', 'category'])->get();
});

Route::get('/restaurants/{id}/categories', function ($id) {
    return \App\Models\Category::where('restaurant_id', $id)->get();
});

Route::get('/restaurants/{id}/foods', function ($id) {
    return \App\Models\Food::where('restaurant_id', $id)
        ->with('category')
        ->get();
});

Route::get(
    '/restaurants/{id}/reviews',
    [ReviewController::class, 'restaurantReviews']
);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

     Route::get('/user', [AuthController::class, 'user']);

     Route::post(
    '/reviews',
    [ReviewController::class, 'store']
);

});

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'role:customer'
])->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders', [OrderController::class, 'index']);

});

/*
|--------------------------------------------------------------------------
| OWNER ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    'role:owner'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Restaurant
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/owner/restaurants',
        [RestaurantController::class, 'store']
    );

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/owner/categories',
        [CategoryController::class, 'index']
    );

    Route::post(
        '/owner/categories',
        [CategoryController::class, 'store']
    );

    Route::put(
        '/owner/categories/{category}',
        [CategoryController::class, 'update']
    );

    Route::delete(
        '/owner/categories/{category}',
        [CategoryController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Foods
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/owner/foods',
        [FoodController::class, 'ownerFoods']
    );

    Route::post(
        '/owner/foods',
        [FoodController::class, 'store']
    );

    Route::put(
        '/owner/foods/{food}',
        [FoodController::class, 'update']
    );

    Route::delete(
        '/owner/foods/{food}',
        [FoodController::class, 'destroy']
    );

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/owner/orders',
        [OrderController::class, 'ownerOrders']
    );

    Route::put(
        '/owner/orders/{order}',
        [OrderController::class, 'updateStatus']
    );

    Route::get('/owner/analytics', [OrderController::class, 'analytics']);

        Route::get('/owner/profile', [ProfileController::class, 'show']);
    Route::put('/owner/profile', [ProfileController::class, 'update']);
    Route::put('/owner/profile/password', [ProfileController::class, 'changePassword']);
});