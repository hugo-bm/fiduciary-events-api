<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        User::create([
        'name' => 'Admin User',
        'api_key' => Hash::make("Uma chave X qualquer"),
        'role' => 'ADMIN',
        'is_active' => true,
        ]);
    }
}
