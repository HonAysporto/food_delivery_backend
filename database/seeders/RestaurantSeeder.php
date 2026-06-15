<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
 use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Food;

class RestaurantSeeder extends Seeder
{
  

public function run(): void
{
    $kfc = Restaurant::create([
        'name' => 'KFC',
        'image' => 'kfc.jpg',
        'address' => 'Lagos',
        'rating' => 4.5,
    ]);

    $dominos = Restaurant::create([
        'name' => "Domino's",
        'image' => 'dominos.jpg',
        'address' => 'Lagos',
        'rating' => 4.2,
    ]);

    // Categories
    $burgers = Category::create([
        'restaurant_id' => $kfc->id,
        'name' => 'Burgers',
    ]);

    $pizza = Category::create([
        'restaurant_id' => $dominos->id,
        'name' => 'Pizza',
    ]);

    // Foods
    Food::create([
        'restaurant_id' => $kfc->id,
        'category_id' => $burgers->id,
        'name' => 'Zinger Burger',
        'description' => 'Spicy chicken burger',
        'price' => 2500,
        'image' => 'burger.jpg',
    ]);

    Food::create([
        'restaurant_id' => $dominos->id,
        'category_id' => $pizza->id,
        'name' => 'Pepperoni Pizza',
        'description' => 'Cheesy pizza',
        'price' => 5000,
        'image' => 'pizza.jpg',
    ]);
}
}
