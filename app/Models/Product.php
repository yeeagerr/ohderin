<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name','category','price','is_package','is_active'
    ];

    public function recipe()
    {
        return $this->hasOne(Recipe::class);
    }

    public function packageItems()
    {
        return $this->hasMany(ProductPackage::class,'package_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
