# Sistema de Roles y Permisos - Municipalidad

## Descripción General

El sistema implementa un control de acceso basado en roles (RBAC) usando el paquete Spatie Permission para Laravel. Todos los roles y permisos están configurados en español para facilitar su comprensión.

## Roles del Sistema

### 1. Superadministrador (`superadministrador`)
- **Descripción**: Acceso completo al sistema
- **Permisos**: Todos los permisos disponibles
- **Usuario de prueba**: `superadmin@muni.gob.pe`
- **Restricciones**: No puede ser eliminado

### 2. Administrador (`administrador`)
- **Descripción**: Gestión completa del sistema excepto algunos aspectos críticos
- **Permisos**: Gestión de usuarios, expedientes, reportes, configuración
- **Usuario de prueba**: `admin@muni.gob.pe`
- **Restricciones**: No puede ser eliminado

### 3. Jefe de Gerencia (`jefe_gerencia`)
- **Descripción**: Gestión de su gerencia y supervisión de expedientes
- **Permisos**: Gestión de expedientes, usuarios de su gerencia, workflows
- **Usuario de prueba**: `jefe@muni.gob.pe`

### 4. Funcionario (`funcionario`)
- **Descripción**: Procesamiento y gestión de expedientes
- **Permisos**: Crear, editar, derivar expedientes, subir documentos
- **Usuario de prueba**: `funcionario@muni.gob.pe`

### 5. Funcionario Junior (`funcionario_junior`)
- **Descripción**: Apoyo en el procesamiento de expedientes
- **Permisos**: Ver y editar expedientes asignados, subir documentos

### 6. Supervisor (`supervisor`)
- **Descripción**: Supervisión y aprobación de expedientes
- **Permisos**: Aprobar, rechazar, finalizar expedientes, gestión de pagos

### 7. Ciudadano (`ciudadano`)
- **Descripción**: Usuario externo que inicia trámites
- **Permisos**: Crear expedientes, ver sus propios trámites, subir documentos
- **Usuario de prueba**: `ciudadano@email.com`

## Estructura de Permisos

Los permisos siguen una nomenclatura estructurada: `modulo.accion`

### Módulos Principales:

#### Expedientes
- `ver_expedientes` - Ver expedientes
- `registrar_expediente` - Crear nuevos expedientes
- `editar_expediente` - Modificar expedientes
- `derivar_expediente` - Derivar a otras áreas
- `emitir_resolucion` - Aprobar expedientes
- `rechazar_expediente` - Rechazar expedientes
- `finalizar_expediente` - Finalizar expedientes
- `archivar_expediente` - Archivar expedientes
- `subir_documento` - Subir documentos
- `ver_todos_expedientes` - Ver todos los expedientes del sistema

#### Usuarios
- `gestionar_usuarios` - Gestión general de usuarios
- `crear_usuarios` - Crear nuevos usuarios
- `editar_usuarios` - Modificar usuarios existentes
- `asignar_roles` - Asignar roles a usuarios
- `gestionar_permisos` - Gestionar permisos específicos
- `ver_todos_usuarios` - Ver todos los usuarios

#### Gerencias
- `gerencias.gestionar` - Gestión de gerencias
- `gerencias.asignar_usuarios` - Asignar usuarios a gerencias

#### Reportes (`reportes.*`)
- `reportes.ver` - Ver reportes básicos
- `reportes.exportar_datos` - Exportar información
- `reportes.ver_estadisticas_gerencia` - Estadísticas de gerencia
- `reportes.ver_estadisticas_sistema` - Estadísticas del sistema

#### Notificaciones (`notificaciones.*`)
- `notificaciones.enviar` - Enviar notificaciones
- `notificaciones.gestionar` - Gestionar sistema de notificaciones

#### Pagos (`pagos.*`)
- `pagos.gestionar` - Gestión de pagos
- `pagos.confirmar` - Confirmar pagos
- `pagos.ver` - Ver información de pagos

#### Quejas (`quejas.*`)
- `quejas.gestionar` - Gestionar quejas
- `quejas.responder` - Responder quejas
- `quejas.escalar` - Escalar quejas

#### Workflows (`workflows.*`)
- `workflows.gestionar` - Gestión general de workflows
- `workflows.crear` - Crear nuevos workflows
- `workflows.editar` - Modificar workflows
- `workflows.eliminar` - Eliminar workflows
- `workflows.ver` - Ver workflows
- `workflows.activar` - Activar/desactivar workflows
- `workflows.clonar` - Clonar workflows existentes

#### Flujos (`flujos.*`)
- `flujos.crear_reglas` - Crear reglas de flujo
- `flujos.editar_reglas` - Modificar reglas
- `flujos.eliminar_reglas` - Eliminar reglas
- `flujos.ver_reglas` - Ver reglas
- `flujos.activar_desactivar_reglas` - Activar/desactivar reglas
- `flujos.crear_etapas` - Crear etapas de flujo
- `flujos.editar_etapas` - Modificar etapas
- `flujos.eliminar_etapas` - Eliminar etapas
- `flujos.ver_etapas` - Ver etapas

#### Procedimientos (`procedimientos.*`)
- `procedimientos.gestionar` - Gestión de procedimientos

## Usuarios de Prueba

Todos los usuarios tienen la contraseña: `password123`

| Email | Rol | Contraseña |
|-------|-----|------------|
| superadmin@muni.gob.pe | Superadministrador | password123 |
| admin@muni.gob.pe | Administrador | password123 |
| jefe@muni.gob.pe | Jefe de Gerencia | password123 |
| funcionario@muni.gob.pe | Funcionario | password123 |
| ciudadano@email.com | Ciudadano | password123 |

## Gestión de Roles

### Crear Nuevo Rol
1. Acceder a `/roles` (requiere permisos de administrador)
2. Hacer clic en "Crear Rol"
3. Asignar nombre y seleccionar permisos
4. Guardar

### Asignar Roles a Usuarios
1. Acceder a `/usuarios`
2. Editar usuario deseado
3. Seleccionar roles en la sección correspondiente
4. Guardar cambios

### Protecciones del Sistema
- Los roles `superadministrador` y `administrador` no pueden ser eliminados
- Los usuarios con rol `superadministrador` no pueden ser eliminados
- No se puede eliminar un rol que tiene usuarios asignados

## Comandos Útiles

```bash
# Crear roles y permisos
php artisan db:seed --class=RolesAndPermissionsSeeder

# Ver todos los roles
php artisan permission:show

# Limpiar caché de permisos
php artisan permission:cache-reset
```

## Archivos Relacionados

- **Seeder**: `database/seeders/RolesAndPermissionsSeeder.php`
- **Controller Roles**: `app/Http/Controllers/RoleController.php`
- **Controller Usuarios**: `app/Http/Controllers/UsuarioController.php`
- **Vistas Roles**: `resources/views/roles/`
- **Vistas Usuarios**: `resources/views/usuarios/`

## Middleware de Protección

El sistema utiliza middleware para proteger rutas:

```php
// Solo superadministrador y administrador
$this->middleware(['role:superadministrador|administrador']);

// Verificar permiso específico
$this->middleware(['permission:expedientes.crear']);

// Combinar roles y permisos
$this->middleware(['role_or_permission:administrador|expedientes.gestionar']);
```