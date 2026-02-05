<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['raw_material_id','qty','price','purchase_date'];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
