<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BasicUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios básicos para login
        $users = [
            [
                'name' => 'Admin Usuario',
                'email' => 'admin@muni.gob.pe',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test Usuario',
                'email' => 'test@muni.gob.pe', 
                'password' => Hash::make('test123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Usuario Demo',
                'email' => 'demo@muni.gob.pe',
                'password' => Hash::make('demo123'),
                'email_verified_at' => now(),
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Usuarios básicos creados:');
        $this->command->info('- admin@muni.gob.pe / admin123');
        $this->command->info('- test@muni.gob.pe / test123'); 
        $this->command->info('- demo@muni.gob.pe / demo123');
    }
}