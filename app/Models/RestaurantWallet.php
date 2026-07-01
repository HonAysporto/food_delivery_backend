<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantWallet extends Model
{
   protected $fillable = [
    'restaurant_id',
    'balance',
    'total_earned',
    'total_withdrawn'
];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}