<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@studio.com'],
            [
                'name' => 'Fotografer Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Akun Pengguna / Pembeli
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Pembeli Foto',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
    }
}
