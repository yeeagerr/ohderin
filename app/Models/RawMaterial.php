<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = ['name','unit','minimal_stock','cost'];

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
