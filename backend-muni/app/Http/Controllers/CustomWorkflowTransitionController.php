<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkflowTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomWorkflowTransitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workflowId = $request->query('workflow_id');
        $fromStepId = $request->query('from_step_id');
        
        $query = CustomWorkflowTransition::with(['fromStep', 'toStep', 'workflow']);
        
        if ($workflowId) {
            $query->where('custom_workflow_id', $workflowId);
        }
        
        if ($fromStepId) {
            $query->where('from_step_id', $fromStepId);
        }
        
        $transitions = $query->get();
        return response()->json($transitions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_workflow_id' => 'required|exists:custom_workflows,id',
            'from_step_id' => 'required|exists:custom_workflow_steps,id',
            'to_step_id' => 'required|exists:custom_workflow_steps,id|different:from_step_id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'condicion' => 'nullable|json',
            'orden' => 'required|integer|min:1',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transition = CustomWorkflowTransition::create($request->all());
            return response()->json([
                'message' => 'Transición creada exitosamente',
                'data' => $transition->load(['fromStep', 'toStep', 'workflow'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la transición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transition = CustomWorkflowTransition::with(['fromStep', 'toStep', 'workflow'])->findOrFail($id);
            return response()->json($transition);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transición no encontrada'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'custom_workflow_id' => 'sometimes|exists:custom_workflows,id',
            'from_step_id' => 'sometimes|exists:custom_workflow_steps,id',
            'to_step_id' => 'sometimes|exists:custom_workflow_steps,id|different:from_step_id',
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'condicion' => 'nullable|json',
            'orden' => 'sometimes|integer|min:1',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $transition = CustomWorkflowTransition::findOrFail($id);
            $transition->update($request->all());
            
            return response()->json([
                'message' => 'Transición actualizada exitosamente',
                'data' => $transition->load(['fromStep', 'toStep', 'workflow'])
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transición no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la transición',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $transition = CustomWorkflowTransition::findOrFail($id);
            $transition->delete();
            
            return response()->json([
                'message' => 'Transición eliminada exitosamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Transición no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la transición',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
