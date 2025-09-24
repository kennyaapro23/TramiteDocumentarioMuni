<?php

namespace App\Http\Controllers;

use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\TipoDocumento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TipoTramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        
        // Permisos específicos para cada acción
        $this->middleware(['permission:gestionar_tipos_tramite'])->only(['create', 'store']);
        $this->middleware(['permission:editar_tipos_tramite'])->only(['edit', 'update', 'toggleStatus']);
        $this->middleware(['permission:eliminar_tipos_tramite'])->only(['destroy']);
    }

    /**
     * Display a listing of tipo tramites.
     */
    public function index()
    {
        $tipoTramites = TipoTramite::with(['gerencia'])
                                  ->orderBy('nombre')
                                  ->paginate(15);

        $stats = [
            'total_tipos' => TipoTramite::count(),
            'tipos_activos' => TipoTramite::where('activo', true)->count(),
            'tipos_con_pago' => TipoTramite::where('requiere_pago', true)->count(),
            'tiempo_promedio' => TipoTramite::avg('tiempo_estimado_dias')
        ];

        // Si es una solicitud de API, devolver JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $tipoTramites,
                'stats' => $stats
            ]);
        }

        // Si es una solicitud web, devolver vista
        return view('tipos-tramite.index', compact('tipoTramites', 'stats'));
    }

    /**
     * Show the form for creating a new tipo tramite.
     */
    public function create()
    {
        $gerencias = Gerencia::where('activo', true)->orderBy('nombre')->get();
        $tiposDocumento = TipoDocumento::where('activo', true)->orderBy('nombre')->get();

        return view('tipos-tramite.create', compact('gerencias', 'tiposDocumento'));
    }

    /**
     * Store a newly created tipo tramite.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_tramites,nombre',
            'codigo' => 'nullable|string|max:10|unique:tipo_tramites,codigo',
            'descripcion' => 'nullable|string|max:1000',
            'gerencia_id' => 'required|exists:gerencias,id',
            'documentos_requeridos' => 'nullable|array',
            'documentos_requeridos.*' => 'integer|exists:tipo_documentos,id',
            'costo' => 'required|numeric|min:0',
            'tiempo_estimado_dias' => 'required|integer|min:1|max:365',
            'requiere_pago' => 'boolean',
            'activo' => 'boolean'
        ]);

        // Auto-generar código si no se especifica
        if (empty($validated['codigo'])) {
            $validated['codigo'] = 'TT' . str_pad(TipoTramite::max('id') + 1, 3, '0', STR_PAD_LEFT);
        }

        DB::beginTransaction();
        try {
            $tipoTramite = TipoTramite::create($validated);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de trámite creado exitosamente',
                    'data' => $tipoTramite->load('gerencia')
                ], 201);
            }

            return redirect()->route('tipos-tramite.index')
                           ->with('success', 'Tipo de trámite creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el tipo de trámite: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error al crear el tipo de trámite.']);
        }
    }

    /**
     * Display the specified tipo tramite.
     */
    public function show(TipoTramite $tipoTramite)
    {
        $tipoTramite->load(['gerencia', 'expedientes']);
        
        $stats = [
            'expedientes_procesados' => $tipoTramite->expedientes()->count(),
            'expedientes_completados' => $tipoTramite->expedientes()->where('estado', 'finalizado')->count(),
            'tiempo_promedio_real' => 0, // Se calculará cuando tengamos más data
            'ingresos_generados' => $tipoTramite->expedientes()->sum('monto_pagado') ?? 0
        ];

        // Si es una solicitud de API, devolver JSON
        if (request()->wantsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $tipoTramite,
                'stats' => $stats
            ]);
        }

        return view('tipos-tramite.show', compact('tipoTramite', 'stats'));
    }

    /**
     * Show the form for editing the specified tipo tramite.
     */
    public function edit(TipoTramite $tipoTramite)
    {
        $gerencias = Gerencia::where('activo', true)->orderBy('nombre')->get();
        $tiposDocumento = TipoDocumento::where('activo', true)->orderBy('nombre')->get();

        return view('tipos-tramite.edit', compact('tipoTramite', 'gerencias', 'tiposDocumento'));
    }

    /**
     * Update the specified tipo tramite.
     */
    public function update(Request $request, TipoTramite $tipoTramite)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_tramites,nombre,' . $tipoTramite->id,
            'codigo' => 'nullable|string|max:10|unique:tipo_tramites,codigo,' . $tipoTramite->id,
            'descripcion' => 'nullable|string|max:1000',
            'gerencia_id' => 'required|exists:gerencias,id',
            'documentos_requeridos' => 'nullable|array',
            'documentos_requeridos.*' => 'integer|exists:tipo_documentos,id',
            'costo' => 'required|numeric|min:0',
            'tiempo_estimado_dias' => 'required|integer|min:1|max:365',
            'requiere_pago' => 'boolean',
            'activo' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $tipoTramite->update($validated);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de trámite actualizado exitosamente',
                    'data' => $tipoTramite->load('gerencia')
                ]);
            }

            return redirect()->route('tipos-tramite.index')
                           ->with('success', 'Tipo de trámite actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el tipo de trámite: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Error al actualizar el tipo de trámite.']);
        }
    }

    /**
     * Remove the specified tipo tramite from storage.
     */
    public function destroy(TipoTramite $tipoTramite)
    {
        // Verificar que no tenga expedientes
        if ($tipoTramite->expedientes()->count() > 0) {
            $message = 'No se puede eliminar el tipo de trámite porque tiene expedientes asociados.';
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }

            return back()->withErrors(['error' => $message]);
        }

        DB::beginTransaction();
        try {
            $tipoTramite->delete();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de trámite eliminado exitosamente'
                ]);
            }

            return redirect()->route('tipos-tramite.index')
                           ->with('success', 'Tipo de trámite eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el tipo de trámite: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al eliminar el tipo de trámite: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle status of tipo tramite
     */
    public function toggleStatus(TipoTramite $tipoTramite)
    {
        DB::beginTransaction();
        try {
            $tipoTramite->update(['activo' => !$tipoTramite->activo]);
            
            DB::commit();

            $message = $tipoTramite->activo ? 
                      'Tipo de trámite activado exitosamente' : 
                      'Tipo de trámite desactivado exitosamente';

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $tipoTramite
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cambiar el estado: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Error al cambiar el estado.']);
        }
    }

    /**
     * Get tipos de tramite by gerencia
     */
    public function byGerencia(Gerencia $gerencia): JsonResponse
    {
        $tiposTramite = $gerencia->tipoTramites()
                               ->where('activo', true)
                               ->orderBy('nombre')
                               ->get();

        return response()->json([
            'success' => true,
            'data' => $tiposTramite
        ]);
    }
}