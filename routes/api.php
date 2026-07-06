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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use Illuminate\Support\Facades\DB;



/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/auth/google',
    [GoogleAuthController::class, 'redirect']
);

Route::get(
    '/auth/google/callback',
    [GoogleAuthController::class, 'callback']
);

Route::get('/restaurants', function () {

    return \App\Models\Restaurant::where(
        'status',
        'active'
    )
    ->withCount([
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

    $restaurant =
        \App\Models\Restaurant::find($id);

    if (
        !$restaurant ||
        $restaurant->status !== 'active'
    ) {
        return response()->json([
            'message' =>
            'Restaurant unavailable'
        ], 404);
    }

    return \App\Models\Food::where(
        'restaurant_id',
        $id
    )
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

      Route::get('/profile', [ProfileController::class, 'show']);

       Route::put('/profile', [ProfileController::class, 'update']);

    Route::put('/profile/password', [ProfileController::class, 'changePassword']);

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

    Route::post(
        '/owner/restaurants',
        [RestaurantController::class, 'store']
    );

    Route::get(
    '/owner/wallet',
    [RestaurantController::class, 'wallet']
);

});


Route::middleware([
    'auth:sanctum',
    'role:owner',
    'restaurant.active'
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Restaurant
    |--------------------------------------------------------------------------
    */

    Route::get(
    '/owner/restaurant',
    [RestaurantController::class, 'ownerRestaurant']
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

    Route::put(
    '/owner/foods/{food}/availability',
    [FoodController::class, 'toggleAvailability']
);

    Route::get('/owner/analytics', [OrderController::class, 'analytics']);

        Route::get('/owner/profile', [ProfileController::class, 'show']);
    Route::put('/owner/profile', [ProfileController::class, 'update']);
    Route::put('/owner/profile/password', [ProfileController::class, 'changePassword']);

    Route::put(
    '/owner/restaurant/status',
    [RestaurantController::class, 'toggleStatus']
);
});



// Admin Routes


Route::middleware([
    'auth:sanctum',
    'admin'
])->prefix('admin')->group(function () {

    Route::get(
        '/analytics',
        [AdminController::class, 'analytics']
    );

       Route::get(
        '/restaurants',
        [AdminController::class, 'restaurants']
    );

    Route::put(
        '/restaurants/{restaurant}/suspend',
        [AdminController::class, 'suspendRestaurant']
    );

    Route::put(
        '/restaurants/{restaurant}/activate',
        [AdminController::class, 'activateRestaurant']
    );

    Route::get(
    '/wallet',
    [AdminController::class, 'wallet']
);

    Route::get(
    '/users',
    [AdminController::class, 'users']
);

Route::put(
    '/users/{user}/suspend',
    [AdminController::class, 'suspendUser']
);

Route::put(
    '/users/{user}/activate',
    [AdminController::class, 'activateUser']
);

Route::get(
    '/orders',
    [AdminController::class, 'orders']
);

Route::get(
    '/orders/{order}',
    [AdminController::class, 'showOrder']
);

});





Route::get('/db-test', function () {
    return response()->json([
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'config_connection' => config('database.default'),
        'DB_HOST' => env('DB_HOST'),
        'DB_DATABASE' => env('DB_DATABASE'),
    ]);
});
// Rider

Route::middleware(['auth:sanctum'])->prefix('rider')->group(function () {

    Route::post(
        '/profile',
        [RiderController::class, 'store']
    );

        Route::get(
'/available-orders',
[RiderController::class,'availableOrders']
);

Route::get(
'/my-orders',
[RiderController::class,'myOrders']
);

Route::put(
'/accept-order/{order}',
[RiderController::class,'acceptOrder']
);

Route::put(
'/deliver-order/{order}',
[RiderController::class,'deliverOrder']
);

Route::get(
    '/wallet',
    [RiderController::class, 'wallet']
);
});