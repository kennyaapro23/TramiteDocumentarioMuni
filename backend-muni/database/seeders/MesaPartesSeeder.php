<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MesaParte;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\User;
use Carbon\Carbon;

class MesaPartesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos de referencia
        $tiposTramite = TipoTramite::pluck('id', 'codigo')->toArray();
        $gerencias = Gerencia::pluck('id', 'nombre')->toArray();
        $usuarios = User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'super_admin');
        })->pluck('id')->toArray();

        $registrosMesaPartes = [
            [
                'numero_ingreso' => 'MP-' . date('Y') . '-00001',
                'fecha_ingreso' => Carbon::now()->subDays(5)->toDateString(),
                'hora_ingreso' => '09:00:00',
                'solicitante_nombre' => 'Juan Carlos Pérez Rojas',
                'solicitante_dni' => '12345678',
                'solicitante_telefono' => '987654321',
                'solicitante_email' => 'juan.perez@email.com',
                'solicitante_direccion' => 'Av. Principal 123, Lima',
                'tipo_tramite_id' => $tiposTramite['TRAM-001'] ?? 1, // Licencia de Funcionamiento
                'asunto' => 'Solicitud de licencia de funcionamiento para restaurante',
                'estado' => 'derivado',
                'gerencia_destino_id' => $gerencias['Gerencia de Desarrollo Económico'] ?? 1,
                'observaciones_derivacion' => null,
                'fecha_derivacion' => Carbon::now()->subDays(4),
                'codigo_seguimiento' => 'SEG-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'usuario_recepcion_id' => $usuarios[0] ?? 1,
                'usuario_derivacion_id' => $usuarios[0] ?? 1,
                'documentos_presentados' => [
                    'solicitud.pdf',
                    'dni_copia.pdf',
                    'ruc_copia.pdf',
                    'declaracion_jurada.pdf',
                    'recibo_pago.pdf'
                ],
                'documentos_completos' => true
            ],
            [
                'numero_ingreso' => 'MP-' . date('Y') . '-00002',
                'fecha_ingreso' => Carbon::now()->subDays(10)->toDateString(),
                'hora_ingreso' => '10:30:00',
                'solicitante_nombre' => 'María Elena Vásquez Torres',
                'solicitante_dni' => '87654321',
                'solicitante_telefono' => '987123456',
                'solicitante_email' => 'maria.vasquez@email.com',
                'solicitante_direccion' => 'Jr. Los Olivos 456, Lima',
                'tipo_tramite_id' => $tiposTramite['TRAM-002'] ?? 2, // Licencia de Edificación
                'asunto' => 'Licencia de edificación para construcción de vivienda unifamiliar',
                'estado' => 'derivado',
                'gerencia_destino_id' => $gerencias['Gerencia de Desarrollo Urbano'] ?? 2,
                'observaciones_derivacion' => 'Planos arquitectónicos en revisión técnica',
                'fecha_derivacion' => Carbon::now()->subDays(9),
                'codigo_seguimiento' => 'SEG-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'usuario_recepcion_id' => $usuarios[1] ?? 1,
                'usuario_derivacion_id' => $usuarios[1] ?? 1,
                'documentos_presentados' => [
                    'solicitud.pdf',
                    'dni_copia.pdf',
                    'planos_arquitectonicos.dwg',
                    'certificado_parametros.pdf',
                    'titulo_propiedad.pdf',
                    'poliza_seguro.pdf'
                ],
                'documentos_completos' => true
            ],
            [
                'numero_ingreso' => 'MP-' . date('Y') . '-00003',
                'fecha_ingreso' => Carbon::now()->subDays(2)->toDateString(),
                'hora_ingreso' => '14:15:00',
                'solicitante_nombre' => 'Carlos Alberto Mendoza Silva',
                'solicitante_dni' => '11223344',
                'solicitante_telefono' => '987789123',
                'solicitante_email' => 'carlos.mendoza@email.com',
                'solicitante_direccion' => 'Calle Las Flores 789, Lima',
                'tipo_tramite_id' => $tiposTramite['TRAM-003'] ?? 3, // Constancia de Posesión
                'asunto' => 'Constancia de posesión de terreno heredado',
                'estado' => 'recibido',
                'gerencia_destino_id' => null,
                'observaciones_derivacion' => null,
                'fecha_derivacion' => null,
                'codigo_seguimiento' => 'SEG-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'usuario_recepcion_id' => $usuarios[0] ?? 1,
                'usuario_derivacion_id' => null,
                'documentos_presentados' => [
                    'solicitud.pdf',
                    'dni_copia.pdf',
                    'declaracion_jurada.pdf'
                ],
                'documentos_completos' => false,
                'documentos_faltantes' => ['recibo_pago.pdf']
            ],
            [
                'numero_ingreso' => 'MP-' . date('Y') . '-00004',
                'fecha_ingreso' => Carbon::now()->subDays(1)->toDateString(),
                'hora_ingreso' => '11:45:00',
                'solicitante_nombre' => 'Ana Lucía Ramírez Castro',
                'solicitante_dni' => '55667788',
                'solicitante_telefono' => '987456789',
                'solicitante_email' => 'ana.ramirez@email.com',
                'solicitante_direccion' => 'Av. Los Jardines 321, Lima',
                'tipo_tramite_id' => $tiposTramite['TRAM-004'] ?? 4, // Registro Vendedor Ambulante
                'asunto' => 'Registro como vendedora ambulante de comida',
                'estado' => 'observado',
                'gerencia_destino_id' => $gerencias['Gerencia de Desarrollo Económico'] ?? 1,
                'observaciones_derivacion' => 'Falta certificado médico vigente. El presentado está vencido.',
                'fecha_derivacion' => Carbon::now()->subDays(1),
                'codigo_seguimiento' => 'SEG-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'usuario_recepcion_id' => $usuarios[1] ?? 1,
                'usuario_derivacion_id' => $usuarios[1] ?? 1,
                'documentos_presentados' => [
                    'solicitud.pdf',
                    'dni_copia.pdf',
                    'certificado_medico_vencido.pdf',
                    'antecedentes_policiales.pdf'
                ],
                'documentos_completos' => false,
                'requiere_subsanacion' => true,
                'fecha_limite_subsanacion' => Carbon::now()->addDays(5)
            ]
        ];

        foreach ($registrosMesaPartes as $registroData) {
            MesaParte::create($registroData);
        }

        $this->command->info('Registros de Mesa de Partes creados exitosamente');
        $this->command->info('Total de registros: ' . MesaParte::count());
        
        // Mostrar estadísticas por estado
        $this->command->info('Estadísticas por estado:');
        $estados = MesaParte::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();
        
        foreach ($estados as $estado) {
            $this->command->info("- {$estado->estado}: {$estado->total} registros");
        }
    }
}
