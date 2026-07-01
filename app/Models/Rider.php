<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'vehicle_type',
        'license_number',
        'is_available',
        'rating',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function wallet()
{
    return $this->hasOne(RiderWallet::class);
}


}
