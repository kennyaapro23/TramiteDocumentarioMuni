<?php

namespace App\Http\Controllers;

use App\Models\Gerencia;
use App\Models\Subgerencia;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GerenciaController extends Controller
{
    /**
     * Display a listing of gerencias.
     */
    public function index(): JsonResponse
    {
        $gerencias = Gerencia::with('subgerencias')
                            ->activas()
                            ->ordenadas()
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $gerencias
        ]);
    }

    /**
     * Display the specified gerencia.
     */
    public function show(Gerencia $gerencia): JsonResponse
    {
        $gerencia->load('subgerencias.activas');

        return response()->json([
            'success' => true,
            'data' => $gerencia
        ]);
    }

    /**
     * Get subgerencias of a gerencia.
     */
    public function subgerencias(Gerencia $gerencia): JsonResponse
    {
        $subgerencias = $gerencia->subgerencias()
                                ->activas()
                                ->ordenadas()
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $subgerencias
        ]);
    }

    /**
     * Get all subgerencias.
     */
    public function allSubgerencias(): JsonResponse
    {
        $subgerencias = Subgerencia::with('gerencia')
                                  ->activas()
                                  ->ordenadas()
                                  ->get();

        return response()->json([
            'success' => true,
            'data' => $subgerencias
        ]);
    }

    /**
     * Get gerencias for expediente creation.
     */
    public function forExpediente(): JsonResponse
    {
        $gerencias = Gerencia::with(['subgerencias' => function($query) {
                                    $query->activas()->ordenadas();
                                }])
                            ->activas()
                            ->ordenadas()
                            ->get();

        return response()->json([
            'success' => true,
            'data' => $gerencias
        ]);
    }
}
