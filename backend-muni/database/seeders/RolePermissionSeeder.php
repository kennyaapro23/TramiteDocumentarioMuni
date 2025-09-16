<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos
        $permissions = [
            'registrar_expediente',
            'ver_expedientes',
            'derivar_expediente',
            'revision_tecnica',
            'revision_legal',
            'emitir_resolucion',
            'firma_resolucion_mayor',
            'notificar_expediente',
            'rechazar_expediente',
            'gestionar_usuarios',
            'gestionar_gerencias',
            'ver_estadisticas',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $roles = [
            'mesa_partes' => 'Mesa de Partes',
            'gerente_urbano' => 'Gerente Urbano',
            'inspector' => 'Inspector',
            'secretaria_general' => 'Secretaría General',
            'alcalde' => 'Alcalde',
            'admin' => 'Administrador del Sistema',
        ];

        foreach ($roles as $roleName => $roleDisplayName) {
            Role::create(['name' => $roleName]);
        }

        // Asignar permisos a roles
        $rolePermissions = [
            'mesa_partes' => [
                'registrar_expediente',
                'ver_expedientes',
                'derivar_expediente',
                'rechazar_expediente',
            ],
            'gerente_urbano' => [
                'ver_expedientes',
                'revision_tecnica',
                'emitir_resolucion',
                'ver_estadisticas',
            ],
            'inspector' => [
                'ver_expedientes',
                'revision_tecnica',
                'ver_estadisticas',
            ],
            'secretaria_general' => [
                'ver_expedientes',
                'revision_legal',
                'emitir_resolucion',
                'ver_estadisticas',
            ],
            'alcalde' => [
                'ver_expedientes',
                'firma_resolucion_mayor',
                'ver_estadisticas',
            ],
            'admin' => [
                'registrar_expediente',
                'ver_expedientes',
                'derivar_expediente',
                'revision_tecnica',
                'revision_legal',
                'emitir_resolucion',
                'firma_resolucion_mayor',
                'notificar_expediente',
                'rechazar_expediente',
                'gestionar_usuarios',
                'gestionar_gerencias',
                'ver_estadisticas',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::whereName($roleName)->first();
            if ($role) {
                foreach ($permissions as $permission) {
                    $permissionModel = Permission::whereName($permission)->first();
                    if ($permissionModel) {
                        $role->givePermissionTo($permissionModel);
                    }
                }
            }
        }
    }
}
