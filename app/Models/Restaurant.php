<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    public function categories() {
    return $this->hasMany(Category::class);
}

public function foods() {
    return $this->hasMany(Food::class);
}
}
