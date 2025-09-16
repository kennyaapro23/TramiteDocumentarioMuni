<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoTramite extends Model
{
    use HasFactory;

    protected $table = 'tipos_tramite';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'gerencia_id',
        'documentos_requeridos',
        'flujo_tramite',
        'tiempo_estimado_dias',
        'costo_total',
        'prioridad',
        'requiere_inspeccion',
        'requiere_informe_tecnico',
        'requiere_informe_legal',
        'activo'
    ];

    protected $casts = [
        'documentos_requeridos' => 'array',
        'flujo_tramite' => 'array',
        'costo_total' => 'decimal:2',
        'requiere_inspeccion' => 'boolean',
        'requiere_informe_tecnico' => 'boolean',
        'requiere_informe_legal' => 'boolean',
        'activo' => 'boolean'
    ];

    // Constantes para prioridades
    const PRIORIDAD_BAJA = 'baja';
    const PRIORIDAD_MEDIA = 'media';
    const PRIORIDAD_ALTA = 'alta';
    const PRIORIDAD_URGENTE = 'urgente';

    // Constantes para tipos comunes
    const LICENCIA_FUNCIONAMIENTO = 'TRAM-001';
    const LICENCIA_EDIFICACION = 'TRAM-002';
    const PERMISO_CONSTRUCCION = 'TRAM-003';
    const AUTORIZACION_EVENTO = 'TRAM-004';

    /**
     * Gerencia responsable del trámite
     */
    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    /**
     * Documentos que requiere este tipo de trámite
     */
    public function documentosRequeridos(): BelongsToMany
    {
        return $this->belongsToMany(TipoDocumento::class, 'tramite_documento', 'tipo_tramite_id', 'tipo_documento_id');
    }

    /**
     * Registros de mesa de partes de este tipo
     */
    public function mesaPartes(): HasMany
    {
        return $this->hasMany(MesaParte::class, 'tipo_tramite_id');
    }

    /**
     * Scope para trámites activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope por prioridad
     */
    public function scopePorPrioridad($query, $prioridad)
    {
        return $query->where('prioridad', $prioridad);
    }

    /**
     * Scope por gerencia
     */
    public function scopePorGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_id', $gerenciaId);
    }

    /**
     * Obtener array de prioridades
     */
    public static function getPrioridades(): array
    {
        return [
            self::PRIORIDAD_BAJA => 'Baja',
            self::PRIORIDAD_MEDIA => 'Media', 
            self::PRIORIDAD_ALTA => 'Alta',
            self::PRIORIDAD_URGENTE => 'Urgente'
        ];
    }

    /**
     * Verificar si requiere inspección técnica
     */
    public function requiereInspeccionTecnica(): bool
    {
        return $this->requiere_inspeccion;
    }

    /**
     * Verificar si requiere informe técnico
     */
    public function requiereInformeTecnico(): bool
    {
        return $this->requiere_informe_tecnico;
    }

    /**
     * Verificar si requiere informe legal
     */
    public function requiereInformeLegal(): bool
    {
        return $this->requiere_informe_legal;
    }

    /**
     * Obtener tiempo estimado en días
     */
    public function getTiempoEstimadoAttribute(): int
    {
        return $this->tiempo_estimado_dias;
    }

    /**
     * Calcular fecha estimada de finalización
     */
    public function calcularFechaEstimada($fechaInicio = null): \Carbon\Carbon
    {
        $fechaInicio = $fechaInicio ?? now();
        return $fechaInicio->addDays($this->tiempo_estimado_dias);
    }
}
