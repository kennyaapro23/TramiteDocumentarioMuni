<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expediente;
use App\Models\TipoTramite;
use App\Models\Gerencia;
use App\Models\DocumentoExpediente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CiudadanoTramiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ciudadano')->only(['create', 'store']);
    }

    /**
     * Mostrar lista de trámites disponibles para el ciudadano
     */
    public function index()
    {
        $tiposTramite = TipoTramite::with('gerencia')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('ciudadano.tramites.index', compact('tiposTramite'));
    }

    /**
     * Mostrar formulario para solicitar un trámite
     */
    public function create(Request $request)
    {
        $tipoTramiteId = $request->query('tipo_tramite_id');
        
        if (!$tipoTramiteId) {
            return redirect()->route('ciudadano.tramites.index')
                ->with('error', 'Debe seleccionar un tipo de trámite.');
        }

        $tipoTramite = TipoTramite::with(['gerencia', 'documentos'])->findOrFail($tipoTramiteId);

        return view('ciudadano.tramites.create', compact('tipoTramite'));
    }

    /**
     * Almacenar una nueva solicitud de trámite
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_tramite_id' => 'required|exists:tipo_tramites,id',
            'asunto' => 'required|string|max:500',
            'descripcion' => 'required|string',
            'documentos' => 'required|array|min:1',
            'documentos.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
        ], [
            'tipo_tramite_id.required' => 'Debe seleccionar un tipo de trámite.',
            'asunto.required' => 'El asunto es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'documentos.required' => 'Debe adjuntar al menos un documento.',
            'documentos.*.mimes' => 'Los documentos deben ser PDF, imágenes o documentos Word.',
            'documentos.*.max' => 'Cada documento no debe superar los 10MB.',
        ]);

        DB::beginTransaction();
        
        try {
            $tipoTramite = TipoTramite::with('gerencia')->findOrFail($validated['tipo_tramite_id']);
            
            // Crear el expediente
            $expediente = Expediente::create([
                'numero_expediente' => $this->generarNumeroExpediente(),
                'tipo_tramite_id' => $tipoTramite->id,
                'solicitante_id' => auth()->id(),
                'asunto' => $validated['asunto'],
                'descripcion' => $validated['descripcion'],
                'estado' => 'ingresado',
                'prioridad' => 'normal',
                'gerencia_id' => $tipoTramite->gerencia_id,
                'fecha_ingreso' => now(),
                'requiere_pago' => $tipoTramite->requiere_pago,
                'monto' => $tipoTramite->costo,
            ]);

            // Guardar documentos adjuntos
            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $index => $file) {
                    $path = $file->store('expedientes/' . $expediente->id, 'public');
                    
                    DocumentoExpediente::create([
                        'expediente_id' => $expediente->id,
                        'nombre_archivo' => $file->getClientOriginalName(),
                        'ruta_archivo' => $path,
                        'tipo_archivo' => $file->getClientMimeType(),
                        'tamano_archivo' => $file->getSize(),
                        'subido_por' => auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ciudadano.tramites.mis-tramites')
                ->with('success', "Trámite creado exitosamente. Número de expediente: {$expediente->numero_expediente}");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el trámite: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar los trámites del ciudadano actual
     */
    public function misTramites()
    {
        $expedientes = Expediente::with(['tipoTramite', 'gerencia', 'documentos'])
            ->where('solicitante_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ciudadano.tramites.mis-tramites', compact('expedientes'));
    }

    /**
     * Mostrar detalle de un trámite del ciudadano
     */
    public function show($id)
    {
        $expediente = Expediente::with(['tipoTramite', 'gerencia', 'documentos', 'historial'])
            ->where('solicitante_id', auth()->id())
            ->findOrFail($id);

        return view('ciudadano.tramites.show', compact('expediente'));
    }

    /**
     * Generar número de expediente único
     */
    private function generarNumeroExpediente()
    {
        $year = date('Y');
        $lastExpediente = Expediente::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastExpediente 
            ? (int)substr($lastExpediente->numero_expediente, -6) + 1 
            : 1;

        return 'EXP-' . $year . '-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
