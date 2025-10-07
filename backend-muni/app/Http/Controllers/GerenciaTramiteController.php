<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expediente;
use App\Models\Gerencia;
use App\Models\User;

class GerenciaTramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar trámites asignados a mi gerencia
     */
    public function misAsignados()
    {
        $user = auth()->user();
        
        // Obtener la gerencia del usuario
        $gerencia = $user->gerencia;
        
        if (!$gerencia) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes una gerencia asignada');
        }
        
        // Obtener expedientes de la gerencia
        $expedientes = Expediente::with([
            'tipoTramite',
            'gerencia',
            'currentStep',
            'workflow',
            'documentos'
        ])
        ->where('gerencia_id', $gerencia->id)
        ->whereIn('estado', ['pendiente', 'en_proceso', 'en_revision', 'observado'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return view('gerencia.tramites.mis-asignados', compact('expedientes', 'gerencia'));
    }

    /**
     * Ver detalles de un trámite asignado
     */
    public function show($id)
    {
        $user = auth()->user();
        $gerencia = $user->gerencia;
        
        if (!$gerencia) {
            abort(403, 'No tienes una gerencia asignada');
        }
        
        $expediente = Expediente::with([
            'tipoTramite.workflow.steps',
            'gerencia',
            'documentos',
            'historial.usuario',
            'workflowProgress',
            'currentStep',
            'usuarioRegistro'
        ])
        ->where('gerencia_id', $gerencia->id)
        ->findOrFail($id);
        
        return view('gerencia.tramites.show', compact('expediente'));
    }

    /**
     * Avanzar el trámite al siguiente paso
     */
    public function avanzar(Request $request, $id)
    {
        $validated = $request->validate([
            'observaciones' => 'nullable|string|max:1000',
            'siguiente_paso_id' => 'required|string'
        ]);
        
        $user = auth()->user();
        $gerencia = $user->gerencia;
        
        if (!$gerencia) {
            return redirect()->back()->with('error', 'No tienes una gerencia asignada');
        }
        
        $expediente = Expediente::where('gerencia_id', $gerencia->id)
            ->findOrFail($id);
        
        // Guardar paso anterior para el historial
        $pasoAnterior = $expediente->currentStep ? $expediente->currentStep->nombre : 'Inicio';
        
        // Si es finalización
        if ($validated['siguiente_paso_id'] === 'finalizado') {
            $expediente->estado = 'finalizado';
            $expediente->current_step_id = null;
            $expediente->save();
            
            // Registrar finalización en historial
            \App\Models\HistorialExpediente::create([
                'expediente_id' => $expediente->id,
                'accion' => 'Trámite Finalizado',
                'descripcion' => $validated['observaciones'] ?? 'Trámite completado exitosamente',
                'usuario_id' => $user->id,
            ]);
            
            return redirect()->route('gerencia.tramites.show', $expediente->id)
                ->with('success', '¡Trámite finalizado exitosamente!');
        }
        
        // Validar que el siguiente paso existe
        $siguientePaso = \App\Models\WorkflowStep::findOrFail($validated['siguiente_paso_id']);
        
        // Actualizar el paso actual
        $expediente->current_step_id = $siguientePaso->id;
        
        // Actualizar estado si es necesario
        if ($expediente->estado === 'pendiente') {
            $expediente->estado = 'en_proceso';
        }
        
        $expediente->save();
        
        // Registrar en historial
        \App\Models\HistorialExpediente::create([
            'expediente_id' => $expediente->id,
            'accion' => 'Avance de Flujo',
            'descripcion' => "Avanzó de '{$pasoAnterior}' a '{$siguientePaso->nombre}'. " . ($validated['observaciones'] ?? ''),
            'usuario_id' => $user->id,
        ]);
        
        return redirect()->route('gerencia.tramites.show', $expediente->id)
            ->with('success', "Trámite avanzado correctamente a: {$siguientePaso->nombre}");
    }
}
