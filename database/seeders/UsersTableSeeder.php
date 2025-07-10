<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@shop.com',
            'password' => Hash::make('password123'),
            'phone_number' => '1234567890',
            'picture' => null,
            'user_type' => 'admin',
        ]);
        
        // Create sales users
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@shop.com',
            'password' => Hash::make('password123'),
            'phone_number' => '2345678901',
            'picture' => null,
            'user_type' => 'sales',
        ]);
        
        User::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane@shop.com',
            'password' => Hash::make('password123'),
            'phone_number' => '3456789012',
            'picture' => null,
            'user_type' => 'sales',
        ]);
        
        User::create([
            'name' => 'Bob Johnson',
            'username' => 'bobjohnson',
            'email' => null, // Example with null email
            'password' => Hash::make('password123'),
            'phone_number' => '4567890123',
            'picture' => null,
            'user_type' => 'sales',
        ]);
    }
}
