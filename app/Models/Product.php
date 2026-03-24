<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'image',
        'is_package',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function recipe()
    {
        return $this->hasOne(Recipe::class);
    }

    public function packageItems()
    {
        return $this->hasMany(ProductPackage::class, 'package_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Helper method untuk cek apakah produk bisa dijadikan komponen paket
    public function canBePackageComponent()
    {
        return !$this->is_package;
    }

    // Hitung total harga komponen paket
    public function getPackageTotalPriceAttribute()
    {
        if (!$this->is_package) {
            return $this->price;
        }

        return $this->packageItems->sum(function ($item) {
            return $item->product->price * $item->qty;
        });
    }

    // Hitung keuntungan paket (jika harga paket lebih mahal dari total komponen)
    public function getPackageProfitAttribute()
    {
        if (!$this->is_package) {
            return 0;
        }
        return $this->price - $this->package_total_price;
    }
}