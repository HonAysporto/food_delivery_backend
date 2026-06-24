<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
   protected $fillable = [
    'owner_id',
    'name',
    'image',
    'address',
    'phone',
    'description',
    'rating',
];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}
}