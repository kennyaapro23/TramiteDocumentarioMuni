<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $table = 'tipos_documento';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'campos_requeridos',
        'requiere_firma_digital',
        'requiere_notarizacion',
        'costo_tramitacion',
        'vigencia_dias',
        'activo'
    ];

    protected $casts = [
        'campos_requeridos' => 'array',
        'requiere_firma_digital' => 'boolean',
        'requiere_notarizacion' => 'boolean',
        'costo_tramitacion' => 'decimal:2',
        'activo' => 'boolean'
    ];

    // Constantes para tipos comunes
    const SOLICITUD = 'DOC-001';
    const MEMORIAL = 'DOC-002';
    const DECLARACION_JURADA = 'DOC-003';
    const COPIA_DNI = 'DOC-004';
    const COPIA_RUC = 'DOC-005';
    const PLANOS = 'DOC-006';
    const CERTIFICADO_PARAMETROS = 'DOC-007';
    const TITULO_PROPIEDAD = 'DOC-008';

    /**
     * Scope para documentos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Verificar si el documento está vigente
     */
    public function estaVigente($fechaEmision = null): bool
    {
        if (!$this->vigencia_dias) {
            return true; // Sin límite de vigencia
        }

        $fechaEmision = $fechaEmision ?? now();
        $fechaVencimiento = $fechaEmision->addDays($this->vigencia_dias);
        
        return now()->lte($fechaVencimiento);
    }

    /**
     * Obtener el costo total incluyendo tramitación
     */
    public function getCostoTotalAttribute(): float
    {
        return (float) $this->costo_tramitacion;
    }
}
