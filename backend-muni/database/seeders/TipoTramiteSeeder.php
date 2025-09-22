<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoTramite;
use App\Models\Gerencia;

class TipoTramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gerencias = Gerencia::all()->keyBy('nombre');

        $tipos = [
            [
                'nombre' => 'Licencia de Funcionamiento',
                'descripcion' => 'Autorización para el funcionamiento de establecimientos comerciales',
                'codigo' => 'LF-001',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Económico']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'ruc', 'planos'],
                'costo' => 125.50,
                'tiempo_estimado_dias' => 15,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Licencia de Edificación',
                'descripcion' => 'Autorización para construcción de edificaciones',
                'codigo' => 'LE-001',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Urbano']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'planos', 'titulo_propiedad'],
                'costo' => 450.00,
                'tiempo_estimado_dias' => 30,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Numeración',
                'descripcion' => 'Asignación de numeración predial',
                'codigo' => 'CN-001',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Urbano']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'croquis'],
                'costo' => 35.00,
                'tiempo_estimado_dias' => 7,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Matrimonio Civil',
                'descripcion' => 'Ceremonia de matrimonio civil',
                'codigo' => 'MC-001',
                'gerencia_id' => $gerencias['Gerencia de Servicios Sociales']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'dni_contrayentes', 'certificado_solteria'],
                'costo' => 85.00,
                'tiempo_estimado_dias' => 15,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Inscripción de Defunción',
                'descripcion' => 'Registro de defunción en el registro civil',
                'codigo' => 'ID-001',
                'gerencia_id' => $gerencias['Gerencia de Servicios Sociales']->id ?? null,
                'documentos_requeridos' => ['certificado_defuncion', 'dni_fallecido'],
                'costo' => 25.00,
                'tiempo_estimado_dias' => 3,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Constancia de Posesión',
                'descripcion' => 'Constancia de posesión de terreno',
                'codigo' => 'CP-001',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Urbano']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'declaracion_jurada', 'testigos'],
                'costo' => 75.00,
                'tiempo_estimado_dias' => 20,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Licencia de Obra Menor',
                'descripcion' => 'Autorización para obras menores',
                'codigo' => 'LOM-001',
                'gerencia_id' => $gerencias['Gerencia de Desarrollo Urbano']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'croquis', 'presupuesto'],
                'costo' => 180.00,
                'tiempo_estimado_dias' => 10,
                'requiere_pago' => true,
                'activo' => true
            ],
            [
                'nombre' => 'Certificado de Seguridad',
                'descripcion' => 'Certificado de seguridad en defensa civil',
                'codigo' => 'CS-001',
                'gerencia_id' => $gerencias['Gerencia de Seguridad Ciudadana']->id ?? null,
                'documentos_requeridos' => ['solicitud', 'planos_seguridad'],
                'costo' => 95.00,
                'tiempo_estimado_dias' => 12,
                'requiere_pago' => true,
                'activo' => true
            ]
        ];

        foreach ($tipos as $tipo) {
            TipoTramite::updateOrCreate(
                ['codigo' => $tipo['codigo']],
                $tipo
            );
        }

        $this->command?->info('TipoTramiteSeeder: upsert completado (' . count($tipos) . ' registros).');
    }
}
