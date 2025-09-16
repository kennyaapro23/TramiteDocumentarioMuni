<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomWorkflowStep extends Model
{
    protected $fillable = [
        'custom_workflow_id',
        'nombre',
        'codigo',
        'descripcion',
        'orden',
        'tipo',
        'configuracion',
        'condiciones',
        'acciones',
        'requiere_aprobacion',
        'tiempo_estimado',
        'responsable_tipo',
        'responsable_id',
        'activo'
    ];

    protected $casts = [
        'configuracion' => 'array',
        'condiciones' => 'array',
        'acciones' => 'array',
        'requiere_aprobacion' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(CustomWorkflow::class, 'custom_workflow_id');
    }

    public function transitionsFrom(): HasMany
    {
        return $this->hasMany(CustomWorkflowTransition::class, 'from_step_id');
    }

    public function transitionsTo(): HasMany
    {
        return $this->hasMany(CustomWorkflowTransition::class, 'to_step_id');
    }

    public function expedientes(): HasMany
    {
        return $this->hasMany(Expediente::class, 'current_custom_step_id');
    }

    // Relación polimórfica para responsable
    public function responsable()
    {
        return $this->morphTo();
    }

    // Métodos útiles
    public function getNextSteps()
    {
        return $this->transitionsFrom()->with('toStep')->get()->pluck('toStep');
    }

    public function getPreviousSteps()
    {
        return $this->transitionsTo()->with('fromStep')->get()->pluck('fromStep');
    }

    public function canTransitionTo($targetStepId)
    {
        return $this->transitionsFrom()->where('to_step_id', $targetStepId)->exists();
    }

    public function getAvailableTransitions()
    {
        return $this->transitionsFrom()->where('activo', true)->get();
    }

    public function isInitialStep()
    {
        return $this->tipo === 'inicio';
    }

    public function isFinalStep()
    {
        return $this->tipo === 'fin';
    }

    public function requiresApproval()
    {
        return $this->requiere_aprobacion;
    }

    public function getResponsibleUser()
    {
        if ($this->responsable_tipo === 'usuario') {
            return User::find($this->responsable_id);
        } elseif ($this->responsable_tipo === 'rol') {
            return Role::find($this->responsable_id);
        } elseif ($this->responsable_tipo === 'gerencia') {
            return Gerencia::find($this->responsable_id);
        }

        return null;
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

    public function scopePorWorkflow($query, $workflowId)
    {
        return $query->where('custom_workflow_id', $workflowId);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }
}
