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
            RolesAndPermissionsSeeder::class,  // Primero: roles y permisos
            GerenciasSeeder::class,            // Segundo: gerencias
            UsersSeeder::class,                // Tercero: usuarios
            ProceduresSeeder::class,           // Cuarto: procedimientos TUPA (necesita gerencias)
            WorkflowRulesSeeder::class,        // Quinto: reglas de flujo (necesita gerencias y usuarios)
            WorkflowStepsSeeder::class,        // Sexto: etapas de flujo por gerencia
            ExpedientesSeeder::class,          // Séptimo: expedientes (necesita usuarios y procedimientos)
        ]);

        $this->command->info('');
        $this->command->info('✅ ¡Datos de prueba creados exitosamente!');
        $this->command->info('');
        $this->command->info('🎯 RESUMEN DE DATOS CREADOS:');
        $this->command->info('📋 Roles: 7 (super_admin, admin, jefe_gerencia, funcionario, funcionario_junior, ciudadano, supervisor)');
        $this->command->info('🔑 Permisos: 50+ permisos específicos del sistema (incluye gestión de flujos)');
        $this->command->info('🏢 Gerencias: 15 gerencias con estructura jerárquica');
        $this->command->info('👥 Usuarios: 15+ usuarios con diferentes roles y asignaciones');
        $this->command->info('📋 Procedimientos TUPA: 11 procedimientos de diferentes gerencias');
        $this->command->info('🔄 Reglas de flujo: 12+ reglas automáticas para asignación de trámites');
        $this->command->info('⚙️ Etapas de flujo: Etapas secuenciales por gerencia configuradas');
        $this->command->info('📁 Expedientes: 5 expedientes en diferentes estados');
        $this->command->info('');
        $this->command->info('🔐 CREDENCIALES PRINCIPALES:');
        $this->command->info('Super Admin: superadmin@muni.gob.pe / password123');
        $this->command->info('Alcalde: alcalde@muni.gob.pe / alcalde123');
        $this->command->info('Gerente Municipal: gerente.municipal@muni.gob.pe / gerente123');
        $this->command->info('Ciudadano Test: juan.ciudadano@gmail.com / ciudadano123');
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
