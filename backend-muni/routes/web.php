<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\GerenciaController;
use App\Http\Controllers\TipoTramiteController;
use App\Http\Controllers\TramiteController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\CustomWorkflowController;

/*
|--------------------------------------------------------------------------
| Web Routes - BLADE FRONTEND + ANGULAR COEXISTING
|--------------------------------------------------------------------------
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes (Web)
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [WebController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [WebController::class, 'dashboard'])->name('dashboard');
    
    // Expedientes Additional Routes (must come before resource routes)
    Route::prefix('expedientes')->name('expedientes.')->group(function () {
        Route::get('/pendientes', [WebController::class, 'expedientesPendientes'])->name('pendientes');
        Route::get('/proceso', [WebController::class, 'expedientesProceso'])->name('proceso');
        Route::get('/finalizados', [WebController::class, 'expedientesFinalizados'])->name('finalizados');
    });
    
    // Expedientes Routes (Web Views)
    Route::resource('expedientes', ExpedienteController::class)->names([
        'index' => 'expedientes.index',
        'create' => 'expedientes.create',
        'store' => 'expedientes.store',
        'show' => 'expedientes.show',
        'edit' => 'expedientes.edit',
        'update' => 'expedientes.update',
        'destroy' => 'expedientes.destroy'
    ]);
    
    // Gerencias Routes (Web Views)
    Route::resource('gerencias', GerenciaController::class);
    Route::patch('/gerencias/{gerencia}/toggle-status', [GerenciaController::class, 'toggleStatus'])->name('gerencias.toggle-status');
    Route::get('/gerencias/{gerencia}/subgerencias', [GerenciaController::class, 'subgerencias'])->name('gerencias.subgerencias');

    // Tipos de Trámite Routes (Web Views)
    Route::resource('tipos-tramite', TipoTramiteController::class)->names([
        'index' => 'tipos-tramite.index',
        'create' => 'tipos-tramite.create',
        'store' => 'tipos-tramite.store',
        'show' => 'tipos-tramite.show',
        'edit' => 'tipos-tramite.edit',
        'update' => 'tipos-tramite.update',
        'destroy' => 'tipos-tramite.destroy'
    ]);
    Route::patch('/tipos-tramite/{tipoTramite}/toggle-status', [TipoTramiteController::class, 'toggleStatus'])->name('tipos-tramite.toggle-status');
    Route::get('/gerencias/{gerencia}/tipos-tramite', [TipoTramiteController::class, 'byGerencia'])->name('tipos-tramite.by-gerencia');

    // Workflows Routes (Web Views)
    Route::resource('workflows', \App\Http\Controllers\WorkflowController::class)->names([
        'index' => 'workflows.index',
        'create' => 'workflows.create',
        'store' => 'workflows.store',
        'show' => 'workflows.show',
        'edit' => 'workflows.edit',
        'update' => 'workflows.update',
        'destroy' => 'workflows.destroy'
    ]);

    // Flujos Seleccionables Routes
    Route::prefix('flujos')->name('flujos.')->group(function () {
        Route::get('/crear', [\App\Http\Controllers\SelectableWorkflowController::class, 'create'])->name('create');
        Route::post('/crear', [\App\Http\Controllers\SelectableWorkflowController::class, 'store'])->name('store');
        Route::get('/api/gerencias', [\App\Http\Controllers\SelectableWorkflowController::class, 'getGerencias'])->name('api.gerencias');
        Route::get('/api/gerencias/{gerencia}/usuarios', [\App\Http\Controllers\SelectableWorkflowController::class, 'getUsersByGerencia'])->name('api.usuarios');
    });

    // Mesa de Partes Routes (Web Views)
    Route::prefix('mesa-partes')->name('mesa-partes.')->group(function () {
        Route::get('/', [WebController::class, 'mesaPartes'])->name('index');
        Route::get('/derivacion', [WebController::class, 'mesaPartesDerivacion'])->name('derivacion');
        Route::get('/registro', [WebController::class, 'mesaPartesRegistro'])->name('registro');
    });
    
    // Trámites Routes
    Route::resource('tramites', \App\Http\Controllers\TramiteController::class)->names([
        'index' => 'tramites.index',
        'create' => 'tramites.create',
        'store' => 'tramites.store',
        'show' => 'tramites.show',
        'edit' => 'tramites.edit',
        'update' => 'tramites.update',
        'destroy' => 'tramites.destroy'
    ]);
    
    // Reportes Routes
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [WebController::class, 'reportes'])->name('index');
        Route::get('/expedientes', [WebController::class, 'reportesExpedientes'])->name('expedientes');
        Route::get('/tramites', [WebController::class, 'reportesTramites'])->name('tramites');
        Route::get('/tiempos', [WebController::class, 'reportesTiempos'])->name('tiempos');
    });
    
    // Profile Routes
    Route::get('/profile', [WebController::class, 'profile'])->name('profile');
    Route::patch('/profile', [WebController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', [WebController::class, 'settings'])->name('settings');
    
    // Admin Routes
    Route::middleware(['role:superadministrador|administrador'])->group(function () {
        // Usuarios CRUD
        Route::resource('usuarios', UsuarioController::class);
        Route::patch('/usuarios/{usuario}/toggle-status', [UsuarioController::class, 'toggleStatus'])->name('usuarios.toggle-status');
        
        // Roles CRUD
        Route::resource('roles', RoleController::class);
        Route::post('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])->name('roles.assign-permissions');
        
        Route::get('/permisos', [WebController::class, 'permisos'])->name('permisos.index');
        Route::get('/configuracion', [WebController::class, 'configuracion'])->name('configuracion.index');
        
        // API Routes para la interfaz web (usan auth:web en lugar de auth:sanctum)
        Route::prefix('api')->group(function () {
            // Permisos
            Route::prefix('permissions')->group(function () {
                Route::get('/', [RolePermissionController::class, 'getPermissions']);
                Route::post('/', [RolePermissionController::class, 'createPermission']);
                Route::get('/{permission}', [RolePermissionController::class, 'getPermission']);
                Route::put('/{permission}', [RolePermissionController::class, 'updatePermission']);
                Route::delete('/{permission}', [RolePermissionController::class, 'deletePermission']);
            });
            
            // Roles
            Route::prefix('roles')->group(function () {
                Route::get('/', [RolePermissionController::class, 'getRoles']);
                Route::post('/', [RolePermissionController::class, 'createRole']);
                Route::get('/{role}', [RolePermissionController::class, 'getRole']);
                Route::put('/{role}', [RolePermissionController::class, 'updateRole']);
                Route::delete('/{role}', [RolePermissionController::class, 'deleteRole']);
            });
        });
    });
});

// Angular Routes (Catch-all for SPA) - Must be LAST
Route::get('/angular/{any}', function () {
    return view('welcome');
})->where('any', '.*')->name('angular');

// Fallback for other routes
Route::fallback(function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
