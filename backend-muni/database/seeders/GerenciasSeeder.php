<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gerencia;

class GerenciasSeeder extends Seeder
{
    public function run(): void
    {
        // Crear gerencias principales con códigos simples
        $gerenciasEstructura = [
            // ALCALDÍA
            [
                'nombre' => 'Alcaldía',
                'descripcion' => 'Despacho de Alcaldía',
                'codigo' => 'ALC',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => []
            ],
            
            // GERENCIA MUNICIPAL
            [
                'nombre' => 'Gerencia Municipal',
                'descripcion' => 'Gerencia Municipal General',
                'codigo' => 'GM',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => []
            ],
            
            // DESARROLLO URBANO
            [
                'nombre' => 'Sub Gerencia de Desarrollo Urbano',
                'descripcion' => 'Desarrollo urbano y obras',
                'codigo' => 'SGDU',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Obras Privadas',
                        'descripcion' => 'Licencias de edificación',
                        'codigo' => 'AOP'
                    ],
                    [
                        'nombre' => 'Área de Catastro',
                        'descripcion' => 'Catastro urbano',
                        'codigo' => 'ACAT'
                    ]
                ]
            ],
            
            // LICENCIAS Y AUTORIZACIONES
            [
                'nombre' => 'Sub Gerencia de Licencias y Autorizaciones',
                'descripcion' => 'Licencias de funcionamiento',
                'codigo' => 'SGLA',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Comercio',
                        'descripcion' => 'Licencias comerciales',
                        'codigo' => 'ACOM'
                    ],
                    [
                        'nombre' => 'Área de Espectáculos',
                        'descripcion' => 'Autorizaciones de eventos',
                        'codigo' => 'AESP'
                    ]
                ]
            ],
            
            // SERVICIOS PÚBLICOS
            [
                'nombre' => 'Sub Gerencia de Servicios Públicos',
                'descripcion' => 'Servicios públicos locales',
                'codigo' => 'SGSP',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Limpieza Pública',
                        'descripcion' => 'Gestión de residuos',
                        'codigo' => 'ALP'
                    ],
                    [
                        'nombre' => 'Área de Parques y Jardines',
                        'descripcion' => 'Mantenimiento de áreas verdes',
                        'codigo' => 'APJ'
                    ]
                ]
            ],
            
            // FISCALIZACIÓN
            [
                'nombre' => 'Sub Gerencia de Fiscalización',
                'descripcion' => 'Fiscalización municipal',
                'codigo' => 'SGF',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Control Urbano',
                        'descripcion' => 'Control de construcciones',
                        'codigo' => 'ACU'
                    ]
                ]
            ],
            
            // ADMINISTRACIÓN TRIBUTARIA
            [
                'nombre' => 'Sub Gerencia de Rentas',
                'descripcion' => 'Administración tributaria',
                'codigo' => 'SGR',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Recaudación',
                        'descripcion' => 'Cobranza de tributos',
                        'codigo' => 'AREC'
                    ],
                    [
                        'nombre' => 'Área de Fiscalización Tributaria',
                        'descripcion' => 'Fiscalización de tributos',
                        'codigo' => 'AFT'
                    ]
                ]
            ],
            
            // REGISTRO CIVIL
            [
                'nombre' => 'Sub Gerencia de Registro Civil',
                'descripcion' => 'Registro civil y RENIEC',
                'codigo' => 'SGRC',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Nacimientos',
                        'descripcion' => 'Inscripción de nacimientos',
                        'codigo' => 'ANAC'
                    ],
                    [
                        'nombre' => 'Área de Defunciones',
                        'descripcion' => 'Inscripción de defunciones',
                        'codigo' => 'ADEF'
                    ],
                    [
                        'nombre' => 'Área de Matrimonios',
                        'descripcion' => 'Inscripción de matrimonios',
                        'codigo' => 'AMAT'
                    ]
                ]
            ],
            
            // PARTICIPACIÓN CIUDADANA
            [
                'nombre' => 'Sub Gerencia de Participación Ciudadana',
                'descripcion' => 'Participación vecinal',
                'codigo' => 'SGPC',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => []
            ],
            
            // SEGURIDAD CIUDADANA
            [
                'nombre' => 'Sub Gerencia de Seguridad Ciudadana',
                'descripcion' => 'Seguridad y serenazgo',
                'codigo' => 'SGSC',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Serenazgo',
                        'descripcion' => 'Patrullaje y vigilancia',
                        'codigo' => 'ASER'
                    ],
                    [
                        'nombre' => 'Área de Defensa Civil',
                        'descripcion' => 'Prevención de desastres',
                        'codigo' => 'ADC'
                    ]
                ]
            ],
            
            // MEDIO AMBIENTE
            [
                'nombre' => 'Sub Gerencia de Medio Ambiente',
                'descripcion' => 'Gestión ambiental',
                'codigo' => 'SGMA',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Ecología',
                        'descripcion' => 'Conservación ambiental',
                        'codigo' => 'AECO'
                    ]
                ]
            ],
            
            // TRANSPORTES Y TRÁNSITO
            [
                'nombre' => 'Sub Gerencia de Transportes',
                'descripcion' => 'Transporte y tránsito',
                'codigo' => 'SGT',
                'tipo' => 'gerencia',
                'activo' => true,
                'subgerencias' => [
                    [
                        'nombre' => 'Área de Control de Tránsito',
                        'descripcion' => 'Control vehicular',
                        'codigo' => 'ACT'
                    ]
                ]
            ],
        ];

        // Crear gerencias principales y subgerencias
        foreach ($gerenciasEstructura as $gerenciaData) {
            // Crear gerencia principal
            $gerencia = Gerencia::firstOrCreate(
                ['codigo' => $gerenciaData['codigo']],
                [
                    'nombre' => $gerenciaData['nombre'],
                    'descripcion' => $gerenciaData['descripcion'],
                    'tipo' => $gerenciaData['tipo'],
                    'activo' => $gerenciaData['activo'],
                    'gerencia_padre_id' => null
                ]
            );

            // Crear subgerencias si existen
            if (isset($gerenciaData['subgerencias']) && !empty($gerenciaData['subgerencias'])) {
                foreach ($gerenciaData['subgerencias'] as $subData) {
                    Gerencia::firstOrCreate(
                        ['codigo' => $subData['codigo']],
                        [
                            'nombre' => $subData['nombre'],
                            'descripcion' => $subData['descripcion'],
                            'tipo' => 'subgerencia',
                            'activo' => true,
                            'gerencia_padre_id' => $gerencia->id
                        ]
                    );
                }
            }
        }

        $gerenciasPrincipales = Gerencia::whereNull('gerencia_padre_id')->count();
        $subgerencias = Gerencia::whereNotNull('gerencia_padre_id')->count();
        
        $this->command->info("Gerencias creadas exitosamente:");
        $this->command->info("- {$gerenciasPrincipales} gerencias principales");
        $this->command->info("- {$subgerencias} subgerencias");
        $this->command->info("- Total: " . Gerencia::count() . " gerencias");
    }
}