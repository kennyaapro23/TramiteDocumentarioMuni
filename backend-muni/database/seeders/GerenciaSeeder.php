<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Gerencias principales
        $gerencias = [
            [
                'nombre' => 'Gerencia de Desarrollo Urbano',
                'codigo' => 'GDU',
                'descripcion' => 'Gestiona el desarrollo urbano y la planificación territorial',
                'tipo' => Gerencia::TIPO_GERENCIA,
                'gerencia_padre_id' => null,
                'flujos_permitidos' => [
                    'licencia_construccion',
                    'certificado_habilitacion',
                    'autorizacion_especial'
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Gerencia de Servicios Públicos',
                'codigo' => 'GSP',
                'descripcion' => 'Gestiona los servicios públicos municipales',
                'tipo' => Gerencia::TIPO_GERENCIA,
                'gerencia_padre_id' => null,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 2,
            ],
            [
                'nombre' => 'Gerencia de Seguridad Ciudadana',
                'codigo' => 'GSC',
                'descripcion' => 'Gestiona la seguridad ciudadana',
                'tipo' => Gerencia::TIPO_GERENCIA,
                'gerencia_padre_id' => null,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 3,
            ],
            [
                'nombre' => 'Gerencia de Desarrollo Económico',
                'codigo' => 'GDE',
                'descripcion' => 'Gestiona el desarrollo económico local',
                'tipo' => Gerencia::TIPO_GERENCIA,
                'gerencia_padre_id' => null,
                'flujos_permitidos' => [
                    'licencia_funcionamiento',
                    'autorizacion_especial'
                ],
                'orden' => 4,
            ],
            [
                'nombre' => 'Gerencia de Desarrollo Social',
                'codigo' => 'GDS',
                'descripcion' => 'Gestiona el desarrollo social',
                'tipo' => Gerencia::TIPO_GERENCIA,
                'gerencia_padre_id' => null,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 5,
            ],
        ];

        // Crear gerencias principales
        foreach ($gerencias as $gerenciaData) {
            Gerencia::create($gerenciaData);
        }

        // Crear Subgerencias
        $subgerencias = [
            // Subgerencias de Desarrollo Urbano
            [
                'nombre' => 'Subgerencia de Obras Públicas',
                'codigo' => 'SOP',
                'descripcion' => 'Gestiona obras públicas municipales',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GDU')->first()->id,
                'flujos_permitidos' => [
                    'licencia_construccion',
                    'certificado_habilitacion'
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Subgerencia de Catastro',
                'codigo' => 'SCA',
                'descripcion' => 'Gestiona el catastro municipal',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GDU')->first()->id,
                'flujos_permitidos' => [
                    'licencia_construccion',
                    'certificado_habilitacion'
                ],
                'orden' => 2,
            ],
            [
                'nombre' => 'Subgerencia de Transporte',
                'codigo' => 'STR',
                'descripcion' => 'Gestiona el transporte urbano',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GDU')->first()->id,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 3,
            ],

            // Subgerencias de Servicios Públicos
            [
                'nombre' => 'Subgerencia de Agua y Saneamiento',
                'codigo' => 'SAS',
                'descripcion' => 'Gestiona agua potable y alcantarillado',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GSP')->first()->id,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Subgerencia de Limpieza Pública',
                'codigo' => 'SLP',
                'descripcion' => 'Gestiona la limpieza pública',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GSP')->first()->id,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 2,
            ],

            // Subgerencias de Desarrollo Económico
            [
                'nombre' => 'Subgerencia de Comercio',
                'codigo' => 'SCO',
                'descripcion' => 'Gestiona el comercio local',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GDE')->first()->id,
                'flujos_permitidos' => [
                    'licencia_funcionamiento'
                ],
                'orden' => 1,
            ],
            [
                'nombre' => 'Subgerencia de Turismo',
                'codigo' => 'STU',
                'descripcion' => 'Gestiona el turismo local',
                'tipo' => Gerencia::TIPO_SUBGERENCIA,
                'gerencia_padre_id' => Gerencia::where('codigo', 'GDE')->first()->id,
                'flujos_permitidos' => [
                    'autorizacion_especial'
                ],
                'orden' => 2,
            ],
        ];

        // Crear subgerencias
        foreach ($subgerencias as $subgerenciaData) {
            Gerencia::create($subgerenciaData);
        }
    }
}
