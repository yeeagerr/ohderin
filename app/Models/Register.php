<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Register extends Model
{
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sessions()
    {
        return $this->hasMany(RegisterSession::class);
    }

    public function activeSession()
    {
        return $this->hasOne(RegisterSession::class)->where('status', 'open')->latestOfMany();
    }
}
