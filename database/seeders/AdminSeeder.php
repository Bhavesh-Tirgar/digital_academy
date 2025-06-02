<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'digitalacademy@gmail.com'], // Unique identifier
            [
                'name' => 'Admin',
                'password' => Hash::make('stoic@123'), // Securely hash password
                'role_id' => 1, // Ensure this is the admin role
            ]
        );
    }
}
