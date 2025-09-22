<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpedientController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\AdminGerenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\MesaPartesController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CustomWorkflowController;
use App\Http\Controllers\CustomWorkflowStepController;
use App\Http\Controllers\CustomWorkflowTransitionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Convenciones:
| - Rutas públicas: prefijo /public o recursos abiertos de solo lectura
| - Rutas de formularios/catálogos abiertos: /form-data/* (solo desarrollo)
| - Rutas autenticadas: protegidas con auth:sanctum
| - Rutas administrativas: permisos o roles específicos
| - Evitar nuevos endpoints mezclando idiomas (/expedients vs /expedientes)
|   mantener ambos por compatibilidad pero favorecer /expedientes (ES) o
|   /expedients (EN) según estandarización futura.
| - Debug: NO usar en producción (marcadas como DEBUG ONLY)
*/

// -------------------------------------------------------------------------
// Health & Meta
// -------------------------------------------------------------------------
Route::get('/health', function() {
    return response()->json([
        'status' => 'ok',
        'time' => now(),
        'app' => config('app.name'),
        'env' => config('app.env')
    ]);
});

Route::get('/version', function() {
    return response()->json([
        'version' => app()->version(),
        'php' => PHP_VERSION
    ]);
});

// Rutas públicas
Route::prefix('public')->group(function () {
    // Consulta pública de expedientes por número de seguimiento
    Route::get('/expedients/tracking/{trackingNumber}', [ExpedientController::class, 'getByTrackingNumber']);
    
    // Consulta pública de mesa de partes
    Route::get('/mesa-partes/seguimiento/{codigoSeguimiento}', [MesaPartesController::class, 'consultarPorCodigo']);
    Route::get('/tipos-documento', [MesaPartesController::class, 'getTiposDocumento']);
    Route::get('/tipos-tramite', [MesaPartesController::class, 'getTiposTramite']);
});

// Rutas de catálogos autenticados (movidas a sección protegida)
// NOTA: Estas rutas se movieron a la sección autenticada para evitar conflictos
Route::get('/tipos-documento', [MesaPartesController::class, 'getTiposDocumento']);
Route::get('/tipos-tramite', [MesaPartesController::class, 'getTiposTramite']);
Route::middleware('auth:sanctum')->get('/gerencias', function() {
    return response()->json(['success' => true, 'data' => \App\Models\Gerencia::all()]);
});

// Ruta de prueba simple
Route::get('/test', function() {
    return response()->json(['message' => 'API funcionando', 'timestamp' => now()]);
});

// Ruta para verificar usuarios (solo para desarrollo) - DEBUG ONLY
Route::get('/debug/users', function() {
    $users = \App\Models\User::select('id', 'name', 'email', 'estado', 'gerencia_id')->get();
    return response()->json(['users' => $users]);
});

// Ruta para probar credenciales específicas - DEBUG ONLY
Route::post('/debug/test-login', function(\Illuminate\Http\Request $request) {
    try {
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado']);
        }
        
        $passwordCheck = \Illuminate\Support\Facades\Hash::check($request->password, $user->password);
        
        return response()->json([
            'user_found' => true,
            'email' => $user->email,
            'estado' => $user->estado ?? 'no_estado_field',
            'password_valid' => $passwordCheck,
            'user_data' => $user->toArray()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Exception: ' . $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// Login simplificado para debug - DEBUG ONLY
Route::post('/debug/simple-login', function(\Illuminate\Http\Request $request) {
    try {
        $credentials = $request->only('email', 'password');
        
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'token' => $token
            ]);
        }
        
        return response()->json(['error' => 'Credenciales inválidas']);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Exception: ' . $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// Rutas GET para datos de formularios (sin autenticación para facilitar desarrollo) - NO PRODUCCIÓN
Route::get('/form-data/gerencias', function() {
    $gerencias = \App\Models\Gerencia::select('id', 'nombre', 'codigo')->where('activo', true)->get();
    return response()->json(['data' => $gerencias]);
});

Route::get('/form-data/usuarios', function() {
    $usuarios = \App\Models\User::select('id', 'name', 'email', 'gerencia_id')->get();
    return response()->json(['data' => $usuarios]);
});

Route::get('/form-data/tipos-tramite', function() {
    $tipos = \App\Models\TipoTramite::select('id', 'nombre', 'codigo', 'gerencia_id', 'costo')->where('activo', true)->get();
    return response()->json(['data' => $tipos]);
});

Route::get('/form-data/tipos-documento', function() {
    $tipos = \App\Models\TipoDocumento::select('id', 'nombre', 'codigo', 'requiere_firma')->where('activo', true)->get();
    return response()->json(['data' => $tipos]);
});

Route::get('/form-data/roles', function() {
    $roles = \Spatie\Permission\Models\Role::select('id', 'name')->get();
    return response()->json(['data' => $roles]);
});

Route::get('/form-data/permisos', function() {
    $permisos = \Spatie\Permission\Models\Permission::select('id', 'name')->get();
    return response()->json(['data' => $permisos]);
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
    
    // Workflows Personalizables - Gestión completa de workflows
    Route::prefix('custom-workflows')->group(function () {
        Route::get('/', [CustomWorkflowController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [CustomWorkflowController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [CustomWorkflowController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [CustomWorkflowController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [CustomWorkflowController::class, 'destroy'])->middleware('permission:gestionar_workflows');
        
        // Activar/desactivar workflow
        Route::post('/{id}/toggle', [CustomWorkflowController::class, 'toggleActive'])->middleware('permission:gestionar_workflows');
        
        // Clonar workflow
        Route::post('/{id}/clone', [CustomWorkflowController::class, 'clone'])->middleware('permission:gestionar_workflows');
        
        // Obtener workflows por tipo
        Route::get('/tipo/{tipo}', [CustomWorkflowController::class, 'getByType'])->middleware('permission:gestionar_workflows');
    });
    
    // Pasos de Workflows Personalizables
    Route::prefix('custom-workflow-steps')->group(function () {
        Route::get('/', [CustomWorkflowStepController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [CustomWorkflowStepController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [CustomWorkflowStepController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [CustomWorkflowStepController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [CustomWorkflowStepController::class, 'destroy'])->middleware('permission:gestionar_workflows');
    });
    
    // Transiciones de Workflows Personalizables
    Route::prefix('custom-workflow-transitions')->group(function () {
        Route::get('/', [CustomWorkflowTransitionController::class, 'index'])->middleware('permission:gestionar_workflows');
        Route::post('/', [CustomWorkflowTransitionController::class, 'store'])->middleware('permission:gestionar_workflows');
        Route::get('/{id}', [CustomWorkflowTransitionController::class, 'show'])->middleware('permission:gestionar_workflows');
        Route::put('/{id}', [CustomWorkflowTransitionController::class, 'update'])->middleware('permission:gestionar_workflows');
        Route::delete('/{id}', [CustomWorkflowTransitionController::class, 'destroy'])->middleware('permission:gestionar_workflows');
    });
    
    // Gerencias - Administración de gerencias (usando controlador correcto)
    Route::get('/gerencias', function() {
        return response()->json(['success' => true, 'data' => \App\Models\Gerencia::all()]);
    });
    
    Route::prefix('gerencias')->group(function () {
        Route::get('/admin', [AdminGerenciaController::class, 'index'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/all', [AdminGerenciaController::class, 'getAll'])->middleware('permission:gestionar_gerencias');
        Route::post('/admin', [AdminGerenciaController::class, 'store'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/{gerencia}', [AdminGerenciaController::class, 'show'])->middleware('permission:gestionar_gerencias');
        Route::put('/admin/{gerencia}', [AdminGerenciaController::class, 'update'])->middleware('permission:gestionar_gerencias');
        Route::delete('/admin/{gerencia}', [AdminGerenciaController::class, 'destroy'])->middleware('permission:gestionar_gerencias');
        
        // Acciones específicas (con prefijo admin)
        Route::post('/admin/{gerencia}/estado', [AdminGerenciaController::class, 'cambiarEstado'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/{gerencia}/subgerencias', [AdminGerenciaController::class, 'getSubgerencias'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/{gerencia}/usuarios', [AdminGerenciaController::class, 'getUsuarios'])->middleware('permission:gestionar_gerencias');
        Route::post('/admin/{gerencia}/usuarios', [AdminGerenciaController::class, 'asignarUsuario'])->middleware('permission:gestionar_gerencias');
        Route::delete('/admin/{gerencia}/usuarios/{user}', [AdminGerenciaController::class, 'removerUsuario'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/{gerencia}/estadisticas', [AdminGerenciaController::class, 'getEstadisticas'])->middleware('permission:gestionar_gerencias');
        
        // Endpoints adicionales (con prefijo admin)
        Route::get('/admin/tipo/{tipo}', [AdminGerenciaController::class, 'getPorTipo'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/flujos-disponibles', [AdminGerenciaController::class, 'getFlujosDisponibles'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/jerarquia', [AdminGerenciaController::class, 'getJerarquia'])->middleware('permission:gestionar_gerencias');
        Route::get('/admin/{gerencia}/puede-manejar/{tipoTramite}', [AdminGerenciaController::class, 'puedeManejarTramite'])->middleware('permission:gestionar_gerencias');
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

    // Roles y Permisos - Administración completa
    Route::prefix('roles')->middleware('permission:gestionar_usuarios')->group(function () {
        Route::get('/', [RolePermissionController::class, 'getRoles']);
        Route::post('/', [RolePermissionController::class, 'createRole']);
        Route::get('/{role}', [RolePermissionController::class, 'getRole']);
        Route::put('/{role}', [RolePermissionController::class, 'updateRole']);
        Route::delete('/{role}', [RolePermissionController::class, 'deleteRole']);
    });

    Route::prefix('permissions')->middleware('permission:gestionar_usuarios')->group(function () {
        Route::get('/', [RolePermissionController::class, 'getPermissions']);
        Route::post('/', [RolePermissionController::class, 'createPermission']);
        Route::get('/{permission}', [RolePermissionController::class, 'getPermission']);
        Route::put('/{permission}', [RolePermissionController::class, 'updatePermission']);
        Route::delete('/{permission}', [RolePermissionController::class, 'deletePermission']);
    });

    // Estadísticas del sistema
    Route::prefix('admin')->middleware('permission:administrar_sistema')->group(function () {
        Route::get('/roles-permissions/stats', [RolePermissionController::class, 'getStats']);
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

// Rutas de Mesa de Partes (autenticadas)
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

// -------------------------------------------------------------------------
// Fallback 404 JSON (debe ir al final para no interferir)
// -------------------------------------------------------------------------
Route::fallback(function() {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint no encontrado. Verifique la ruta o método.',
        'hint' => 'Use /health para comprobar estado base o revise documentación.'
    ], 404);
});