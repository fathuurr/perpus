<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Create a library staff
        User::create([
            'name' => 'Library Staff',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas_perpus',
        ]);

        // Create some students
        User::factory()->count(10)->create([
            'role' => 'siswa',
            'balance' => 10000,
        ]);
    }
}
