<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario admin
        $admin = User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@municipalidad.com',
            'password' => Hash::make('admin123'),
            'estado' => 'activo',
            'gerencia_id' => 1, // Gerencia General
        ]);

        // Asignar rol admin
        $admin->assignRole('admin');

        // Sincronizar todos los permisos
        $allPermissions = Permission::all()->pluck('name')->toArray();
        $admin->syncPermissions($allPermissions);

        $this->command->info('Usuario admin creado exitosamente');
        $this->command->info('Email: admin@municipalidad.com');
        $this->command->info('Password: admin123');
    }
}
