<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'order_number',
        'order_type',
        'total',
        'paid_amount',
        'change_amount',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'refunded_by',
        'payment_method',
        'user_id',
        'register_id',
        'register_session_id',
        'status',
        'table_id',
        'split_bill_group'
    ];

    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function register()
    {
        return $this->belongsTo(Register::class);
    }

    public function registerSession()
    {
        return $this->belongsTo(RegisterSession::class, 'register_session_id');
    }

    public function refundedBy()
    {
        return $this->belongsTo(User::class, 'refunded_by');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
