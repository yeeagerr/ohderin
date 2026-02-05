<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    protected $fillable = ['recipe_id','raw_material_id','qty'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
