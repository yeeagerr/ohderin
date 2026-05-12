<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function hasPermissionTo($permission)
    {
        // Allow super admin to have all permissions implicitly if we define 'Super Admin'
        // But let's just check array
        if ($this->name === 'Super Admin') {
            return true;
        }
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }
}
