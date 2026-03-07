<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillable = ['opname_date', 'shift', 'user_id'];

    protected $casts = [
        'opname_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    // Helper: Get total items count
    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }

    // Helper: Check if has difference
    public function hasDifference($systemStocks)
    {
        foreach ($this->items as $item) {
            $systemQty = $systemStocks[$item->raw_material_id] ?? 0;
            if (abs($item->qty - $systemQty) > 0.01) {
                return true;
            }
        }
        return false;
    }
}