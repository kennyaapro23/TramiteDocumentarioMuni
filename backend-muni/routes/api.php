<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpedientController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\AdminGerenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MesaPartesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::prefix('public')->group(function () {
    // Consulta pública de expedientes por número de seguimiento
    Route::get('/expedients/tracking/{trackingNumber}', [ExpedientController::class, 'getByTrackingNumber']);
    
    // Consulta pública de mesa de partes
    Route::get('/mesa-partes/seguimiento/{codigoSeguimiento}', [MesaPartesController::class, 'consultarPorCodigo']);
    Route::get('/tipos-documento', [MesaPartesController::class, 'getTiposDocumento']);
    Route::get('/tipos-tramite', [MesaPartesController::class, 'getTiposTramite']);
});

// Rutas de catálogos (sin autenticación para facilitar desarrollo)
Route::get('/mesa-partes', [MesaPartesController::class, 'index']);
Route::get('/tipos-documento', [MesaPartesController::class, 'getTiposDocumento']);
Route::get('/tipos-tramite', [MesaPartesController::class, 'getTiposTramite']);
Route::get('/gerencias', function() {
    return response()->json(['data' => \App\Models\Gerencia::all()]);
});

// Rutas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::get('/check-email', [AuthController::class, 'checkEmail']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
});

// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    
    // Usuario autenticado
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['roles', 'permissions']);
    });
    
    // Expedientes - Usando los controladores existentes
    Route::prefix('expedientes')->group(function () {
        Route::get('/', [ExpedienteController::class, 'index']);
        Route::post('/', [ExpedienteController::class, 'store'])->middleware('permission:registrar_expediente');
        Route::get('/{expediente}', [ExpedienteController::class, 'show']);
        Route::put('/{expediente}', [ExpedienteController::class, 'update'])->middleware('permission:editar_expediente');
        Route::delete('/{expediente}', [ExpedienteController::class, 'destroy'])->middleware('permission:eliminar_expediente');
        
        // Acciones específicas
        Route::post('/{expediente}/derivar', [ExpedienteController::class, 'derivar'])->middleware('permission:derivar_expediente');
        Route::post('/{expediente}/aprobar', [ExpedienteController::class, 'aprobar'])->middleware('permission:emitir_resolucion');
        Route::post('/{expediente}/rechazar', [ExpedienteController::class, 'rechazar'])->middleware('permission:rechazar_expediente');
        Route::post('/{expediente}/documentos', [ExpedienteController::class, 'subirDocumento'])->middleware('permission:subir_documento');
        
        // Endpoints adicionales
        Route::get('/tipos-tramite', [ExpedienteController::class, 'getTiposTramite']);
        Route::get('/estados', [ExpedienteController::class, 'getEstados']);
        Route::get('/estadisticas', [ExpedienteController::class, 'getEstadisticas']);
        Route::get('/exportar', [ExpedienteController::class, 'exportar']);
    });

    // Expedientes - Rutas nuevas usando el nuevo controlador Api
    Route::prefix('expedients')->group(function () {
        Route::get('/', [ExpedientController::class, 'index']);
        Route::post('/', [ExpedientController::class, 'store']);
        Route::get('/{id}', [ExpedientController::class, 'show']);
        Route::put('/{id}', [ExpedientController::class, 'update']);
        Route::delete('/{id}', [ExpedientController::class, 'destroy']);
        
        // Rutas adicionales para expedientes nuevos
        Route::get('/{id}/files', [ExpedientController::class, 'getFiles']);
        Route::post('/{id}/files', [ExpedientController::class, 'uploadFile']);
        Route::get('/{id}/history', [ExpedientController::class, 'getHistory']);
        Route::post('/{id}/assign', [ExpedientController::class, 'assignToUser']);
        Route::post('/{id}/change-status', [ExpedientController::class, 'changeStatus']);
    });
    
    // Gerencias - Administración de gerencias
    Route::prefix('gerencias')->group(function () {
        Route::get('/', [AdminGerenciaController::class, 'index'])->middleware('permission:gestionar_gerencias');
        Route::get('/all', [AdminGerenciaController::class, 'getAll'])->middleware('permission:gestionar_gerencias');
        Route::post('/', [AdminGerenciaController::class, 'store'])->middleware('permission:gestionar_gerencias');
        Route::get('/{gerencia}', [AdminGerenciaController::class, 'show'])->middleware('permission:gestionar_gerencias');
        Route::put('/{gerencia}', [AdminGerenciaController::class, 'update'])->middleware('permission:gestionar_gerencias');
        Route::delete('/{gerencia}', [AdminGerenciaController::class, 'destroy'])->middleware('permission:gestionar_gerencias');
        
        // Acciones específicas
        Route::post('/{gerencia}/estado', [AdminGerenciaController::class, 'cambiarEstado'])->middleware('permission:gestionar_gerencias');
        Route::get('/{gerencia}/subgerencias', [AdminGerenciaController::class, 'getSubgerencias'])->middleware('permission:gestionar_gerencias');
        Route::get('/{gerencia}/usuarios', [AdminGerenciaController::class, 'getUsuarios'])->middleware('permission:gestionar_gerencias');
        Route::post('/{gerencia}/usuarios', [AdminGerenciaController::class, 'asignarUsuario'])->middleware('permission:gestionar_gerencias');
        Route::delete('/{gerencia}/usuarios/{user}', [AdminGerenciaController::class, 'removerUsuario'])->middleware('permission:gestionar_gerencias');
        Route::get('/{gerencia}/estadisticas', [AdminGerenciaController::class, 'getEstadisticas'])->middleware('permission:gestionar_gerencias');
        
        // Endpoints adicionales
        Route::get('/tipo/{tipo}', [AdminGerenciaController::class, 'getPorTipo'])->middleware('permission:gestionar_gerencias');
        Route::get('/flujos-disponibles', [AdminGerenciaController::class, 'getFlujosDisponibles'])->middleware('permission:gestionar_gerencias');
        Route::get('/jerarquia', [AdminGerenciaController::class, 'getJerarquia'])->middleware('permission:gestionar_gerencias');
        Route::get('/{gerencia}/puede-manejar/{tipoTramite}', [AdminGerenciaController::class, 'puedeManejarTramite'])->middleware('permission:gestionar_gerencias');
    });
    
    // Usuarios - Administración de usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [AdminGerenciaController::class, 'getUsuarios'])->middleware('permission:gestionar_usuarios');
        Route::post('/', [AdminGerenciaController::class, 'createUsuario'])->middleware('permission:gestionar_usuarios');
        Route::get('/{user}', [AdminGerenciaController::class, 'getUsuario'])->middleware('permission:gestionar_usuarios');
        Route::put('/{user}', [AdminGerenciaController::class, 'updateUsuario'])->middleware('permission:gestionar_usuarios');
        Route::delete('/{user}', [AdminGerenciaController::class, 'deleteUsuario'])->middleware('permission:gestionar_usuarios');
        
        // Acciones específicas
        Route::post('/{user}/estado', [AdminGerenciaController::class, 'cambiarEstadoUsuario'])->middleware('permission:gestionar_usuarios');
        Route::post('/{user}/roles', [AdminGerenciaController::class, 'asignarRol'])->middleware('permission:gestionar_usuarios');
        Route::delete('/{user}/roles/{role}', [AdminGerenciaController::class, 'removerRol'])->middleware('permission:gestionar_usuarios');
        Route::post('/{user}/permissions', [AdminGerenciaController::class, 'asignarPermisos'])->middleware('permission:gestionar_usuarios');
        Route::delete('/{user}/permissions', [AdminGerenciaController::class, 'removerPermisos'])->middleware('permission:gestionar_usuarios');
        Route::post('/{user}/permissions/sync', [AdminGerenciaController::class, 'sincronizarPermisos'])->middleware('permission:gestionar_usuarios');
        Route::post('/{user}/password', [AdminGerenciaController::class, 'cambiarContraseñaUsuario'])->middleware('permission:gestionar_usuarios');
        
        // Endpoints adicionales
        Route::get('/role/{role}', [AdminGerenciaController::class, 'getUsuariosPorRol'])->middleware('permission:gestionar_usuarios');
        Route::get('/gerencia/{gerencia}', [AdminGerenciaController::class, 'getUsuariosPorGerencia'])->middleware('permission:gestionar_usuarios');
        Route::get('/{user}/actividad', [AdminGerenciaController::class, 'getActividadUsuario'])->middleware('permission:gestionar_usuarios');
        Route::get('/estadisticas', [AdminGerenciaController::class, 'getEstadisticasUsuarios'])->middleware('permission:gestionar_usuarios');
        Route::get('/verificar-email', [AdminGerenciaController::class, 'verificarEmailDisponible'])->middleware('permission:gestionar_usuarios');
        Route::post('/invitar', [AdminGerenciaController::class, 'enviarInvitacion'])->middleware('permission:gestionar_usuarios');
        Route::post('/{user}/reenviar-invitacion', [AdminGerenciaController::class, 'reenviarInvitacion'])->middleware('permission:gestionar_usuarios');
        
        // Roles y permisos
        Route::get('/roles', [AdminGerenciaController::class, 'getRoles'])->middleware('permission:gestionar_usuarios');
        Route::get('/permissions', [AdminGerenciaController::class, 'getPermissions'])->middleware('permission:gestionar_usuarios');
    });
    
    // Middleware de roles para funciones administrativas
    Route::middleware('role:admin|funcionario')->group(function () {
        // Rutas que requieren permisos de funcionario o admin
        Route::get('/admin/expedients', [ExpedientController::class, 'adminIndex']);
        Route::get('/admin/reports/dashboard', [ExpedientController::class, 'dashboard']);
    });
    
    // Middleware solo para administradores
    Route::middleware('role:admin')->group(function () {
        // Rutas que solo admin puede acceder
        Route::get('/admin/system-settings', [ExpedientController::class, 'getSystemSettings']);
        Route::post('/admin/system-settings', [ExpedientController::class, 'updateSystemSettings']);
    });
});

// Rutas de Mesa de Partes
Route::middleware('auth:sanctum')->prefix('mesa-partes')->group(function () {
    // CRUD principal
    Route::get('/', [MesaPartesController::class, 'index'])->middleware('permission:ver_mesa_partes');
    Route::post('/', [MesaPartesController::class, 'store'])->middleware('permission:crear_mesa_partes');
    Route::get('/{id}', [MesaPartesController::class, 'show'])->middleware('permission:ver_mesa_partes');
    Route::put('/{id}', [MesaPartesController::class, 'update'])->middleware('permission:editar_mesa_partes');
    
    // Acciones específicas
    Route::post('/{id}/derivar', [MesaPartesController::class, 'derivar'])->middleware('permission:derivar_mesa_partes');
    Route::post('/{id}/observar', [MesaPartesController::class, 'observar'])->middleware('permission:observar_mesa_partes');
    
    // Datos de apoyo
    Route::get('/tipos/tramites', [MesaPartesController::class, 'getTiposTramite']);
    Route::get('/tipos/documentos', [MesaPartesController::class, 'getTiposDocumento']);
    Route::get('/tramites/{tipoTramiteId}/documentos-requeridos', [MesaPartesController::class, 'getDocumentosRequeridos']);
    
    // Estadísticas
    Route::get('/reportes/estadisticas', [MesaPartesController::class, 'getEstadisticas'])->middleware('permission:ver_estadisticas_mesa_partes');
});

// Ruta pública para consulta de seguimiento
Route::get('/mesa-partes/consultar/{codigoSeguimiento}', [MesaPartesController::class, 'consultarPorCodigo']);