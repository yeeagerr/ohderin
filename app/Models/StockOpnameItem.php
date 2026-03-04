<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['stock_opname_id', 'raw_material_id', 'qty'];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }
}
