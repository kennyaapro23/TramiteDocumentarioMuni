<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MesaParte extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mesa_partes';

    protected $fillable = [
        'numero_ingreso',
        'fecha_ingreso',
        'hora_ingreso',
        'solicitante_nombre',
        'solicitante_dni',
        'solicitante_telefono',
        'solicitante_email',
        'solicitante_direccion',
        'tipo_tramite_id',
        'documentos_presentados',
        'asunto',
        'observaciones_ingreso',
        'estado',
        'gerencia_destino_id',
        'usuario_derivacion_id',
        'fecha_derivacion',
        'observaciones_derivacion',
        'usuario_recepcion_id',
        'usuario_revision_id',
        'codigo_seguimiento',
        'historial_estados',
        'documentos_completos',
        'documentos_faltantes',
        'requiere_subsanacion',
        'fecha_limite_subsanacion'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'hora_ingreso' => 'datetime:H:i:s',
        'fecha_derivacion' => 'datetime',
        'fecha_limite_subsanacion' => 'date',
        'documentos_presentados' => 'array',
        'historial_estados' => 'array',
        'documentos_faltantes' => 'array',
        'documentos_completos' => 'boolean',
        'requiere_subsanacion' => 'boolean'
    ];

    // Constantes para estados
    const ESTADO_RECIBIDO = 'recibido';
    const ESTADO_EN_REVISION = 'en_revision';
    const ESTADO_DERIVADO = 'derivado';
    const ESTADO_OBSERVADO = 'observado';
    const ESTADO_DEVUELTO = 'devuelto';

    /**
     * Tipo de trámite
     */
    public function tipoTramite(): BelongsTo
    {
        return $this->belongsTo(TipoTramite::class, 'tipo_tramite_id');
    }

    /**
     * Gerencia de destino
     */
    public function gerenciaDestino(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_destino_id');
    }

    /**
     * Usuario que recibió el documento
     */
    public function usuarioRecepcion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_recepcion_id');
    }

    /**
     * Usuario que revisó el documento
     */
    public function usuarioRevision(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_revision_id');
    }

    /**
     * Usuario responsable en gerencia de destino
     */
    public function usuarioDerivacion(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_derivacion_id');
    }

    /**
     * Scope por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope por fecha de ingreso
     */
    public function scopePorFechaIngreso($query, $fecha)
    {
        return $query->whereDate('fecha_ingreso', $fecha);
    }

    /**
     * Scope por gerencia destino
     */
    public function scopePorGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_destino_id', $gerenciaId);
    }

    /**
     * Scope para documentos pendientes de subsanación
     */
    public function scopePendientesSubsanacion($query)
    {
        return $query->where('requiere_subsanacion', true)
                    ->where('fecha_limite_subsanacion', '>=', now());
    }

    /**
     * Scope para documentos vencidos de subsanación
     */
    public function scopeVencidosSubsanacion($query)
    {
        return $query->where('requiere_subsanacion', true)
                    ->where('fecha_limite_subsanacion', '<', now());
    }

    /**
     * Generar número de ingreso automático
     */
    public static function generarNumeroIngreso(): string
    {
        $año = date('Y');
        $ultimoNumero = self::whereYear('fecha_ingreso', $año)
                           ->count() + 1;
        
        return 'MP-' . $año . '-' . str_pad($ultimoNumero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generar código de seguimiento único
     */
    public static function generarCodigoSeguimiento(): string
    {
        do {
            $codigo = 'TRK-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('codigo_seguimiento', $codigo)->exists());

        return $codigo;
    }

    /**
     * Cambiar estado y registrar en historial
     */
    public function cambiarEstado($nuevoEstado, $observaciones = null, $usuarioId = null)
    {
        $estadoAnterior = $this->estado;
        $this->estado = $nuevoEstado;

        // Actualizar historial
        $historial = $this->historial_estados ?? [];
        $historial[] = [
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $nuevoEstado,
            'fecha_cambio' => now()->toISOString(),
            'usuario_id' => $usuarioId,
            'observaciones' => $observaciones
        ];
        
        $this->historial_estados = $historial;
        $this->save();

        return $this;
    }

    /**
     * Derivar a gerencia
     */
    public function derivarAGerencia($gerenciaId, $usuarioDerivacionId = null, $observaciones = null)
    {
        $this->gerencia_destino_id = $gerenciaId;
        $this->usuario_derivacion_id = $usuarioDerivacionId;
        $this->fecha_derivacion = now();
        $this->observaciones_derivacion = $observaciones;
        
        $this->cambiarEstado(self::ESTADO_DERIVADO, $observaciones);

        return $this;
    }

    /**
     * Marcar como observado por documentos faltantes
     */
    public function marcarObservado($documentosFaltantes, $fechaLimiteSubsanacion = null)
    {
        $this->documentos_faltantes = $documentosFaltantes;
        $this->requiere_subsanacion = true;
        $this->fecha_limite_subsanacion = $fechaLimiteSubsanacion ?? now()->addDays(10);
        
        $this->cambiarEstado(self::ESTADO_OBSERVADO, 'Documentos faltantes detectados');

        return $this;
    }

    /**
     * Verificar si está en plazo para subsanación
     */
    public function estaEnPlazoSubsanacion(): bool
    {
        if (!$this->requiere_subsanacion || !$this->fecha_limite_subsanacion) {
            return false;
        }

        return $this->fecha_limite_subsanacion >= now()->toDateString();
    }

    /**
     * Obtener array de estados disponibles
     */
    public static function getEstados(): array
    {
        return [
            self::ESTADO_RECIBIDO => 'Recibido',
            self::ESTADO_EN_REVISION => 'En Revisión',
            self::ESTADO_DERIVADO => 'Derivado',
            self::ESTADO_OBSERVADO => 'Observado',
            self::ESTADO_DEVUELTO => 'Devuelto'
        ];
    }
}
