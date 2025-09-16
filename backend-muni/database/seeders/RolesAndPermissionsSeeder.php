<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para el sistema de trámites municipales
        $permissions = [
            // Permisos de expedientes
            'ver_expedientes',
            'crear_expedientes',
            'editar_expedientes',
            'eliminar_expedientes',
            'derivar_expediente',
            'aprobar_expediente',
            'rechazar_expediente',
            'finalizar_expediente',
            'archivar_expediente',
            'subir_documento',
            'eliminar_documento',
            'ver_expedientes_todos', // Para ver expedientes de todas las gerencias
            
            // Permisos de usuarios
            'gestionar_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'eliminar_usuarios',
            'asignar_roles',
            'gestionar_permisos',
            'ver_usuarios_todos',
            
            // Permisos de gerencias
            'gestionar_gerencias',
            'crear_gerencias',
            'editar_gerencias',
            'eliminar_gerencias',
            'asignar_usuarios_gerencia',
            
            // Permisos de procedimientos TUPA
            'gestionar_procedimientos',
            'crear_procedimientos',
            'editar_procedimientos',
            'eliminar_procedimientos',
            
            // Permisos de reportes y estadísticas
            'ver_reportes',
            'exportar_datos',
            'ver_estadisticas_gerencia',
            'ver_estadisticas_sistema',
            
            // Permisos de configuración
            'configurar_sistema',
            'gestionar_respaldos',
            'ver_logs_sistema',
            
            // Permisos de notificaciones
            'enviar_notificaciones',
            'gestionar_notificaciones',
            
            // Permisos de pagos
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            
            // Permisos de quejas y reclamos
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            
            // Permisos de gestión de flujos de trabajo (para gerencias)
            'crear_reglas_flujo',
            'editar_reglas_flujo',
            'eliminar_reglas_flujo',
            'ver_reglas_flujo',
            'activar_desactivar_reglas',
            'crear_etapas_flujo',
            'editar_etapas_flujo',
            'eliminar_etapas_flujo',
            'ver_etapas_flujo',
            
            // Permisos de workflows personalizables
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'eliminar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows'
        ];

        // Crear todos los permisos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles y asignar permisos
        
        // ROL: Super Administrador
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all()); // Todos los permisos

        // ROL: Administrador
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'ver_expedientes',
            'crear_expedientes',
            'editar_expedientes',
            'derivar_expediente',
            'aprobar_expediente',
            'rechazar_expediente',
            'finalizar_expediente',
            'archivar_expediente',
            'subir_documento',
            'ver_expedientes_todos',
            'gestionar_usuarios',
            'crear_usuarios',
            'editar_usuarios',
            'asignar_roles',
            'gestionar_permisos',
            'ver_usuarios_todos',
            'gestionar_gerencias',
            'gestionar_procedimientos',
            'ver_reportes',
            'exportar_datos',
            'ver_estadisticas_gerencia',
            'ver_estadisticas_sistema',
            'enviar_notificaciones',
            'gestionar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'eliminar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows'
        ]);

        // ROL: Jefe de Gerencia
        $jefeGerencia = Role::firstOrCreate(['name' => 'jefe_gerencia', 'guard_name' => 'web']);
        $jefeGerencia->givePermissionTo([
            'ver_expedientes',
            'crear_expedientes',
            'editar_expedientes',
            'derivar_expediente',
            'aprobar_expediente',
            'rechazar_expediente',
            'finalizar_expediente',
            'subir_documento',
            'crear_usuarios',
            'editar_usuarios',
            'asignar_usuarios_gerencia',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'crear_reglas_flujo',
            'editar_reglas_flujo',
            'eliminar_reglas_flujo',
            'ver_reglas_flujo',
            'activar_desactivar_reglas',
            'crear_etapas_flujo',
            'editar_etapas_flujo',
            'eliminar_etapas_flujo',
            'ver_etapas_flujo',
            'gestionar_workflows',
            'crear_workflows',
            'editar_workflows',
            'ver_workflows',
            'activar_workflows',
            'clonar_workflows'
        ]);

        // ROL: Funcionario
        $funcionario = Role::firstOrCreate(['name' => 'funcionario', 'guard_name' => 'web']);
        $funcionario->givePermissionTo([
            'ver_expedientes',
            'crear_expedientes',
            'editar_expedientes',
            'derivar_expediente',
            'subir_documento',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas'
        ]);

        // ROL: Funcionario Junior
        $funcionarioJunior = Role::firstOrCreate(['name' => 'funcionario_junior', 'guard_name' => 'web']);
        $funcionarioJunior->givePermissionTo([
            'ver_expedientes',
            'editar_expedientes',
            'subir_documento',
            'enviar_notificaciones',
            'ver_pagos',
            'gestionar_quejas'
        ]);

        // ROL: Ciudadano
        $ciudadano = Role::firstOrCreate(['name' => 'ciudadano', 'guard_name' => 'web']);
        $ciudadano->givePermissionTo([
            'crear_expedientes',
            'ver_expedientes', // Solo sus propios expedientes
            'subir_documento', // Solo a sus expedientes
            'ver_pagos', // Solo sus pagos
            'gestionar_quejas' // Solo crear quejas sobre sus expedientes
        ]);

        // ROL: Supervisor
        $supervisor = Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        $supervisor->givePermissionTo([
            'ver_expedientes',
            'editar_expedientes',
            'derivar_expediente',
            'aprobar_expediente',
            'rechazar_expediente',
            'finalizar_expediente',
            'subir_documento',
            'ver_reportes',
            'ver_estadisticas_gerencia',
            'enviar_notificaciones',
            'gestionar_pagos',
            'confirmar_pagos',
            'ver_pagos',
            'gestionar_quejas',
            'responder_quejas',
            'escalar_quejas',
            'ver_workflows'
        ]);

        $this->command->info('Roles y permisos creados exitosamente');
        $this->command->info('Roles creados: super_admin, admin, jefe_gerencia, funcionario, funcionario_junior, ciudadano, supervisor');
        $this->command->info('Total de permisos: ' . count($permissions));
    }
}
