<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomWorkflowTransition extends Model
{
    protected $fillable = [
        'custom_workflow_id',
        'from_step_id',
        'to_step_id',
        'nombre',
        'descripcion',
        'condicion',
        'reglas',
        'automatica',
        'orden',
        'activo'
    ];

    protected $casts = [
        'reglas' => 'array',
        'automatica' => 'boolean',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(CustomWorkflow::class, 'custom_workflow_id');
    }

    public function fromStep(): BelongsTo
    {
        return $this->belongsTo(CustomWorkflowStep::class, 'from_step_id');
    }

    public function toStep(): BelongsTo
    {
        return $this->belongsTo(CustomWorkflowStep::class, 'to_step_id');
    }

    // Métodos útiles
    public function isAutomatic()
    {
        return $this->automatica;
    }

    public function canExecute($context = [])
    {
        if (!$this->activo) {
            return false;
        }

        if (empty($this->condicion)) {
            return true;
        }

        // Aquí se implementaría la lógica para evaluar condiciones
        // Por ahora, retornamos true si no hay condición específica
        return $this->evaluateCondition($context);
    }

    protected function evaluateCondition($context)
    {
        // Implementar evaluación de condiciones
        // Esto podría incluir expresiones simples o llamadas a servicios
        if (empty($this->condicion)) {
            return true;
        }

        // Ejemplo simple: condición como "estado == 'aprobado'"
        // Aquí iría la lógica de evaluación

        return true; // Placeholder
    }

    public function execute($expediente, $user = null)
    {
        // Ejecutar acciones automáticas si las hay
        if (!empty($this->reglas) && isset($this->reglas['acciones'])) {
            foreach ($this->reglas['acciones'] as $accion) {
                $this->executeAction($accion, $expediente, $user);
            }
        }

        // Actualizar el expediente al siguiente paso
        $expediente->update([
            'current_custom_step_id' => $this->to_step_id,
            'custom_step_started_at' => now(),
            'custom_step_deadline' => $this->calculateDeadline()
        ]);

        // Registrar en el historial
        $this->logTransition($expediente, $user);
    }

    protected function executeAction($action, $expediente, $user)
    {
        // Implementar ejecución de acciones automáticas
        // Ejemplos: enviar email, crear notificación, actualizar campos, etc.

        switch ($action['tipo']) {
            case 'email':
                // Enviar email
                break;
            case 'notification':
                // Crear notificación
                break;
            case 'update_field':
                // Actualizar campo del expediente
                break;
        }
    }

    protected function calculateDeadline()
    {
        $toStep = $this->toStep;
        if ($toStep && $toStep->tiempo_estimado) {
            return now()->addMinutes($toStep->tiempo_estimado);
        }

        return null;
    }

    protected function logTransition($expediente, $user)
    {
        $history = $expediente->custom_step_history ?? [];
        $history[] = [
            'from_step_id' => $this->from_step_id,
            'to_step_id' => $this->to_step_id,
            'transition_id' => $this->id,
            'executed_by' => $user ? $user->id : null,
            'executed_at' => now(),
            'automatic' => $this->automatica
        ];

        $expediente->update(['custom_step_history' => $history]);
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    public function scopeAutomaticas($query)
    {
        return $query->where('automatica', true);
    }

    public function scopePorWorkflow($query, $workflowId)
    {
        return $query->where('custom_workflow_id', $workflowId);
    }
}
