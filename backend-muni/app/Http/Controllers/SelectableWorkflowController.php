<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkflow;
use App\Models\Gerencia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectableWorkflowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:gestionar_workflows');
    }

    /**
     * Mostrar formulario para crear flujo seleccionable
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
        
        $tiposTramite = \App\Models\TipoTramite::where('activo', true)
                                            ->orderBy('nombre')
                                            ->get();
        
        $usuarios = User::select('id', 'name', 'email')->orderBy('name')->get();
        
        return view('workflows.create-selectable', compact('gerenciasPrincipales', 'todasGerencias', 'usuarios', 'tiposTramite'));
    }

    /**
     * Crear flujo basado en gerencias seleccionadas
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_tramite_id' => 'required|exists:tipo_tramites,id',
            'steps' => 'required|array|min:1',
            'steps.*.nombre' => 'required|string',
            'steps.*.gerencia_id' => 'required|exists:gerencias,id',
            'steps.*.orden' => 'required|integer',
            'responsables' => 'array',
            'responsables.*' => 'nullable|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            // Obtener el tipo de trámite seleccionado
            $tipoTramite = \App\Models\TipoTramite::findOrFail($validated['tipo_tramite_id']);
            
            // Crear el workflow
            $workflow = CustomWorkflow::create([
                'nombre' => "Flujo para {$tipoTramite->nombre}",
                'descripcion' => "Flujo de trabajo para el trámite: {$tipoTramite->nombre}",
                'tipo' => 'tramite',
                'gerencia_id' => $validated['steps'][0]['gerencia_id'], // Primera gerencia como principal
                'activo' => true,
                'created_by' => auth()->id(),
                'codigo' => $this->generateCode()
            ]);

            // Crear los pasos basados en gerencias seleccionadas
            foreach ($validated['steps'] as $index => $step) {
                $responsableId = $request->input("responsables.{$index}");
                
                $workflow->steps()->create([
                    'nombre' => $step['nombre'],
                    'descripcion' => "Proceso en {$step['nombre']}",
                    'orden' => $step['orden'],
                    'gerencia_id' => $step['gerencia_id'],
                    'usuario_responsable_id' => $responsableId,
                    'tiempo_estimado' => 3 // días por defecto
                ]);
            }

            DB::commit();

            return redirect()->route('workflows.index')
                           ->with('success', 'Flujo creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                         ->with('error', 'Error al crear el flujo: ' . $e->getMessage());
        }
    }

    /**
     * API para obtener gerencias disponibles
     */
    public function getGerencias()
    {
        $gerencias = Gerencia::where('activo', true)
                            ->select('id', 'nombre')
                            ->orderBy('nombre')
                            ->get();
                            
        return response()->json($gerencias);
    }

    /**
     * API para obtener usuarios por gerencia
     */
    public function getUsersByGerencia($gerenciaId)
    {
        $usuarios = User::where('gerencia_id', $gerenciaId)
                       ->where('activo', true)
                       ->select('id', 'name', 'email')
                       ->orderBy('name')
                       ->get();
                       
        return response()->json($usuarios);
    }

    private function generateCode()
    {
        $lastId = CustomWorkflow::max('id') ?? 0;
        return 'FL' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
    }
}