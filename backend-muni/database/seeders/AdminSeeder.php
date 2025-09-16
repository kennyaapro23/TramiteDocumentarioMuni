<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos básicos
        $permissions = [
            // Usuarios
            'crear_usuarios',
            'editar_usuarios', 
            'eliminar_usuarios',
            'ver_usuarios',
            
            // Roles y Permisos
            'crear_roles',
            'editar_roles',
            'eliminar_roles',
            'ver_roles',
            'asignar_permisos',
            
            // Expedientes
            'crear_expedientes',
            'editar_expedientes',
            'eliminar_expedientes',
            'ver_expedientes',
            'asignar_expedientes',
            
            // Gerencias
            'gestionar_gerencias',
            'ver_gerencias',
            
            // Administración general
            'administrar_sistema',
            'ver_estadisticas',
            'gestionar_configuracion',
        ];

        // Crear permisos para ambos guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api'
            ]);
        }

        // Crear roles básicos para ambos guards
        $adminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);
        $adminRoleApi = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'api'
        ]);

        $jefeRole = Role::firstOrCreate([
            'name' => 'Jefe de Gerencia', 
            'guard_name' => 'web'
        ]);
        $jefeRoleApi = Role::firstOrCreate([
            'name' => 'Jefe de Gerencia', 
            'guard_name' => 'api'
        ]);

        $funcionarioRole = Role::firstOrCreate([
            'name' => 'Funcionario',
            'guard_name' => 'web'
        ]);
        $funcionarioRoleApi = Role::firstOrCreate([
            'name' => 'Funcionario',
            'guard_name' => 'api'
        ]);

        $ciudadanoRole = Role::firstOrCreate([
            'name' => 'Ciudadano',
            'guard_name' => 'web'
        ]);
        $ciudadanoRoleApi = Role::firstOrCreate([
            'name' => 'Ciudadano',
            'guard_name' => 'api'
        ]);

        // Asignar todos los permisos al Super Admin (ambos guards)
        $adminRole->syncPermissions(Permission::where('guard_name', 'web')->get());
        $adminRoleApi->syncPermissions(Permission::where('guard_name', 'api')->get());

        // Asignar permisos específicos a Jefe de Gerencia
        $jefePermisos = [
            'crear_expedientes',
            'editar_expedientes',
            'ver_expedientes',
            'asignar_expedientes',
            'ver_usuarios',
            'ver_gerencias',
            'ver_estadisticas'
        ];
        
        $jefeRole->syncPermissions(Permission::whereIn('name', $jefePermisos)->where('guard_name', 'web')->get());
        $jefeRoleApi->syncPermissions(Permission::whereIn('name', $jefePermisos)->where('guard_name', 'api')->get());

        // Asignar permisos básicos a Funcionario
        $funcionarioPermisos = [
            'crear_expedientes',
            'editar_expedientes',
            'ver_expedientes',
            'ver_gerencias'
        ];
        
        $funcionarioRole->syncPermissions(Permission::whereIn('name', $funcionarioPermisos)->where('guard_name', 'web')->get());
        $funcionarioRoleApi->syncPermissions(Permission::whereIn('name', $funcionarioPermisos)->where('guard_name', 'api')->get());

        // Permisos básicos para Ciudadano
        $ciudadanoPermisos = [
            'crear_expedientes',
            'ver_expedientes'
        ];
        
        $ciudadanoRole->syncPermissions(Permission::whereIn('name', $ciudadanoPermisos)->where('guard_name', 'web')->get());
        $ciudadanoRoleApi->syncPermissions(Permission::whereIn('name', $ciudadanoPermisos)->where('guard_name', 'api')->get());

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@municipalidad.com'],
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('admin123'),
                'activo' => true,
                'telefono' => '999999999',
                'cargo' => 'Administrador de Sistema',
                'email_verified_at' => now(),
            ]
        );

        // Asignar rol de Super Admin
        $admin->assignRole('Super Admin');

        // Crear algunos usuarios de ejemplo
        $jefe = User::firstOrCreate(
            ['email' => 'jefe@municipalidad.com'],
            [
                'name' => 'Jefe de Gerencia General',
                'password' => Hash::make('jefe123'),
                'activo' => true,
                'telefono' => '888888888',
                'cargo' => 'Jefe de Gerencia',
                'email_verified_at' => now(),
            ]
        );
        $jefe->assignRole('Jefe de Gerencia');

        $funcionario = User::firstOrCreate(
            ['email' => 'funcionario@municipalidad.com'],
            [
                'name' => 'Funcionario Municipal',
                'password' => Hash::make('funcionario123'),
                'activo' => true,
                'telefono' => '777777777',
                'cargo' => 'Funcionario',
                'email_verified_at' => now(),
            ]
        );
        $funcionario->assignRole('Funcionario');

        $ciudadano = User::firstOrCreate(
            ['email' => 'ciudadano@municipalidad.com'],
            [
                'name' => 'Juan Pérez Ciudadano',
                'password' => Hash::make('ciudadano123'),
                'activo' => true,
                'telefono' => '666666666',
                'cargo' => 'Ciudadano',
                'email_verified_at' => now(),
            ]
        );
        $ciudadano->assignRole('Ciudadano');

        $this->command->info('✅ AdminSeeder completado:');
        $this->command->info('- Creados ' . Permission::count() . ' permisos');
        $this->command->info('- Creados ' . Role::count() . ' roles');
        $this->command->info('- Creados usuarios de ejemplo con credenciales:');
        $this->command->info('  * Admin: admin@municipalidad.com / admin123');
        $this->command->info('  * Jefe: jefe@municipalidad.com / jefe123');
        $this->command->info('  * Funcionario: funcionario@municipalidad.com / funcionario123');
        $this->command->info('  * Ciudadano: ciudadano@municipalidad.com / ciudadano123');
    }
}