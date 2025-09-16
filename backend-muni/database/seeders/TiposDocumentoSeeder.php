<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TiposDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposDocumento = [
            [
                'codigo' => 'DOC-001',
                'nombre' => 'Solicitud dirigida al alcalde',
                'descripcion' => 'Solicitud formal dirigida al señor alcalde con datos completos del solicitante',
                'campos_requeridos' => [
                    'nombre_completo',
                    'dni',
                    'direccion',
                    'telefono',
                    'motivo_solicitud',
                    'firma'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-002',
                'nombre' => 'Memorial',
                'descripcion' => 'Memorial o carta dirigida a la autoridad municipal correspondiente',
                'campos_requeridos' => [
                    'fecha',
                    'dirigido_a',
                    'nombre_solicitante',
                    'dni',
                    'asunto',
                    'contenido',
                    'firma'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-003',
                'nombre' => 'Declaración Jurada',
                'descripcion' => 'Declaración jurada de diversos tipos según el trámite',
                'campos_requeridos' => [
                    'tipo_declaracion',
                    'nombre_declarante',
                    'dni',
                    'contenido_declaracion',
                    'fecha',
                    'firma'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => true,
                'costo_tramitacion' => 5.00,
                'vigencia_dias' => 90,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-004',
                'nombre' => 'Copia simple del DNI',
                'descripcion' => 'Copia simple del documento nacional de identidad vigente',
                'campos_requeridos' => [
                    'numero_dni',
                    'fecha_emision',
                    'fecha_vencimiento'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-005',
                'nombre' => 'Copia simple del RUC',
                'descripcion' => 'Copia simple del registro único de contribuyente vigente',
                'campos_requeridos' => [
                    'numero_ruc',
                    'razon_social',
                    'fecha_inscripcion',
                    'estado'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 30,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-006',
                'nombre' => 'Planos arquitectónicos',
                'descripcion' => 'Planos arquitectónicos firmados por arquitecto colegiado',
                'campos_requeridos' => [
                    'escala',
                    'arquitecto_responsable',
                    'numero_colegiatura',
                    'firma_arquitecto',
                    'sello_profesional',
                    'fecha_elaboracion'
                ],
                'requiere_firma_digital' => true,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 180,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-007',
                'nombre' => 'Certificado de parámetros urbanísticos',
                'descripcion' => 'Certificado de parámetros urbanísticos vigente emitido por la municipalidad',
                'campos_requeridos' => [
                    'numero_certificado',
                    'fecha_emision',
                    'parametros_vigentes',
                    'zona_ubicacion'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 25.00,
                'vigencia_dias' => 90,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-008',
                'nombre' => 'Título de propiedad',
                'descripcion' => 'Copia certificada del título de propiedad del inmueble',
                'campos_requeridos' => [
                    'numero_partida',
                    'propietario',
                    'direccion_inmueble',
                    'area_terreno',
                    'linderos'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => true,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => null,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-009',
                'nombre' => 'Póliza de seguro',
                'descripcion' => 'Póliza de seguro de responsabilidad civil vigente',
                'campos_requeridos' => [
                    'numero_poliza',
                    'aseguradora',
                    'monto_cobertura',
                    'fecha_inicio',
                    'fecha_vencimiento',
                    'beneficiario'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 365,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-010',
                'nombre' => 'Recibo de pago de derechos',
                'descripcion' => 'Comprobante de pago de derechos de tramitación',
                'campos_requeridos' => [
                    'numero_recibo',
                    'fecha_pago',
                    'monto_pagado',
                    'concepto',
                    'codigo_tramite'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 30,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-011',
                'nombre' => 'Certificado médico',
                'descripcion' => 'Certificado médico vigente emitido por profesional colegiado',
                'campos_requeridos' => [
                    'medico_emisor',
                    'numero_colegiatura',
                    'fecha_emision',
                    'aptitud',
                    'observaciones'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 90,
                'activo' => true
            ],
            [
                'codigo' => 'DOC-012',
                'nombre' => 'Antecedentes policiales',
                'descripcion' => 'Certificado de antecedentes policiales vigente',
                'campos_requeridos' => [
                    'numero_certificado',
                    'fecha_emision',
                    'entidad_emisora',
                    'resultado'
                ],
                'requiere_firma_digital' => false,
                'requiere_notarizacion' => false,
                'costo_tramitacion' => 0.00,
                'vigencia_dias' => 90,
                'activo' => true
            ]
        ];

        foreach ($tiposDocumento as $tipoData) {
            TipoDocumento::create($tipoData);
        }

        $this->command->info('Tipos de documento creados exitosamente');
        $this->command->info('Total de tipos de documento: ' . TipoDocumento::count());
    }
}
