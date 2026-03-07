<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpnameItem extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'stock_opname_id', 
        'raw_material_id', 
        'qty'
    ];

    protected $casts = [
        'qty' => 'decimal:2',
    ];

    public function stockOpname()
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    // Helper: Calculate difference from system stock
    public function getDifference($systemStock)
    {
        return $this->qty - $systemStock;
    }
}