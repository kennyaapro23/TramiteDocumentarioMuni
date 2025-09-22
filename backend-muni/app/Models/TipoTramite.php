<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoTramite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo',
        'gerencia_id',
        'documentos_requeridos',
        'costo',
        'tiempo_estimado_dias',
        'requiere_pago',
        'activo'
    ];

    protected $casts = [
        'documentos_requeridos' => 'array',
        'costo' => 'decimal:2',
        'requiere_pago' => 'boolean',
        'activo' => 'boolean'
    ];

    // Scope para obtener solo los tipos activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class);
    }
}
