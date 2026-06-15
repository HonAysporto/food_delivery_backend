<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function restaurant() {
    return $this->belongsTo(Restaurant::class);
}

public function foods() {
    return $this->hasMany(Food::class);
}
}
