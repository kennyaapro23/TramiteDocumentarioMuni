<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion', 
        'codigo',
        'campos_requeridos',
        'requiere_firma',
        'vigencia_dias',
        'activo'
    ];

    protected $casts = [
        'campos_requeridos' => 'array',
        'requiere_firma' => 'boolean',
        'activo' => 'boolean'
    ];

    public function mesaPartes(): HasMany
    {
        return $this->hasMany(MesaParte::class);
    }
}