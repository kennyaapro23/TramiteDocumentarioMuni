# Sistema de Permisos - Laravel con Spatie Permission

## Configuración

Este proyecto utiliza el paquete `spatie/laravel-permission` para gestionar roles y permisos de usuarios.

## Estructura de Roles

### 1. Mesa de Partes
- **Permisos:**
  - `registrar_expediente` - Crear nuevos expedientes
  - `ver_expedientes` - Ver listado de expedientes

### 2. Gerente Urbano
- **Permisos:**
  - `ver_expedientes` - Ver listado de expedientes
  - `derivar_expediente` - Derivar expedientes a otras áreas
  - `revision_tecnica` - Realizar revisión técnica
  - `revision_legal` - Realizar revisión legal
  - `reportes` - Acceder a reportes

### 3. Secretaria General
- **Permisos:**
  - `ver_expedientes` - Ver listado de expedientes
  - `derivar_expediente` - Derivar expedientes a otras áreas
  - `revision_legal` - Realizar revisión legal
  - `emitir_resolucion` - Emitir resoluciones
  - `reportes` - Acceder a reportes

### 4. Alcalde
- **Permisos:**
  - `ver_expedientes` - Ver listado de expedientes
  - `firma_resolucion_mayor` - Firmar resoluciones mayores
  - `gestionar_usuarios` - Administrar usuarios del sistema
  - `gestionar_roles` - Administrar roles y permisos
  - `reportes` - Acceder a reportes

## Permisos Disponibles

- `registrar_expediente` - Crear expedientes
- `derivar_expediente` - Derivar expedientes
- `revision_tecnica` - Revisión técnica
- `revision_legal` - Revisión legal
- `emitir_resolucion` - Emitir resoluciones
- `firma_resolucion_mayor` - Firmar resoluciones mayores
- `ver_expedientes` - Ver expedientes
- `editar_expedientes` - Editar expedientes
- `eliminar_expedientes` - Eliminar expedientes
- `gestionar_usuarios` - Administrar usuarios
- `gestionar_roles` - Administrar roles
- `reportes` - Acceder a reportes

## Uso en Controladores

### Verificar Permisos

```php
// Verificar si el usuario tiene un permiso específico
if (auth()->user()->hasPermissionTo('registrar_expediente')) {
    // Lógica para registrar expediente
}

// Verificar si el usuario tiene un rol específico
if (auth()->user()->hasRole('alcalde')) {
    // Lógica específica para alcalde
}

// Verificar si el usuario tiene alguno de varios permisos
if (auth()->user()->hasAnyPermission(['editar_expedientes', 'eliminar_expedientes'])) {
    // Lógica para editar o eliminar
}
```

### Asignar Roles y Permisos

```php
// Asignar un rol a un usuario
$user->assignRole('mesa_partes');

// Asignar múltiples roles
$user->assignRole(['mesa_partes', 'gerente_urbano']);

// Asignar un permiso directamente
$user->givePermissionTo('registrar_expediente');

// Asignar múltiples permisos
$user->givePermissionTo(['ver_expedientes', 'editar_expedientes']);

// Remover un rol
$user->removeRole('mesa_partes');

// Remover un permiso
$user->revokePermissionTo('registrar_expediente');
```

## Uso en Rutas

### Middleware de Permisos

```php
// Ruta protegida con permiso específico
Route::get('/expedientes', [ExpedienteController::class, 'index'])
    ->middleware('permission:ver_expedientes')
    ->name('expedientes.index');

// Ruta protegida con múltiples permisos (OR)
Route::get('/expedientes/edit', [ExpedienteController::class, 'edit'])
    ->middleware('permission:editar_expedientes|gestionar_expedientes')
    ->name('expedientes.edit');

// Ruta protegida con rol específico
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('role:alcalde')
    ->name('admin.index');
```

## Uso en Vistas Blade

### Directivas de Permisos

```blade
{{-- Mostrar contenido solo si tiene permiso --}}
@can('registrar_expediente')
    <a href="{{ route('expedientes.create') }}" class="btn btn-primary">
        Nuevo Expediente
    </a>
@endcan

{{-- Mostrar contenido si tiene rol --}}
@role('alcalde')
    <div class="admin-panel">
        Panel de Administración
    </div>
@endrole

{{-- Mostrar contenido si tiene alguno de varios permisos --}}
@canany(['editar_expedientes', 'eliminar_expedientes'])
    <div class="actions">
        <button class="btn btn-warning">Editar</button>
        <button class="btn btn-danger">Eliminar</button>
    </div>
@endcanany

{{-- Mostrar contenido si NO tiene permiso --}}
@cannot('gestionar_usuarios')
    <p>No tienes permisos para gestionar usuarios</p>
@endcannot
```

### Verificaciones en JavaScript

```javascript
// Verificar permisos del usuario actual
const userPermissions = @json(auth()->user()->getAllPermissions()->pluck('name'));

function canPerformAction(permission) {
    return userPermissions.includes(permission);
}

// Ejemplo de uso
if (canPerformAction('eliminar_expedientes')) {
    showDeleteButton();
}
```

## Comandos Artisan Útiles

### Crear Roles y Permisos

```bash
# Crear un rol
php artisan permission:create-role gerente_urbano

# Crear un permiso
php artisan permission:create-permission revision_tecnica

# Asignar permiso a rol
php artisan permission:give-permission-to-role revision_tecnica gerente_urbano

# Asignar rol a usuario
php artisan permission:assign-role-to-user gerente_urbano user@example.com
```

### Verificar Estado

```bash
# Ver todos los roles
php artisan permission:show

# Ver permisos de un usuario
php artisan permission:show --user=user@example.com

# Ver usuarios con un rol
php artisan permission:show --role=alcalde
```

## Ejecutar Seeders

Para crear los roles y permisos iniciales:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

O para ejecutar todos los seeders:

```bash
php artisan db:seed
```

## Mejores Prácticas

1. **Nombres de Permisos:** Usar nombres descriptivos y consistentes
2. **Granularidad:** Crear permisos específicos en lugar de permisos muy generales
3. **Herencia:** Usar roles para agrupar permisos relacionados
4. **Verificación:** Siempre verificar permisos tanto en frontend como backend
5. **Cache:** Los permisos se cachean automáticamente para mejor rendimiento

## Troubleshooting

### Problemas Comunes

1. **Permisos no se aplican:** Verificar que el usuario esté autenticado
2. **Cache de permisos:** Ejecutar `php artisan permission:cache-reset`
3. **Middleware no funciona:** Verificar que esté registrado en `bootstrap/app.php`
4. **Roles no se crean:** Verificar que las migraciones se ejecutaron correctamente

### Comandos de Limpieza

```bash
# Limpiar cache de permisos
php artisan permission:cache-reset

# Limpiar cache general
php artisan cache:clear

# Limpiar config
php artisan config:clear
```
