<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Expediente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expedientes';

    // Estados del expediente
    const STATUS_INICIADO = 'iniciado';
    const STATUS_EN_PROCESO = 'en_proceso';
    const STATUS_OBSERVADO = 'observado';
    const STATUS_APROBADO = 'aprobado';
    const STATUS_RECHAZADO = 'rechazado';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_ARCHIVADO = 'archivado';

    protected $fillable = [
        'tracking_number',
        'citizen_id',
        'procedure_id',
        'gerencia_id',
        'status',
        'priority',
        'subject',
        'description',
        'expected_response_date',
        'actual_response_date',
        'notes',
        'total_amount',
        'payment_status',
        'assigned_to',
        'metadata'
    ];

    protected $casts = [
        'expected_response_date' => 'datetime',
        'actual_response_date' => 'datetime',
        'metadata' => 'array',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relaciones
    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function files()
    {
        return $this->hasMany(ExpedientFile::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function actionLogs()
    {
        return $this->hasMany(ActionLog::class);
    }

    public function historial()
    {
        return $this->hasMany(HistorialExpediente::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    // Métodos auxiliares
    public function isOverdue()
    {
        if (!$this->expected_response_date) {
            return false;
        }

        return Carbon::now()->isAfter($this->expected_response_date) && 
               !in_array($this->status, [self::STATUS_FINALIZADO, self::STATUS_ARCHIVADO]);
    }

    public function getDaysUntilDue()
    {
        if (!$this->expected_response_date) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expected_response_date, false);
    }

    public function generateTrackingNumber()
    {
        $year = date('Y');
        $sequence = static::whereYear('created_at', $year)->count() + 1;
        $gerenciaCode = $this->gerencia ? $this->gerencia->code : 'GEN';
        
        return sprintf('%s-%04d-%06d', $gerenciaCode, $year, $sequence);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_id', $gerenciaId);
    }

    public function scopeByCitizen($query, $citizenId)
    {
        return $query->where('citizen_id', $citizenId);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_response_date', '<', Carbon::now())
                    ->whereNotIn('status', [self::STATUS_FINALIZADO, self::STATUS_ARCHIVADO]);
    }

    public function scopeByTrackingNumber($query, $trackingNumber)
    {
        return $query->where('tracking_number', $trackingNumber);
    }

    /**
     * Asignar automáticamente el expediente a una gerencia basándose en reglas de flujo
     */
    public function asignarAutomaticamente()
    {
        // Buscar una regla de flujo apropiada
        $regla = WorkflowRule::encontrarReglaParaTramite(
            $this->procedure->nombre ?? 'general',
            $this->subject
        );

        if ($regla) {
            $this->gerencia_id = $regla->gerencia_destino_id;
            $this->save();

            // Registrar en el historial
            HistorialExpediente::create([
                'expediente_id' => $this->id,
                'previous_status' => null,
                'new_status' => $this->status,
                'changed_by' => auth()->id(),
                'change_reason' => "Asignado automáticamente a {$regla->gerenciaDestino->nombre} por regla: {$regla->nombre_regla}",
                'change_date' => now(),
                'metadata' => [
                    'workflow_rule_id' => $regla->id,
                    'assignment_type' => 'automatic'
                ]
            ]);

            return $regla;
        }

        return null;
    }

    /**
     * Método estático para asignar automáticamente durante la creación
     */
    public static function crearConAsignacionAutomatica(array $datos)
    {
        $expediente = static::create($datos);
        $expediente->asignarAutomaticamente();
        $expediente->iniciarFlujoEtapas();
        return $expediente;
    }

    /**
     * Relación con el progreso de etapas del workflow
     */
    public function workflowProgress()
    {
        return $this->hasMany(ExpedienteWorkflowProgress::class);
    }

    /**
     * Iniciar el flujo de etapas después de asignar a gerencia
     */
    public function iniciarFlujoEtapas()
    {
        if (!$this->gerencia_id) {
            return false;
        }

        // Obtener etapas de la gerencia
        $etapas = WorkflowStep::etapasDeGerencia($this->gerencia_id);
        
        foreach ($etapas as $etapa) {
            ExpedienteWorkflowProgress::create([
                'expediente_id' => $this->id,
                'workflow_step_id' => $etapa->id,
                'estado' => $etapa->orden === 1 ? 
                    ExpedienteWorkflowProgress::ESTADO_PENDIENTE : 
                    ExpedienteWorkflowProgress::ESTADO_PENDIENTE,
                'fecha_limite' => now()->addDays($etapa->dias_limite)
            ]);
        }

        // Iniciar la primera etapa
        $primeraEtapa = $this->workflowProgress()->first();
        if ($primeraEtapa) {
            $primeraEtapa->iniciar();
        }

        return true;
    }

    /**
     * Activar la siguiente etapa en la secuencia
     */
    public function activarSiguienteEtapa(WorkflowStep $siguienteEtapa)
    {
        $progreso = $this->workflowProgress()
            ->where('workflow_step_id', $siguienteEtapa->id)
            ->first();

        if ($progreso) {
            $progreso->iniciar();
        }
    }

    /**
     * Obtener la etapa actual en proceso
     */
    public function etapaActual()
    {
        return $this->workflowProgress()
            ->with('workflowStep')
            ->where('estado', ExpedienteWorkflowProgress::ESTADO_EN_PROCESO)
            ->first();
    }

    /**
     * Obtener todas las etapas pendientes
     */
    public function etapasPendientes()
    {
        return $this->workflowProgress()
            ->with('workflowStep')
            ->where('estado', ExpedienteWorkflowProgress::ESTADO_PENDIENTE)
            ->get();
    }

    /**
     * Verificar si puede avanzar a la siguiente etapa
     */
    public function puedeAvanzar()
    {
        $etapaActual = $this->etapaActual();
        if (!$etapaActual) {
            return false;
        }

        // Verificar si la etapa anterior está aprobada
        $etapaAnterior = $etapaActual->workflowStep->etapaAnterior();
        if ($etapaAnterior) {
            $progresoAnterior = $this->workflowProgress()
                ->where('workflow_step_id', $etapaAnterior->id)
                ->first();
            
            return $progresoAnterior && 
                   $progresoAnterior->estado === ExpedienteWorkflowProgress::ESTADO_APROBADO;
        }

        return true; // Primera etapa siempre puede proceder
    }

    // Eventos del modelo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expediente) {
            if (!$expediente->tracking_number) {
                $expediente->tracking_number = $expediente->generateTrackingNumber();
            }
        });

        static::updating(function ($expediente) {
            // Registrar cambios en el historial
            if ($expediente->isDirty('status')) {
                HistorialExpediente::create([
                    'expediente_id' => $expediente->id,
                    'previous_status' => $expediente->getOriginal('status'),
                    'new_status' => $expediente->status,
                    'changed_by' => auth()->id(),
                    'change_reason' => 'Cambio de estado',
                    'change_date' => now()
                ]);
            }
        });
    }
}
