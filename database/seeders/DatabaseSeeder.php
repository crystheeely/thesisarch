<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::create([
            'full_name' => 'Test User', // ✅ Changed from 'name' to 'full_name'
            'id_number' => '2025-1234', // ✅ Add a test ID number
            'email' => 'test@example.com',
            'password' => Hash::make('password'), // ✅ Hash the password properly
            'role' => 'student', // ✅ Add a role
        ]);

        $this->call(AdminUserSeeder::class); 


    }
}
