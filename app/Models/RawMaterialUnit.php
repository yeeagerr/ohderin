<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialUnit extends Model
{
    protected $fillable = ['raw_material_id', 'name', 'ratio'];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
