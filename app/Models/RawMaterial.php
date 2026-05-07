<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = ['name','unit','stock','minimal_stock','cost'];

    public function quantityToBaseUnit($quantity, $unitId = null): float
    {
        $ratio = 1;

        if ($unitId) {
            $unit = $this->units()->whereKey($unitId)->first();
            $ratio = $unit ? (float) $unit->ratio : 1;
        }

        return (float) $quantity * $ratio;
    }

    public function priceToBaseUnit($price, $unitId = null): float
    {
        $ratio = 1;

        if ($unitId) {
            $unit = $this->units()->whereKey($unitId)->first();
            $ratio = $unit ? (float) $unit->ratio : 1;
        }

        return $ratio > 0 ? (float) $price / $ratio : (float) $price;
    }

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function units()
    {
        return $this->hasMany(RawMaterialUnit::class);
    }

}
