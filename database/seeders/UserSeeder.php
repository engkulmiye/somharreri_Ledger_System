<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'type' => 'admin',
            'is_active' => true,

        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('123456789'),
            'type' => 'manager',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Normal User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('123456789'),
            'type' => 'user',
            'is_active' => true,

        ]);
    }
}
