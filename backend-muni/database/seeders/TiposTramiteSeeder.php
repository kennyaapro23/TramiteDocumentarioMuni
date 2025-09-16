<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoTramite;
use App\Models\TipoDocumento;
use App\Models\Gerencia;

class TiposTramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener gerencias por nombre para asignar los trámites
        $gerencias = Gerencia::pluck('id', 'nombre')->toArray();
        
        // Obtener tipos de documento por código para referencias
        $tiposDoc = TipoDocumento::pluck('id', 'codigo')->toArray();

        $tiposTramite = [
            [
                'codigo' => 'TRAM-001',
                'nombre' => 'Licencia de Funcionamiento',
                'descripcion' => 'Solicitud de licencia de funcionamiento para establecimiento comercial',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Económico'] ?? 1,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-005'], // RUC
                    $tiposDoc['DOC-003'], // Declaración Jurada
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 150.00,
                'tiempo_estimado_dias' => 15,
                'requiere_inspeccion' => true,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción y verificación de documentos',
                        'tiempo_estimado' => 1
                    ],
                    'evaluacion_tecnica' => [
                        'descripcion' => 'Evaluación técnica de la solicitud',
                        'tiempo_estimado' => 7
                    ],
                    'inspeccion' => [
                        'descripcion' => 'Inspección del local',
                        'tiempo_estimado' => 3
                    ],
                    'emision_licencia' => [
                        'descripcion' => 'Emisión de la licencia',
                        'tiempo_estimado' => 4
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-002',
                'nombre' => 'Licencia de Edificación',
                'descripcion' => 'Solicitud de licencia de edificación para construcción',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Urbano'] ?? 2,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-006'], // Planos
                    $tiposDoc['DOC-007'], // Certificado parámetros
                    $tiposDoc['DOC-008'], // Título de propiedad
                    $tiposDoc['DOC-009'], // Póliza de seguro
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 500.00,
                'tiempo_estimado_dias' => 30,
                'requiere_inspeccion' => true,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción y verificación de documentos',
                        'tiempo_estimado' => 2
                    ],
                    'revision_planos' => [
                        'descripcion' => 'Revisión de planos arquitectónicos',
                        'tiempo_estimado' => 15
                    ],
                    'evaluacion_tecnica' => [
                        'descripcion' => 'Evaluación técnica estructural',
                        'tiempo_estimado' => 10
                    ],
                    'emision_licencia' => [
                        'descripcion' => 'Emisión de la licencia',
                        'tiempo_estimado' => 3
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-003',
                'nombre' => 'Constancia de Posesión',
                'descripcion' => 'Constancia de posesión de terreno o inmueble',
                'gerencia_id' => $gerencias['Gerencia de Catastro'] ?? 3,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-003'], // Declaración Jurada
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 50.00,
                'tiempo_estimado_dias' => 10,
                'requiere_inspeccion' => true,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción de documentos',
                        'tiempo_estimado' => 1
                    ],
                    'verificacion_campo' => [
                        'descripcion' => 'Verificación en campo',
                        'tiempo_estimado' => 5
                    ],
                    'emision_constancia' => [
                        'descripcion' => 'Emisión de constancia',
                        'tiempo_estimado' => 4
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-004',
                'nombre' => 'Registro de Vendedor Ambulante',
                'descripcion' => 'Registro municipal para vendedores ambulantes',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Económico'] ?? 1,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-011'], // Certificado médico
                    $tiposDoc['DOC-012'], // Antecedentes policiales
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 25.00,
                'tiempo_estimado_dias' => 7,
                'requiere_inspeccion' => false,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción de documentos',
                        'tiempo_estimado' => 1
                    ],
                    'evaluacion_documentos' => [
                        'descripcion' => 'Evaluación de documentos',
                        'tiempo_estimado' => 3
                    ],
                    'emision_carnet' => [
                        'descripcion' => 'Emisión de carnet',
                        'tiempo_estimado' => 3
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-005',
                'nombre' => 'Reclamo Tributario',
                'descripcion' => 'Reclamo contra resoluciones tributarias',
                'gerencia_id' => $gerencias['Gerencia de Administración Tributaria'] ?? 4,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-002'], // Memorial
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-003']  // Declaración Jurada
                ],
                'costo_base' => 0.00,
                'tiempo_estimado_dias' => 30,
                'requiere_inspeccion' => false,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción del reclamo',
                        'tiempo_estimado' => 1
                    ],
                    'evaluacion_legal' => [
                        'descripcion' => 'Evaluación legal del reclamo',
                        'tiempo_estimado' => 20
                    ],
                    'emision_resolucion' => [
                        'descripcion' => 'Emisión de resolución',
                        'tiempo_estimado' => 9
                    ]
                ],
                'requiere_pago_previo' => false,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-006',
                'nombre' => 'Solicitud de Empleo Municipal',
                'descripcion' => 'Solicitud para trabajar en la municipalidad',
                'gerencia_id' => $gerencias['Gerencia de Recursos Humanos'] ?? 5,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-011'], // Certificado médico
                    $tiposDoc['DOC-012']  // Antecedentes policiales
                ],
                'costo_base' => 0.00,
                'tiempo_estimado_dias' => 45,
                'requiere_inspeccion' => false,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción de solicitud',
                        'tiempo_estimado' => 1
                    ],
                    'evaluacion_perfil' => [
                        'descripcion' => 'Evaluación de perfil',
                        'tiempo_estimado' => 15
                    ],
                    'proceso_seleccion' => [
                        'descripcion' => 'Proceso de selección',
                        'tiempo_estimado' => 25
                    ],
                    'notificacion_resultado' => [
                        'descripcion' => 'Notificación de resultado',
                        'tiempo_estimado' => 4
                    ]
                ],
                'requiere_pago_previo' => false,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-007',
                'nombre' => 'Permiso de Evento Público',
                'descripcion' => 'Permiso para realizar eventos en espacios públicos',
                'gerencia_id' => $gerencias['Gerencia de Seguridad Ciudadana'] ?? 6,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-009'], // Póliza de seguro
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 100.00,
                'tiempo_estimado_dias' => 5,
                'requiere_inspeccion' => true,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción de solicitud',
                        'tiempo_estimado' => 1
                    ],
                    'evaluacion_seguridad' => [
                        'descripcion' => 'Evaluación de seguridad',
                        'tiempo_estimado' => 2
                    ],
                    'emision_permiso' => [
                        'descripcion' => 'Emisión del permiso',
                        'tiempo_estimado' => 2
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ],
            [
                'codigo' => 'TRAM-008',
                'nombre' => 'Certificado de Limpieza Pública',
                'descripcion' => 'Certificado de que el establecimiento tiene servicio de limpieza',
                'gerencia_id' => $gerencias['Gerencia de Servicios Públicos'] ?? 7,
                'documentos_requeridos' => [
                    $tiposDoc['DOC-001'], // Solicitud
                    $tiposDoc['DOC-004'], // DNI
                    $tiposDoc['DOC-005'], // RUC
                    $tiposDoc['DOC-010']  // Recibo de pago
                ],
                'costo_base' => 30.00,
                'tiempo_estimado_dias' => 3,
                'requiere_inspeccion' => true,
                'flujo_tramite' => [
                    'mesa_partes' => [
                        'descripcion' => 'Recepción de solicitud',
                        'tiempo_estimado' => 1
                    ],
                    'inspeccion_limpieza' => [
                        'descripcion' => 'Inspección de limpieza',
                        'tiempo_estimado' => 1
                    ],
                    'emision_certificado' => [
                        'descripcion' => 'Emisión del certificado',
                        'tiempo_estimado' => 1
                    ]
                ],
                'requiere_pago_previo' => true,
                'activo' => true
            ]
        ];

        foreach ($tiposTramite as $tramiteData) {
            TipoTramite::create($tramiteData);
        }

        $this->command->info('Tipos de trámite creados exitosamente');
        $this->command->info('Total de tipos de trámite: ' . TipoTramite::count());
    }
}
