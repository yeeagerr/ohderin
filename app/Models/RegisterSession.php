<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegisterSession extends Model
{
    protected $fillable = [
        'register_id',
        'opened_by',
        'closed_by',
        'opened_at',
        'opening_cash',
        'opening_note',
        'closed_at',
        'closing_cash',
        'total_transactions',
        'total_sales',
        'cash_difference',
        'session_summary',
        'status',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_cash' => 'decimal:2',
        'closing_cash' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'session_summary' => 'array',
    ];

    public function register()
    {
        return $this->belongsTo(Register::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'register_session_id');
    }
}
