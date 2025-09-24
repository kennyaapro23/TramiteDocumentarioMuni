<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gerencia;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener gerencias para asignar usuarios
        $alcaldia = Gerencia::where('codigo', 'ALC')->first();
        $gerenciaMunicipal = Gerencia::where('codigo', 'GM')->first();
        $desarrolloUrbano = Gerencia::where('codigo', 'SGDU')->first();
        $licencias = Gerencia::where('codigo', 'SGLA')->first();
        $serviciosPublicos = Gerencia::where('codigo', 'SGSP')->first();
        $fiscalizacion = Gerencia::where('codigo', 'SGF')->first();
        $rentas = Gerencia::where('codigo', 'SGR')->first();
        $registroCivil = Gerencia::where('codigo', 'SGRC')->first();
        $participacion = Gerencia::where('codigo', 'SGPC')->first();
        $seguridad = Gerencia::where('codigo', 'SGSC')->first();
        $ambiente = Gerencia::where('codigo', 'SGMA')->first();
        $transportes = Gerencia::where('codigo', 'SGT')->first();

        // Verificar que las gerencias principales existan
        if (!$gerenciaMunicipal) {
            $this->command->error('Gerencia Municipal no encontrada. Ejecute primero GerenciasSeeder.');
            return;
        }

        $this->command->info("Gerencias encontradas para asignar usuarios:");
        $this->command->info("- Alcaldía: " . ($alcaldia ? $alcaldia->nombre : 'No encontrada'));
        $this->command->info("- Gerencia Municipal: " . ($gerenciaMunicipal ? $gerenciaMunicipal->nombre : 'No encontrada'));
        $this->command->info("- Desarrollo Urbano: " . ($desarrolloUrbano ? $desarrolloUrbano->nombre : 'No encontrada'));

        // Usuarios del sistema
        $users = [
            // Super Administrador
            [
                'name' => 'Super Administrador',
                'email' => 'superadmin@muni.gob.pe',
                'password' => Hash::make('password123'),
                'gerencia_id' => $gerenciaMunicipal->id,
                'role' => 'superadministrador'
            ],
            
            // Alcalde
            [
                'name' => 'Carlos Mendoza Alcalde',
                'email' => 'alcalde@muni.gob.pe',
                'password' => Hash::make('alcalde123'),
                'gerencia_id' => $alcaldia->id,
                'role' => 'administrador'
            ],
            
            // Gerente Municipal
            [
                'name' => 'María González Gerente',
                'email' => 'gerente.municipal@muni.gob.pe',
                'password' => Hash::make('gerente123'),
                'gerencia_id' => $gerenciaMunicipal->id,
                'role' => 'administrador'
            ],
            
            // Jefe de Desarrollo Urbano
            [
                'name' => 'Roberto Sánchez Urbano',
                'email' => 'jefe.urbano@muni.gob.pe',
                'password' => Hash::make('urbano123'),
                'gerencia_id' => $desarrolloUrbano->id,
                'role' => 'jefe_gerencia'
            ],
            
            // Funcionarios de Licencias
            [
                'name' => 'Ana Pérez Licencias',
                'email' => 'ana.licencias@muni.gob.pe',
                'password' => Hash::make('licencias123'),
                'gerencia_id' => $licencias->id,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Pedro Ramírez Licencias',
                'email' => 'pedro.licencias@muni.gob.pe',
                'password' => Hash::make('licencias456'),
                'gerencia_id' => $licencias->id,
                'role' => 'funcionario_junior'
            ],
            
            // Funcionarios de Servicios Públicos
            [
                'name' => 'Carmen López Servicios',
                'email' => 'carmen.servicios@muni.gob.pe',
                'password' => Hash::make('servicios123'),
                'gerencia_id' => $serviciosPublicos->id,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Fiscalización
            [
                'name' => 'Luis Torres Fiscalización',
                'email' => 'luis.fiscalizacion@muni.gob.pe',
                'password' => Hash::make('fiscal123'),
                'gerencia_id' => $fiscalizacion->id,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Rentas
            [
                'name' => 'Isabel Herrera Rentas',
                'email' => 'isabel.rentas@muni.gob.pe',
                'password' => Hash::make('rentas123'),
                'gerencia_id' => $rentas->id,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Registro Civil
            [
                'name' => 'Fernando Vega Registro',
                'email' => 'fernando.registro@muni.gob.pe',
                'password' => Hash::make('registro123'),
                'gerencia_id' => $registroCivil->id,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Seguridad Ciudadana
            [
                'name' => 'Ricardo Morales Seguridad',
                'email' => 'ricardo.seguridad@muni.gob.pe',
                'password' => Hash::make('seguridad123'),
                'gerencia_id' => $seguridad->id,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Transportes
            [
                'name' => 'Mónica Castro Transportes',
                'email' => 'monica.transportes@muni.gob.pe',
                'password' => Hash::make('transportes123'),
                'gerencia_id' => $transportes->id,
                'role' => 'funcionario'
            ],
            
            // Supervisor
            [
                'name' => 'Alberto Ramos Supervisor',
                'email' => 'alberto.supervisor@muni.gob.pe',
                'password' => Hash::make('supervisor123'),
                'gerencia_id' => $gerenciaMunicipal->id,
                'role' => 'supervisor'
            ],
            
            // Ciudadanos para pruebas
            [
                'name' => 'Juan Ciudadano Pérez',
                'email' => 'juan.ciudadano@gmail.com',
                'password' => Hash::make('ciudadano123'),
                'gerencia_id' => null,
                'role' => 'ciudadano'
            ],
            [
                'name' => 'Rosa Martínez Ciudadana',
                'email' => 'rosa.martinez@hotmail.com',
                'password' => Hash::make('ciudadana456'),
                'gerencia_id' => null,
                'role' => 'ciudadano'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            // Usar firstOrCreate para evitar duplicados
            $user = User::firstOrCreate(
                ['email' => $userData['email']], // Buscar por email
                $userData // Datos para crear si no existe
            );
            
            // Asignar rol al usuario si no lo tiene
            if (!$user->hasAnyRole($role)) {
                $user->assignRole($role);
            }
        }

        $this->command->info('Usuarios creados exitosamente');
        $this->command->info('Total de usuarios: ' . User::count());
        $this->command->info('Usuarios con roles asignados');
    }
}
