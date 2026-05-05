<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    protected $fillable = ['name', 'type', 'category', 'price_adjustment', 'is_active'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_modifier')->withTimestamps();
    }
}
