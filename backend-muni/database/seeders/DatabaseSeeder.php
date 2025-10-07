<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Iniciando la siembra de datos del sistema de trámites municipales...');
        $this->command->info('');

        // Ejecutar seeders en orden específico debido a dependencias
        $this->call([
            GerenciasSeeder::class,            // Primero: gerencias (necesario para usuarios)
            RolesAndPermissionsSeeder::class,  // Segundo: roles y permisos del sistema
            UsersSeeder::class,                // Tercero: usuarios adicionales del sistema
            ProceduresSeeder::class,           // Cuarto: procedimientos TUPA (necesita gerencias)
            WorkflowRulesSeeder::class,        // Quinto: reglas de flujo (necesita gerencias y usuarios)
            WorkflowsSeeder::class,            // Sexto: workflows completos (necesita gerencias y usuarios)
            ExpedientesSeeder::class,          // Séptimo: expedientes (necesita usuarios y procedimientos)
            TipoDocumentoSeeder::class,        // Octavo: tipos de documentos
            TipoTramiteSeeder::class,          // Noveno: tipos de trámites (necesita gerencias y TipoDocumento)
            AsignarWorkflowsSeeder::class,     // Décimo: asignar workflows a tipos de trámite
        ]);

        $this->command->info('');
        $this->command->info('✅ ¡Datos de prueba creados exitosamente!');
        $this->command->info('');
        $this->command->info('🎯 RESUMEN DE DATOS CREADOS:');
        $this->command->info('📋 Roles: 7 (superadministrador, administrador, jefe_gerencia, funcionario, funcionario_junior, ciudadano, supervisor)');
        $this->command->info('🔑 Permisos: 59 permisos específicos del sistema en español (incluye gestión de flujos)');
        $this->command->info('🏢 Gerencias: 15 gerencias con estructura jerárquica');
        $this->command->info('👥 Usuarios: 5 usuarios de prueba con diferentes roles y asignaciones');
        $this->command->info('📋 Procedimientos TUPA: 11 procedimientos de diferentes gerencias');
        $this->command->info('🔄 Reglas de flujo: 12+ reglas automáticas para asignación de trámites');
        $this->command->info('⚙️ Etapas de flujo: Etapas secuenciales por gerencia configuradas');
        $this->command->info('📁 Expedientes: 5 expedientes en diferentes estados');
        $this->command->info('');
        $this->command->info('🔐 CREDENCIALES PRINCIPALES:');
        $this->command->info('Super Admin: superadmin@muni.gob.pe / password123');
        $this->command->info('Administrador: admin@muni.gob.pe / password123');
        $this->command->info('Jefe Gerencia: jefe@muni.gob.pe / password123');
        $this->command->info('Funcionario: funcionario@muni.gob.pe / password123');
        $this->command->info('Ciudadano: ciudadano@email.com / password123');
        $this->command->info('');
        $this->command->info('🧪 CASOS DE PRUEBA INCLUIDOS:');
        $this->command->info('✓ Usuarios con diferentes niveles de acceso');
        $this->command->info('✓ Expedientes vencidos para testing de alertas');
        $this->command->info('✓ Expedientes en todos los estados posibles');
        $this->command->info('✓ Notificaciones y logs de auditoría');
        $this->command->info('✓ Estructura jerárquica de gerencias');
        $this->command->info('✓ Procedimientos TUPA con diferentes costos y plazos');
        $this->command->info('✓ Sistema de roles sin Mesa de Partes - todo automático');
        $this->command->info('✓ Reglas de flujo automático para asignación de trámites');
        $this->command->info('✓ Etapas secuenciales por gerencia configuradas');
        $this->command->info('✓ Gestión de expedientes web 100% automática');
        $this->command->info('');
        $this->command->info('🚀 ¡Sistema listo para trámites web completamente automáticos!');
        $this->command->info('🌐 Los ciudadanos crean expedientes vía web');
        $this->command->info('⚡ Sistema asigna automáticamente a gerencias según tipo de trámite');
        $this->command->info('🔄 Cada gerencia procesa según sus etapas secuenciales definidas');
        $this->command->info('✅ Aprobaciones secuenciales: cada etapa debe completarse para continuar');
    }
}
