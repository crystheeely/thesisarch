<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create only if no admin exists
        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'full_name' => 'CpE Admin',
                'id_number' => 'ADMIN0001',
                'email' => 'cpe_admin@gmail.com',
                'password' => Hash::make('securepassword'),
                'role' => 'admin',
                'approved' => true,
            ]);
        }
        
    }
}
