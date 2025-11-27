<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Bikin Akun Admin Otomatis
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chewyt.com',
            'password' => Hash::make('password123'), // Passwordnya 'password123'
            'role' => 'admin',
        ]);
    }
}