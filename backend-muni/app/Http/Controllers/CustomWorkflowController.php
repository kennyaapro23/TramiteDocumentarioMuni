<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkflow;
use App\Models\CustomWorkflowStep;
use App\Models\CustomWorkflowTransition;
use App\Models\Gerencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomWorkflowController extends Controller
{
    /**
     * Listar todos los workflows personalizables
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = CustomWorkflow::with(['gerencia', 'creador', 'steps']);

            // Filtros
            if ($request->has('tipo') && $request->tipo) {
                $query->porTipo($request->tipo);
            }

            if ($request->has('gerencia_id') && $request->gerencia_id) {
                $query->porGerencia($request->gerencia_id);
            }

            if ($request->has('activo')) {
                $query->activos();
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('descripcion', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
                });
            }

            $workflows = $query->orderBy('created_at', 'desc')
                               ->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $workflows
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener workflows: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo workflow personalizado
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:100|unique:custom_workflows,codigo',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:expediente,tramite,proceso',
            'configuracion' => 'nullable|array',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $workflow = CustomWorkflow::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'configuracion' => $request->configuracion,
                'activo' => $request->activo ?? true,
                'gerencia_id' => $request->gerencia_id,
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Workflow creado exitosamente',
                'data' => $workflow->load(['gerencia', 'creador'])
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar un workflow específico
     */
    public function show($id): JsonResponse
    {
        try {
            $workflow = CustomWorkflow::with([
                'gerencia',
                'creador',
                'steps' => function($query) {
                    $query->orderBy('orden');
                },
                'steps.transitionsFrom' => function($query) {
                    $query->with('toStep');
                },
                'steps.transitionsTo' => function($query) {
                    $query->with('fromStep');
                }
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $workflow
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Workflow no encontrado: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar un workflow
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'codigo' => ['required', 'string', 'max:100', Rule::unique('custom_workflows')->ignore($id)],
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string|in:expediente,tramite,proceso',
            'configuracion' => 'nullable|array',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $workflow = CustomWorkflow::findOrFail($id);

            $workflow->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'configuracion' => $request->configuracion,
                'activo' => $request->activo ?? $workflow->activo,
                'gerencia_id' => $request->gerencia_id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Workflow actualizado exitosamente',
                'data' => $workflow->load(['gerencia', 'creador'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un workflow
     */
    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $workflow = CustomWorkflow::findOrFail($id);

            // Verificar si el workflow está siendo usado
            if ($workflow->expedientes()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el workflow porque está siendo usado por expedientes'
                ], 422);
            }

            $workflow->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Workflow eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Duplicar un workflow
     */
    public function duplicate($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $originalWorkflow = CustomWorkflow::with('steps.transitions')->findOrFail($id);

            // Crear copia del workflow
            $newWorkflow = CustomWorkflow::create([
                'nombre' => $originalWorkflow->nombre . ' (Copia)',
                'codigo' => $originalWorkflow->codigo . '_copy_' . time(),
                'descripcion' => $originalWorkflow->descripcion,
                'tipo' => $originalWorkflow->tipo,
                'configuracion' => $originalWorkflow->configuracion,
                'activo' => false, // Iniciar inactivo
                'gerencia_id' => $originalWorkflow->gerencia_id,
                'created_by' => auth()->id()
            ]);

            // Copiar pasos
            $stepMapping = [];
            foreach ($originalWorkflow->steps as $step) {
                $newStep = CustomWorkflowStep::create([
                    'custom_workflow_id' => $newWorkflow->id,
                    'nombre' => $step->nombre,
                    'codigo' => $step->codigo . '_copy',
                    'descripcion' => $step->descripcion,
                    'orden' => $step->orden,
                    'tipo' => $step->tipo,
                    'configuracion' => $step->configuracion,
                    'condiciones' => $step->condiciones,
                    'acciones' => $step->acciones,
                    'requiere_aprobacion' => $step->requiere_aprobacion,
                    'tiempo_estimado' => $step->tiempo_estimado,
                    'responsable_tipo' => $step->responsable_tipo,
                    'responsable_id' => $step->responsable_id,
                    'activo' => $step->activo
                ]);

                $stepMapping[$step->id] = $newStep->id;
            }

            // Copiar transiciones
            foreach ($originalWorkflow->steps as $step) {
                foreach ($step->transitionsFrom as $transition) {
                    CustomWorkflowTransition::create([
                        'custom_workflow_id' => $newWorkflow->id,
                        'from_step_id' => $stepMapping[$transition->from_step_id] ?? null,
                        'to_step_id' => $stepMapping[$transition->to_step_id],
                        'nombre' => $transition->nombre,
                        'descripcion' => $transition->descripcion,
                        'condicion' => $transition->condicion,
                        'reglas' => $transition->reglas,
                        'automatica' => $transition->automatica,
                        'orden' => $transition->orden,
                        'activo' => $transition->activo
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Workflow duplicado exitosamente',
                'data' => $newWorkflow->load(['gerencia', 'creador', 'steps'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al duplicar workflow: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener opciones para crear workflows
     */
    public function getOptions(): JsonResponse
    {
        try {
            $gerencias = Gerencia::where('activa', true)->select('id', 'nombre')->get();

            $tipos = [
                ['value' => 'expediente', 'label' => 'Expediente'],
                ['value' => 'tramite', 'label' => 'Trámite'],
                ['value' => 'proceso', 'label' => 'Proceso']
            ];

            $tiposPaso = [
                ['value' => 'normal', 'label' => 'Normal'],
                ['value' => 'inicio', 'label' => 'Inicio'],
                ['value' => 'fin', 'label' => 'Fin'],
                ['value' => 'decision', 'label' => 'Decisión'],
                ['value' => 'paralelo', 'label' => 'Paralelo']
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'gerencias' => $gerencias,
                    'tipos_workflow' => $tipos,
                    'tipos_paso' => $tiposPaso
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener opciones: ' . $e->getMessage()
            ], 500);
        }
    }
}
