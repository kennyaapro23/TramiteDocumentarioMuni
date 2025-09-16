<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    protected $fillable = [
        'gerencia_id',
        'nombre_etapa',
        'descripcion',
        'orden',
        'rol_requerido',
        'dias_limite',
        'es_opcional',
        'requiere_documento',
        'condiciones_aprobacion',
        'activa',
        'created_by'
    ];

    protected $casts = [
        'es_opcional' => 'boolean',
        'requiere_documento' => 'boolean',
        'activa' => 'boolean',
        'condiciones_aprobacion' => 'array',
        'dias_limite' => 'integer',
        'orden' => 'integer'
    ];

    /**
     * Relación con la gerencia
     */
    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    /**
     * Relación con el usuario creador
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope para etapas activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    /**
     * Scope para ordenar por secuencia
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }

    /**
     * Obtener etapas secuenciales de una gerencia
     */
    public static function etapasDeGerencia($gerenciaId)
    {
        return static::where('gerencia_id', $gerenciaId)
            ->activas()
            ->ordenadas()
            ->get();
    }

    /**
     * Obtener la siguiente etapa en la secuencia
     */
    public function siguienteEtapa()
    {
        return static::where('gerencia_id', $this->gerencia_id)
            ->where('orden', '>', $this->orden)
            ->activas()
            ->orderBy('orden')
            ->first();
    }

    /**
     * Obtener la etapa anterior en la secuencia
     */
    public function etapaAnterior()
    {
        return static::where('gerencia_id', $this->gerencia_id)
            ->where('orden', '<', $this->orden)
            ->activas()
            ->orderBy('orden', 'desc')
            ->first();
    }
}
