<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MesaParte;
use App\Models\TipoTramite;
use App\Models\TipoDocumento;
use App\Models\Gerencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MesaPartesController extends Controller
{
    /**
     * Lista de registros de mesa de partes
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = MesaParte::with([
                'tipoTramite',
                'gerenciaDestino',
                'usuarioRecepcion',
                'usuarioRevision',
                'usuarioDerivacion'
            ]);

            // Filtros
            if ($request->has('estado')) {
                $query->where('estado', $request->estado);
            }

            if ($request->has('fecha_ingreso')) {
                $query->whereDate('fecha_ingreso', $request->fecha_ingreso);
            }

            if ($request->has('gerencia_id')) {
                $query->where('gerencia_destino_id', $request->gerencia_id);
            }

            if ($request->has('tipo_tramite_id')) {
                $query->where('tipo_tramite_id', $request->tipo_tramite_id);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('numero_ingreso', 'like', "%{$search}%")
                      ->orWhere('solicitante_nombre', 'like', "%{$search}%")
                      ->orWhere('solicitante_dni', 'like', "%{$search}%")
                      ->orWhere('codigo_seguimiento', 'like', "%{$search}%")
                      ->orWhere('asunto', 'like', "%{$search}%");
                });
            }

            // Ordenamiento
            $orderBy = $request->get('order_by', 'fecha_ingreso');
            $orderDirection = $request->get('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginación
            $perPage = $request->get('per_page', 15);
            $registros = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $registros,
                'message' => 'Registros de mesa de partes obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener registros: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nuevo registro en mesa de partes
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'solicitante_nombre' => 'required|string|max:255',
                'solicitante_dni' => 'required|string|size:8',
                'solicitante_telefono' => 'nullable|string|max:20',
                'solicitante_email' => 'nullable|email|max:255',
                'solicitante_direccion' => 'nullable|string|max:500',
                'tipo_tramite_id' => 'required|exists:tipos_tramite,id',
                'documentos_presentados' => 'required|array|min:1',
                'documentos_presentados.*' => 'exists:tipos_documento,id',
                'asunto' => 'required|string|max:500',
                'observaciones_ingreso' => 'nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            // Generar números automáticos
            $numeroIngreso = MesaParte::generarNumeroIngreso();
            $codigoSeguimiento = MesaParte::generarCodigoSeguimiento();

            // Obtener tipo de trámite para determinar gerencia destino
            $tipoTramite = TipoTramite::findOrFail($validated['tipo_tramite_id']);

            $registro = MesaParte::create([
                'numero_ingreso' => $numeroIngreso,
                'fecha_ingreso' => now()->toDateString(),
                'hora_ingreso' => now()->toTimeString(),
                'solicitante_nombre' => $validated['solicitante_nombre'],
                'solicitante_dni' => $validated['solicitante_dni'],
                'solicitante_telefono' => $validated['solicitante_telefono'],
                'solicitante_email' => $validated['solicitante_email'],
                'solicitante_direccion' => $validated['solicitante_direccion'],
                'tipo_tramite_id' => $validated['tipo_tramite_id'],
                'documentos_presentados' => $validated['documentos_presentados'],
                'asunto' => $validated['asunto'],
                'observaciones_ingreso' => $validated['observaciones_ingreso'],
                'estado' => MesaParte::ESTADO_RECIBIDO,
                'gerencia_destino_id' => $tipoTramite->gerencia_id,
                'usuario_recepcion_id' => Auth::id(),
                'codigo_seguimiento' => $codigoSeguimiento,
                'historial_estados' => [[
                    'estado_anterior' => null,
                    'estado_nuevo' => MesaParte::ESTADO_RECIBIDO,
                    'fecha_cambio' => now()->toISOString(),
                    'usuario_id' => Auth::id(),
                    'observaciones' => 'Registro inicial en mesa de partes'
                ]]
            ]);

            // Verificar documentos completos
            $this->verificarDocumentosCompletos($registro);

            DB::commit();

            $registro->load([
                'tipoTramite',
                'gerenciaDestino',
                'usuarioRecepcion'
            ]);

            return response()->json([
                'success' => true,
                'data' => $registro,
                'message' => 'Documento registrado exitosamente en mesa de partes'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar registro específico
     */
    public function show($id): JsonResponse
    {
        try {
            $registro = MesaParte::with([
                'tipoTramite',
                'gerenciaDestino',
                'usuarioRecepcion',
                'usuarioRevision',
                'usuarioDerivacion'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $registro,
                'message' => 'Registro obtenido exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registro no encontrado'
            ], 404);
        }
    }

    /**
     * Actualizar registro
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $registro = MesaParte::findOrFail($id);

            $validated = $request->validate([
                'solicitante_telefono' => 'nullable|string|max:20',
                'solicitante_email' => 'nullable|email|max:255',
                'solicitante_direccion' => 'nullable|string|max:500',
                'observaciones_ingreso' => 'nullable|string|max:1000'
            ]);

            $registro->update($validated);

            return response()->json([
                'success' => true,
                'data' => $registro,
                'message' => 'Registro actualizado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar registro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Derivar documento a gerencia
     */
    public function derivar(Request $request, $id): JsonResponse
    {
        try {
            $registro = MesaParte::findOrFail($id);

            $validated = $request->validate([
                'gerencia_destino_id' => 'required|exists:gerencias,id',
                'usuario_derivacion_id' => 'nullable|exists:users,id',
                'observaciones_derivacion' => 'nullable|string|max:1000'
            ]);

            // Verificar que los documentos estén completos
            if (!$registro->documentos_completos) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede derivar: documentos incompletos'
                ], 400);
            }

            $registro->derivarAGerencia(
                $validated['gerencia_destino_id'],
                $validated['usuario_derivacion_id'],
                $validated['observaciones_derivacion']
            );

            $registro->load([
                'tipoTramite',
                'gerenciaDestino',
                'usuarioDerivacion'
            ]);

            return response()->json([
                'success' => true,
                'data' => $registro,
                'message' => 'Documento derivado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al derivar documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Marcar como observado
     */
    public function observar(Request $request, $id): JsonResponse
    {
        try {
            $registro = MesaParte::findOrFail($id);

            $validated = $request->validate([
                'documentos_faltantes' => 'required|array|min:1',
                'documentos_faltantes.*' => 'exists:tipos_documento,id',
                'fecha_limite_subsanacion' => 'nullable|date|after:today',
                'observaciones' => 'nullable|string|max:1000'
            ]);

            $fechaLimite = $validated['fecha_limite_subsanacion'] 
                ? \Carbon\Carbon::parse($validated['fecha_limite_subsanacion'])
                : now()->addDays(10);

            $registro->marcarObservado(
                $validated['documentos_faltantes'],
                $fechaLimite
            );

            return response()->json([
                'success' => true,
                'data' => $registro,
                'message' => 'Documento marcado como observado'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al observar documento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Consulta pública por código de seguimiento
     */
    public function consultarPorCodigo($codigoSeguimiento): JsonResponse
    {
        try {
            $registro = MesaParte::where('codigo_seguimiento', $codigoSeguimiento)
                ->with(['tipoTramite', 'gerenciaDestino'])
                ->first();

            if (!$registro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Código de seguimiento no encontrado'
                ], 404);
            }

            // Solo información pública
            $datosPublicos = [
                'numero_ingreso' => $registro->numero_ingreso,
                'fecha_ingreso' => $registro->fecha_ingreso,
                'estado' => $registro->estado,
                'tipo_tramite' => $registro->tipoTramite->nombre,
                'gerencia_destino' => $registro->gerenciaDestino->nombre ?? 'No asignada',
                'requiere_subsanacion' => $registro->requiere_subsanacion,
                'fecha_limite_subsanacion' => $registro->fecha_limite_subsanacion,
                'historial_estados' => $registro->historial_estados
            ];

            return response()->json([
                'success' => true,
                'data' => $datosPublicos,
                'message' => 'Información de seguimiento obtenida'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consultar seguimiento'
            ], 500);
        }
    }

    /**
     * Obtener tipos de trámite disponibles
     */
    public function getTiposTramite(): JsonResponse
    {
        try {
            $tipos = TipoTramite::activo()
                ->with('gerencia')
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tipos,
                'message' => 'Tipos de trámite obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de trámite'
            ], 500);
        }
    }

    /**
     * Obtener tipos de documento
     */
    public function getTiposDocumento(): JsonResponse
    {
        try {
            $tipos = TipoDocumento::activo()
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tipos,
                'message' => 'Tipos de documento obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener tipos de documento'
            ], 500);
        }
    }

    /**
     * Obtener documentos requeridos para un tipo de trámite
     */
    public function getDocumentosRequeridos($tipoTramiteId): JsonResponse
    {
        try {
            $tipoTramite = TipoTramite::findOrFail($tipoTramiteId);
            
            $documentosRequeridos = TipoDocumento::whereIn('id', $tipoTramite->documentos_requeridos)
                ->orderBy('nombre')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $documentosRequeridos,
                'message' => 'Documentos requeridos obtenidos exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener documentos requeridos'
            ], 500);
        }
    }

    /**
     * Estadísticas de mesa de partes
     */
    public function getEstadisticas(): JsonResponse
    {
        try {
            $hoy = now()->toDateString();
            $semanaActual = [now()->startOfWeek(), now()->endOfWeek()];
            $mesActual = [now()->startOfMonth(), now()->endOfMonth()];

            $estadisticas = [
                'registros_hoy' => MesaParte::whereDate('fecha_ingreso', $hoy)->count(),
                'registros_semana' => MesaParte::whereBetween('fecha_ingreso', $semanaActual)->count(),
                'registros_mes' => MesaParte::whereBetween('fecha_ingreso', $mesActual)->count(),
                'por_estado' => MesaParte::select('estado', DB::raw('COUNT(*) as total'))
                    ->groupBy('estado')
                    ->get()
                    ->keyBy('estado'),
                'pendientes_subsanacion' => MesaParte::pendientesSubsanacion()->count(),
                'vencidos_subsanacion' => MesaParte::vencidosSubsanacion()->count(),
                'por_gerencia' => MesaParte::join('gerencias', 'mesa_partes.gerencia_destino_id', '=', 'gerencias.id')
                    ->select('gerencias.nombre', DB::raw('COUNT(*) as total'))
                    ->groupBy('gerencias.id', 'gerencias.nombre')
                    ->get()
            ];

            return response()->json([
                'success' => true,
                'data' => $estadisticas,
                'message' => 'Estadísticas obtenidas exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Verificar si los documentos están completos
     */
    private function verificarDocumentosCompletos(MesaParte $registro)
    {
        $tipoTramite = $registro->tipoTramite;
        $documentosRequeridos = $tipoTramite->documentos_requeridos;
        $documentosPresentados = $registro->documentos_presentados;

        $documentosFaltantes = array_diff($documentosRequeridos, $documentosPresentados);

        if (empty($documentosFaltantes)) {
            $registro->update([
                'documentos_completos' => true,
                'documentos_faltantes' => null,
                'requiere_subsanacion' => false
            ]);
        } else {
            $registro->update([
                'documentos_completos' => false,
                'documentos_faltantes' => $documentosFaltantes,
                'requiere_subsanacion' => true,
                'fecha_limite_subsanacion' => now()->addDays(10)
            ]);
        }

        return $registro;
    }
}
