<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use App\Models\CustomWorkflow;
use App\Models\Gerencia;
use App\Models\TipoTramite;
use App\Models\User;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gestionar_workflows')->only('index');
        $this->middleware('permission:crear_workflows')->only(['create', 'store']);
        $this->middleware('permission:editar_workflows')->only(['edit', 'update']);
        $this->middleware('permission:eliminar_workflows')->only('destroy');
    }

    /**
     * Display a listing of workflows.
     */
    public function index(Request $request)
    {
        // Solo usar CustomWorkflow ya que no existe tabla workflows estándar
        $query = CustomWorkflow::with(['gerencia', 'steps'])
            ->when($request->search, function($q, $search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            })
            ->when($request->gerencia, function($q, $gerencia) {
                $q->where('gerencia_id', $gerencia);
            })
            ->when($request->status !== null, function($q) use ($request) {
                $q->where('activo', $request->status);
            })
            ->orderBy('created_at', 'desc');

        $workflows = $query->paginate(10);

        // Estadísticas
        $stats = [
            'total' => CustomWorkflow::count(),
            'activos' => CustomWorkflow::where('activo', true)->count(),
            'en_progreso' => 0, // Placeholder - ajustar según expedientes
            'total_pasos' => CustomWorkflow::with('steps')->get()->sum(function($workflow) {
                return $workflow->steps->count();
            })
        ];

        $gerencias = Gerencia::orderBy('nombre')->get();

        return view('workflows.index', compact('workflows', 'stats', 'gerencias'));
    }

    /**
     * Show the form for creating a new workflow.
     */
    public function create()
    {
        // Gerencias principales (sin padre)
        $gerenciasPrincipales = Gerencia::with(['responsable', 'subgerencias.responsable'])
                                      ->whereNull('gerencia_padre_id')
                                      ->where('activo', true)
                                      ->orderBy('nombre')
                                      ->get();
        
        // Todas las gerencias para el JavaScript
        $todasGerencias = Gerencia::with('responsable')
                                ->where('activo', true)
                                ->orderBy('nombre')
                                ->get();
        
        $tiposTramite = \App\Models\TipoTramite::with('gerencia.responsable')
                                            ->where('activo', true)
                                            ->orderBy('nombre')
                                            ->get();
        
        $usuarios = User::select('id', 'name', 'email')->orderBy('name')->get();
        
        return view('workflows.create', compact('gerenciasPrincipales', 'todasGerencias', 'usuarios', 'tiposTramite'));
    }

    /**
     * Store a newly created workflow.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:expediente,tramite,proceso',
            'gerencia_id' => 'required|exists:gerencias,id',
            'activo' => 'boolean',
            'steps' => 'required|array|min:1',
            'steps.*.nombre' => 'required|string|max:255',
            'steps.*.descripcion' => 'nullable|string',
            'steps.*.orden' => 'required|integer|min:1',
            'steps.*.usuario_responsable' => 'nullable|exists:users,id',
            'steps.*.tiempo_limite_dias' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Crear el workflow personalizado
            $workflow = CustomWorkflow::create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'gerencia_id' => $validated['gerencia_id'],
                'activo' => $validated['activo'] ?? true,
                'created_by' => auth()->id(),
                'codigo' => 'CW' . str_pad(CustomWorkflow::max('id') + 1, 4, '0', STR_PAD_LEFT),
                'tipo' => $validated['tipo']
            ]);

            // Crear los pasos
            foreach ($validated['steps'] as $stepData) {
                $workflow->steps()->create([
                    'nombre' => $stepData['nombre'],
                    'descripcion' => $stepData['descripcion'],
                    'orden' => $stepData['orden'],
                    'usuario_responsable_id' => $stepData['usuario_responsable'],
                    'tiempo_estimado' => $stepData['tiempo_limite_dias'],
                ]);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Workflow creado exitosamente.',
                    'data' => $workflow
                ], 201);
            }

            return redirect()->route('workflows.index')
                           ->with('success', 'Workflow creado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el workflow: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                         ->with('error', 'Error al crear el workflow: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified workflow.
     */
    public function show($id)
    {
        // Solo buscar en CustomWorkflow
        $workflow = CustomWorkflow::with(['gerencia', 'steps.usuarioResponsable', 'creador'])->findOrFail($id);

        // Estadísticas específicas del workflow
        $stats = [
            'expedientes_procesados' => 0, // Placeholder
            'expedientes_activos' => 0, // Placeholder
            'tiempo_promedio' => 0, // Placeholder
            'total_pasos' => $workflow->steps->count(),
        ];

        return view('workflows.show', compact('workflow', 'stats'));
    }

    /**
     * Show the form for editing the workflow.
     */
    public function edit($id)
    {
        // Solo buscar en CustomWorkflow
        $workflow = CustomWorkflow::with(['steps'])->findOrFail($id);
        $isCustom = true; // Siempre será true ya que solo manejamos CustomWorkflow

        $gerencias = Gerencia::orderBy('nombre')->get();
        
        return view('workflows.edit', compact('workflow', 'gerencias', 'isCustom'));
    }

    /**
     * Update the specified workflow.
     */
    public function update(Request $request, $id)
    {
        // Solo buscar en CustomWorkflow
        $workflow = CustomWorkflow::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:expediente,tramite,proceso',
            'gerencia_id' => 'required|exists:gerencias,id',
            'activo' => 'boolean',
            'steps' => 'required|array|min:1',
            'steps.*.nombre' => 'required|string|max:255',
            'steps.*.descripcion' => 'nullable|string',
            'steps.*.orden' => 'required|integer|min:1',
            'steps.*.usuario_responsable' => 'nullable|exists:users,id',
            'steps.*.tiempo_limite_dias' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $workflow->update([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'tipo' => $validated['tipo'],
                'gerencia_id' => $validated['gerencia_id'],
                'activo' => $validated['activo'] ?? true,
            ]);

            // Eliminar pasos existentes y crear nuevos
            $workflow->steps()->delete();
            
            foreach ($validated['steps'] as $stepData) {
                $workflow->steps()->create([
                    'nombre' => $stepData['nombre'],
                    'descripcion' => $stepData['descripcion'],
                    'orden' => $stepData['orden'],
                    'usuario_responsable_id' => $stepData['usuario_responsable'],
                    'tiempo_estimado' => $stepData['tiempo_limite_dias'],
                ]);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Workflow actualizado exitosamente.',
                    'data' => $workflow->fresh(['steps'])
                ]);
            }

            return redirect()->route('workflows.index')
                           ->with('success', 'Workflow actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el workflow: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()
                         ->with('error', 'Error al actualizar el workflow: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified workflow.
     */
    public function destroy($id)
    {
        // Solo buscar en CustomWorkflow
        $workflow = CustomWorkflow::findOrFail($id);

        try {
            // Verificar si hay expedientes utilizando este workflow
            $expedientesCount = $workflow->expedientes()->count();
            
            if ($expedientesCount > 0) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se puede eliminar el workflow porque tiene {$expedientesCount} expediente(s) asociado(s)."
                    ], 422);
                }

                return back()->with('error', "No se puede eliminar el workflow porque tiene {$expedientesCount} expediente(s) asociado(s).");
            }

            $workflow->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Workflow eliminado exitosamente.'
                ]);
            }

            return redirect()->route('workflows.index')
                           ->with('success', 'Workflow eliminado exitosamente.');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el workflow: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error al eliminar el workflow: ' . $e->getMessage());
        }
    }
}