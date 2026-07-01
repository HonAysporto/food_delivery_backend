<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [   'user_id',
    'restaurant_id',
        'total',
        'status',
        'delivery_name',
        'delivery_phone',
        'delivery_address',
        'rider_id',];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function rider()
{
    return $this->belongsTo(User::class,'rider_id');
}

public function restaurant()
{
    return $this->belongsTo(Restaurant::class);
}
}
