<?php

namespace App\Http\Controllers;

use App\Models\CustomWorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomWorkflowStepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workflowId = $request->query('workflow_id');
        
        if ($workflowId) {
            $steps = CustomWorkflowStep::where('custom_workflow_id', $workflowId)
                ->orderBy('orden')
                ->get();
        } else {
            $steps = CustomWorkflowStep::with('workflow')->get();
        }
        
        return response()->json($steps);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'custom_workflow_id' => 'required|exists:custom_workflows,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:inicio,proceso,decision,fin',
            'orden' => 'required|integer|min:1',
            'configuracion' => 'nullable|json',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $step = CustomWorkflowStep::create($request->all());
            return response()->json([
                'message' => 'Paso creado exitosamente',
                'data' => $step->load('workflow')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el paso',
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
            $step = CustomWorkflowStep::with(['workflow', 'transitionsFrom', 'transitionsTo'])->findOrFail($id);
            return response()->json($step);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Paso no encontrado'
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
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'sometimes|in:inicio,proceso,decision,fin',
            'orden' => 'sometimes|integer|min:1',
            'configuracion' => 'nullable|json',
            'activo' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Datos de entrada inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $step = CustomWorkflowStep::findOrFail($id);
            $step->update($request->all());
            
            return response()->json([
                'message' => 'Paso actualizado exitosamente',
                'data' => $step->load('workflow')
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Paso no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el paso',
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
            $step = CustomWorkflowStep::findOrFail($id);
            $step->delete();
            
            return response()->json([
                'message' => 'Paso eliminado exitosamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Paso no encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el paso',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
