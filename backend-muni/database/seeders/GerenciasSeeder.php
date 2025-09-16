<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gerencias principales
        $alcaldia = Gerencia::create([
            'nombre' => 'Alcaldía',
            'codigo' => 'ALC',
            'descripcion' => 'Despacho de Alcaldía - Máxima autoridad municipal',
            'tipo' => 'gerencia',
            'gerencia_padre_id' => null,
            'activo' => true,
            'orden' => 1,
        ]);

        $gerenciaMunicipal = Gerencia::create([
            'nombre' => 'Gerencia Municipal',
            'codigo' => 'GM',
            'descripcion' => 'Gerencia Municipal - Administración general',
            'tipo' => 'gerencia',
            'gerencia_padre_id' => $alcaldia->id,
            'activo' => true,
            'orden' => 2,
        ]);

        // Sub-gerencias
        $gerencias = [
            [
                'nombre' => 'Sub Gerencia de Servicios Públicos',
                'codigo' => 'SGSP',
                'descripcion' => 'Gestión de servicios públicos municipales',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 1,
            ],
            [
                'nombre' => 'Sub Gerencia de Desarrollo Urbano',
                'codigo' => 'SGDU',
                'descripcion' => 'Planificación y desarrollo urbano',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 2,
            ],
            [
                'nombre' => 'Sub Gerencia de Licencias y Autorizaciones',
                'codigo' => 'SGLA',
                'descripcion' => 'Otorgamiento de licencias y autorizaciones',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 3,
            ],
            [
                'nombre' => 'Sub Gerencia de Fiscalización',
                'codigo' => 'SGF',
                'descripcion' => 'Fiscalización y control municipal',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 4,
            ],
            [
                'nombre' => 'Sub Gerencia de Rentas',
                'codigo' => 'SGR',
                'descripcion' => 'Gestión de ingresos municipales',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 5,
            ],
            [
                'nombre' => 'Sub Gerencia de Registro Civil',
                'codigo' => 'SGRC',
                'descripcion' => 'Servicios de registro civil',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 6,
            ],
            [
                'nombre' => 'Sub Gerencia de Participación Ciudadana',
                'codigo' => 'SGPC',
                'descripcion' => 'Promoción de la participación ciudadana',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 7,
            ],
            [
                'nombre' => 'Sub Gerencia de Seguridad Ciudadana',
                'codigo' => 'SGSC',
                'descripcion' => 'Seguridad y orden público',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 8,
            ],
            [
                'nombre' => 'Sub Gerencia de Medio Ambiente',
                'codigo' => 'SGMA',
                'descripcion' => 'Gestión ambiental municipal',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 9,
            ],
            [
                'nombre' => 'Sub Gerencia de Transportes',
                'codigo' => 'SGT',
                'descripcion' => 'Gestión del transporte urbano',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 10,
            ],
            [
                'nombre' => 'Sub Gerencia de Defensa Civil',
                'codigo' => 'SGDC',
                'descripcion' => 'Gestión de riesgos y emergencias',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 11,
            ],
            [
                'nombre' => 'Sub Gerencia de Tecnologías de la Información',
                'codigo' => 'SGTI',
                'descripcion' => 'Gestión tecnológica municipal',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 12,
            ],
            [
                'nombre' => 'Sub Gerencia de Recursos Humanos',
                'codigo' => 'SGRH',
                'descripcion' => 'Gestión del talento humano',
                'tipo' => 'subgerencia',
                'gerencia_padre_id' => $gerenciaMunicipal->id,
                'orden' => 13,
            ],
        ];

        foreach ($gerencias as $gerenciaData) {
            Gerencia::create([
                'nombre' => $gerenciaData['nombre'],
                'codigo' => $gerenciaData['codigo'],
                'descripcion' => $gerenciaData['descripcion'],
                'tipo' => $gerenciaData['tipo'],
                'gerencia_padre_id' => $gerenciaData['gerencia_padre_id'],
                'activo' => true,
                'orden' => $gerenciaData['orden'],
            ]);
        }

        $this->command->info('Gerencias creadas exitosamente');
        $this->command->info('Total de gerencias: ' . Gerencia::count());
        $this->command->info('Estructura jerárquica establecida');
    }
}
