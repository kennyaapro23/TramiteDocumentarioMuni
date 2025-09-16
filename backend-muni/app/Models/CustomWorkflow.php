<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomWorkflow extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'tipo',
        'configuracion',
        'activo',
        'gerencia_id',
        'created_by'
    ];

    protected $casts = [
        'configuracion' => 'array',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function gerencia(): BelongsTo
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function steps(): HasMany
    {
        return $this->hasMany(CustomWorkflowStep::class)->orderBy('orden');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(CustomWorkflowTransition::class);
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'custom_workflow_id');
    }

    // Métodos útiles
    public function getStepById($stepId)
    {
        return $this->steps()->find($stepId);
    }

    public function getInitialStep()
    {
        return $this->steps()->where('tipo', 'inicio')->first() ??
               $this->steps()->orderBy('orden')->first();
    }

    public function getFinalSteps()
    {
        return $this->steps()->where('tipo', 'fin')->get();
    }

    public function getNextSteps($currentStepId)
    {
        return $this->transitions()
            ->where('from_step_id', $currentStepId)
            ->with('toStep')
            ->get()
            ->pluck('toStep');
    }

    public function getPreviousSteps($currentStepId)
    {
        return $this->transitions()
            ->where('to_step_id', $currentStepId)
            ->with('fromStep')
            ->get()
            ->pluck('fromStep');
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePorGerencia($query, $gerenciaId)
    {
        return $query->where('gerencia_id', $gerenciaId);
    }
}
