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
        // Obtener gerencias principales para asignar usuarios
        $desarrolloUrbano = Gerencia::where('codigo', 'GDUR')->first();
        $desarrolloEconomico = Gerencia::where('codigo', 'GDEL')->first();
        $desarrolloSocial = Gerencia::where('codigo', 'GDS')->first();
        $serviciosPublicos = Gerencia::where('codigo', 'GSPMA')->first();
        $administracionFinanzas = Gerencia::where('codigo', 'GAF')->first();
        $planeamiento = Gerencia::where('codigo', 'GPP')->first();
        $recursosHumanos = Gerencia::where('codigo', 'GRH')->first();
        $asesoriaJuridica = Gerencia::where('codigo', 'GAJ')->first();
        $administracionTributaria = Gerencia::where('codigo', 'GAT')->first();
        $seguridadCiudadana = Gerencia::where('codigo', 'GSCD')->first();
        $tecnologias = Gerencia::where('codigo', 'GTI')->first();
        $controlInterno = Gerencia::where('codigo', 'GCI')->first();
        
        // Obtener subgerencias para asignar responsables
        $subPlaneamientoUrbano = Gerencia::where('codigo', 'SGPUR')->first();
        $subObrasPrivadas = Gerencia::where('codigo', 'SGOPL')->first();
        $subCatastro = Gerencia::where('codigo', 'SGC')->first();
        $subHabilitaciones = Gerencia::where('codigo', 'SGHT')->first();
        $subControlUrbano = Gerencia::where('codigo', 'SGCUSO')->first();
        $subOrdenamientoTerritorial = Gerencia::where('codigo', 'OOT')->first();

        // Verificar que al menos una gerencia exista
        if (!$desarrolloUrbano && !$desarrolloEconomico) {
            $this->command->error('No se encontraron gerencias. Ejecute primero GerenciasSeeder.');
            return;
        }

        $this->command->info("Gerencias encontradas para asignar usuarios:");
        $this->command->info("- Desarrollo Urbano: " . ($desarrolloUrbano ? $desarrolloUrbano->nombre : 'No encontrada'));
        $this->command->info("- Desarrollo Económico: " . ($desarrolloEconomico ? $desarrolloEconomico->nombre : 'No encontrada'));
        $this->command->info("- Desarrollo Social: " . ($desarrolloSocial ? $desarrolloSocial->nombre : 'No encontrada'));

        // Usuarios del sistema
        $users = [
            // Super Administrador
            [
                'name' => 'Super Administrador',
                'email' => 'superadmin@muni.gob.pe',
                'password' => Hash::make('password123'),
                'gerencia_id' => $administracionFinanzas->id ?? null,
                'role' => 'superadministrador'
            ],
            
            // Jefe de Desarrollo Urbano y Rural
            [
                'name' => 'Roberto Sánchez Urbano',
                'email' => 'jefe.urbano@muni.gob.pe',
                'password' => Hash::make('urbano123'),
                'gerencia_id' => $desarrolloUrbano->id ?? null,
                'role' => 'jefe_gerencia'
            ],
            
            // Jefe de Desarrollo Económico
            [
                'name' => 'María González Económico',
                'email' => 'jefe.economico@muni.gob.pe',
                'password' => Hash::make('economico123'),
                'gerencia_id' => $desarrolloEconomico->id ?? null,
                'role' => 'jefe_gerencia'
            ],
            
            // Jefe de Desarrollo Social
            [
                'name' => 'Carmen López Social',
                'email' => 'jefe.social@muni.gob.pe',
                'password' => Hash::make('social123'),
                'gerencia_id' => $desarrolloSocial->id ?? null,
                'role' => 'jefe_gerencia'
            ],
            
            // Funcionario de Desarrollo Urbano
            [
                'name' => 'Ana Pérez Obras',
                'email' => 'ana.obras@muni.gob.pe',
                'password' => Hash::make('obras123'),
                'gerencia_id' => $desarrolloUrbano->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Responsables de Subgerencias de Desarrollo Urbano
            [
                'name' => 'Carlos Mendoza Planeamiento',
                'email' => 'carlos.planeamiento@muni.gob.pe',
                'password' => Hash::make('plan123'),
                'gerencia_id' => $subPlaneamientoUrbano->id ?? null,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Laura Ruiz Obras Privadas',
                'email' => 'laura.obrasprivadas@muni.gob.pe',
                'password' => Hash::make('obras123'),
                'gerencia_id' => $subObrasPrivadas->id ?? null,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Diego Vargas Catastro',
                'email' => 'diego.catastro@muni.gob.pe',
                'password' => Hash::make('catastro123'),
                'gerencia_id' => $subCatastro->id ?? null,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Patricia Flores Habilitaciones',
                'email' => 'patricia.habilitaciones@muni.gob.pe',
                'password' => Hash::make('habilit123'),
                'gerencia_id' => $subHabilitaciones->id ?? null,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Miguel Ángel Control Urbano',
                'email' => 'miguel.controlurbano@muni.gob.pe',
                'password' => Hash::make('control123'),
                'gerencia_id' => $subControlUrbano->id ?? null,
                'role' => 'funcionario'
            ],
            [
                'name' => 'Sandra Jiménez Ordenamiento',
                'email' => 'sandra.ordenamiento@muni.gob.pe',
                'password' => Hash::make('orden123'),
                'gerencia_id' => $subOrdenamientoTerritorial->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Desarrollo Económico (Licencias)
            [
                'name' => 'Pedro Ramírez Licencias',
                'email' => 'pedro.licencias@muni.gob.pe',
                'password' => Hash::make('licencias456'),
                'gerencia_id' => $desarrolloEconomico->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Funcionarios de Servicios Públicos
            [
                'name' => 'Luis Torres Servicios',
                'email' => 'luis.servicios@muni.gob.pe',
                'password' => Hash::make('servicios123'),
                'gerencia_id' => $serviciosPublicos->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Administración Tributaria
            [
                'name' => 'Isabel Herrera Rentas',
                'email' => 'isabel.rentas@muni.gob.pe',
                'password' => Hash::make('rentas123'),
                'gerencia_id' => $administracionTributaria->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Seguridad Ciudadana
            [
                'name' => 'Ricardo Morales Seguridad',
                'email' => 'ricardo.seguridad@muni.gob.pe',
                'password' => Hash::make('seguridad123'),
                'gerencia_id' => $seguridadCiudadana->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Funcionario de Tecnologías
            [
                'name' => 'Mónica Castro Sistemas',
                'email' => 'monica.sistemas@muni.gob.pe',
                'password' => Hash::make('sistemas123'),
                'gerencia_id' => $tecnologias->id ?? null,
                'role' => 'funcionario'
            ],
            
            // Supervisor General
            [
                'name' => 'Alberto Ramos Supervisor',
                'email' => 'alberto.supervisor@muni.gob.pe',
                'password' => Hash::make('supervisor123'),
                'gerencia_id' => $planeamiento->id ?? null,
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
