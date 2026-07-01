<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'restaurant_percentage',
        'rider_percentage',
        'admin_percentage'
    ];
}