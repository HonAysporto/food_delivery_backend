<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderWallet extends Model
{
    protected $fillable = [
    'rider_id',
    'balance',
    'total_earned',
    'total_withdrawn'
];

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }
}