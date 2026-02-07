<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $cashierRole = Role::where('name', 'Cashier')->first();

        // Admin User
        User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]
        );

        // Cashier User
        User::firstOrCreate(
        ['email' => 'cashier@example.com'],
        [
            'name' => 'Cashier User',
            'password' => Hash::make('password'),
            'role_id' => $cashierRole->id,
        ]
        );
    }
}
