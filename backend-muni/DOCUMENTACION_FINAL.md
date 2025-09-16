# 📋 Sistema de Trámite Documentario Municipal - Documentación Completa

## 🎯 Descripción del Proyecto

Sistema de gestión de expedientes y trámites documentarios para municipalidades, desarrollado con **Laravel 11** y **Angular**. Incluye autenticación con Sanctum, gestión de roles y permisos con Spatie Permission, y una arquitectura por gerencias.

---

## 🚀 Estado Actual del Sistema

### ✅ Funcionalidades Implementadas

#### 🔐 **Sistema de Autenticación**
- Login/Logout con Laravel Sanctum
- Gestión de tokens API
- Middleware de autenticación para rutas protegidas
- Usuarios de ejemplo creados por seeder

#### 👥 **Gestión de Usuarios**
- CRUD completo de usuarios
- Asignación de roles y permisos
- Usuarios por gerencia
- Estados activo/inactivo
- Campos adicionales: teléfono, cargo

#### 🎭 **Sistema de Roles y Permisos**
- Roles predefinidos: Super Admin, Jefe de Gerencia, Funcionario, Ciudadano
- 21 permisos específicos del sistema
- Guards: web y api
- Middleware de autorización

#### 🏢 **Gestión de Gerencias**
- CRUD de gerencias municipales
- Asignación de usuarios a gerencias
- Estructura jerárquica

#### 📁 **Sistema de Expedientes**
- CRUD de expedientes
- Estados del expediente
- Asignación a usuarios
- Historial de cambios
- Carga de archivos

#### 📊 **Panel Administrativo**
- Estadísticas del sistema
- Gestión completa de usuarios/roles/permisos
- Interface de prueba HTML

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Framework**: Laravel 11
- **Base de Datos**: SQLite (configurable)
- **Autenticación**: Laravel Sanctum
- **Permisos**: Spatie Laravel Permission
- **Middleware**: Autenticación y autorización

### Frontend de Prueba
- **HTML5/CSS3/JavaScript**
- **Fetch API** para requests
- **Bootstrap-like styling**

---

## 📦 Instalación y Configuración

### 1. Requisitos Previos
```bash
- PHP >= 8.2
- Composer
- Node.js (opcional)
```

### 2. Instalación
```bash
# Clonar repositorio
git clone <url-repositorio>
cd backend-muni

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Crear base de datos
touch database/database.sqlite

# Ejecutar migraciones
php artisan migrate

# Crear usuarios y permisos iniciales
php artisan db:seed --class=AdminSeeder

# Iniciar servidor
php artisan serve
```

### 3. Credenciales de Acceso
```
Super Admin:     admin@municipalidad.com / admin123
Jefe Gerencia:   jefe@municipalidad.com / jefe123
Funcionario:     funcionario@municipalidad.com / funcionario123
Ciudadano:       ciudadano@municipalidad.com / ciudadano123
```

---

## 🔗 API Endpoints

### 🔐 Autenticación
```http
POST   /api/auth/login          # Iniciar sesión
POST   /api/auth/logout         # Cerrar sesión
GET    /api/auth/user           # Usuario autenticado
POST   /api/auth/register       # Registrar usuario
```

### 👥 Gestión de Usuarios
```http
GET    /api/usuarios            # Listar usuarios (paginado)
POST   /api/usuarios            # Crear usuario
GET    /api/usuarios/{id}       # Ver usuario específico
PUT    /api/usuarios/{id}       # Actualizar usuario
DELETE /api/usuarios/{id}       # Eliminar usuario
POST   /api/usuarios/{id}/asignar-rol     # Asignar rol
DELETE /api/usuarios/{id}/remover-rol     # Remover rol
```

### 🎭 Roles y Permisos
```http
GET    /api/roles               # Listar roles
POST   /api/roles               # Crear rol
GET    /api/roles/{id}          # Ver rol específico
PUT    /api/roles/{id}          # Actualizar rol
DELETE /api/roles/{id}          # Eliminar rol

GET    /api/permissions         # Listar permisos
POST   /api/permissions         # Crear permiso
PUT    /api/permissions/{id}    # Actualizar permiso
DELETE /api/permissions/{id}    # Eliminar permiso
```

### 🏢 Gerencias
```http
GET    /api/gerencias           # Listar gerencias
POST   /api/gerencias           # Crear gerencia
GET    /api/gerencias/{id}      # Ver gerencia específica
PUT    /api/gerencias/{id}      # Actualizar gerencia
DELETE /api/gerencias/{id}      # Eliminar gerencia
```

### 📁 Expedientes
```http
GET    /api/expedientes         # Listar expedientes
POST   /api/expedientes         # Crear expediente
GET    /api/expedientes/{id}    # Ver expediente específico
PUT    /api/expedientes/{id}    # Actualizar expediente
DELETE /api/expedientes/{id}    # Eliminar expediente
GET    /api/expedientes/{id}/files     # Archivos del expediente
POST   /api/expedientes/{id}/files     # Subir archivo
GET    /api/expedientes/{id}/history   # Historial del expediente
POST   /api/expedientes/{id}/assign    # Asignar expediente
POST   /api/expedientes/{id}/change-status  # Cambiar estado
```

### 📊 Estadísticas
```http
GET    /api/admin/roles-permissions/stats  # Estadísticas del sistema
```

---

## 🎭 Sistema de Roles y Permisos

### Roles Predefinidos

#### 🔴 **Super Admin**
- **Permisos**: Todos los permisos del sistema
- **Acceso**: Gestión completa del sistema

#### 🟠 **Jefe de Gerencia**
```
- crear_expedientes
- editar_expedientes  
- ver_expedientes
- asignar_expedientes
- ver_usuarios
- ver_gerencias
- ver_estadisticas
```

#### 🟡 **Funcionario**
```
- crear_expedientes
- editar_expedientes
- ver_expedientes
- ver_gerencias
```

#### 🟢 **Ciudadano**
```
- crear_expedientes
- ver_expedientes
```

### Lista Completa de Permisos

#### 👥 Usuarios
- `crear_usuarios`
- `editar_usuarios`
- `eliminar_usuarios`
- `ver_usuarios`

#### 🎭 Roles y Permisos
- `crear_roles`
- `editar_roles`
- `eliminar_roles`
- `ver_roles`
- `asignar_permisos`

#### 📁 Expedientes
- `crear_expedientes`
- `editar_expedientes`
- `eliminar_expedientes`
- `ver_expedientes`
- `asignar_expedientes`

#### 🏢 Gerencias
- `gestionar_gerencias`
- `ver_gerencias`

#### ⚙️ Administración
- `administrar_sistema`
- `ver_estadisticas`
- `gestionar_configuracion`

---

## 🗃️ Estructura de Base de Datos

### Tablas Principales

#### 👤 users
```sql
- id (PK)
- name
- email (unique)
- password
- gerencia_id (FK)
- activo (boolean)
- telefono
- cargo
- email_verified_at
- timestamps
```

#### 🎭 roles
```sql
- id (PK)
- name
- guard_name
- timestamps
```

#### 🔐 permissions
```sql
- id (PK)
- name
- guard_name
- timestamps
```

#### 🏢 gerencias
```sql
- id (PK)
- nombre
- descripcion
- codigo
- activa (boolean)
- timestamps
```

#### 📁 expedientes
```sql
- id (PK)
- numero_expediente
- asunto
- descripcion
- estado
- prioridad
- gerencia_id (FK)
- user_id (FK)
- ciudadano_nombre
- ciudadano_dni
- fechas (created_at, updated_at)
```

---

## 🧪 Testing

### Interface de Prueba HTML
Accede a: `http://localhost:8000/test_admin.html`

### Funcionalidades de Prueba
1. **Autenticación**: Login con diferentes roles
2. **Gestión de Usuarios**: CRUD completo
3. **Gestión de Roles**: Crear, editar, eliminar roles
4. **Gestión de Permisos**: Administrar permisos
5. **Estadísticas**: Ver métricas del sistema

### Ejemplo de Request con cURL
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@municipalidad.com","password":"admin123"}'

# Listar usuarios (con token)
curl -X GET http://localhost:8000/api/usuarios \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## 🏗️ Arquitectura del Sistema

### Estructura por Gerencias
```
MUNICIPALIDAD
├── Gerencia General
├── Gerencia de Administración  
├── Gerencia de Servicios Públicos
├── Gerencia de Desarrollo Urbano
├── Gerencia de Desarrollo Económico
└── Gerencia de Desarrollo Social
```

### Flujo de Expedientes
1. **Ciudadano** crea expediente
2. **Mesa de Partes** recepciona y deriva
3. **Gerencia** responsable procesa
4. **Funcionarios** ejecutan tareas
5. **Sistema** registra historial completo

### Middleware de Seguridad
```php
- auth:sanctum (autenticación)
- permission:nombre_permiso (autorización)
- role:nombre_rol (rol específico)
```

---

## 📋 Control de Versiones

### Migraciones Ejecutadas
- `2025_01_01_000001_create_gerencias_table`
- `2025_01_01_000006_create_users_table`
- `2025_01_01_000007_create_expedientes_table`
- `2025_09_16_035947_add_admin_fields_to_users_table`
- Migraciones de Spatie Permission

### Seeders Ejecutados
- `AdminSeeder`: Usuarios, roles y permisos iniciales

---

## 🛠️ Desarrollo y Mantenimiento

### Comandos Útiles
```bash
# Limpiar cachés
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Ver rutas
php artisan route:list

# Crear migraciones
php artisan make:migration nombre_migracion

# Ejecutar seeders
php artisan db:seed --class=NombreSeeder

# Tinker (consola interactiva)
php artisan tinker
```

### Logs de la Aplicación
- Ubicación: `storage/logs/laravel.log`
- Configuración: `config/logging.php`

---

## 🔧 Configuración Avanzada

### CORS (config/cors.php)
```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost:4200'], // Angular
'allowed_headers' => ['*'],
'supports_credentials' => true,
```

### Sanctum (config/sanctum.php)
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
'expiration' => null, // Tokens no expiran
```

---

## 📞 Soporte y Documentación

### Documentos Adicionales
- `API_DOCUMENTATION.md`: Documentación técnica de API
- `INSTRUCCIONES_PRUEBA.md`: Guía de testing
- `test_admin.html`: Interface de prueba funcional

### Estructura de Archivos
```
backend-muni/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   └── Services/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php
│   └── web.php
├── public/
│   └── test_admin.html
└── documentación/
```

---

## ✅ Estado de Implementación

### ✅ Completado
- [x] Sistema de autenticación con Sanctum
- [x] Gestión completa de usuarios
- [x] Sistema de roles y permisos
- [x] CRUD de gerencias
- [x] Base del sistema de expedientes
- [x] Interface de prueba HTML
- [x] Documentación completa
- [x] Seeders con datos de prueba

### 🔄 En Desarrollo
- [ ] Frontend Angular completo
- [ ] Sistema de notificaciones
- [ ] Workflow avanzado de expedientes
- [ ] Reportes y dashboard
- [ ] API de integración externa

### 📋 Próximos Pasos
1. Desarrollar frontend Angular
2. Implementar notificaciones en tiempo real  
3. Crear sistema de reportes
4. Agregar workflow configurable
5. Testing automatizado

---

## 📊 Conclusiones

El sistema está **funcionalmente completo** para las operaciones básicas de administración:

- ✅ **Autenticación robusta** con tokens API
- ✅ **Gestión de usuarios** con roles y permisos
- ✅ **Base sólida** para expedientes y gerencias
- ✅ **Interface de prueba** completamente funcional
- ✅ **Arquitectura escalable** y bien documentada

### Credenciales de Acceso
```
URL: http://localhost:8000/test_admin.html
Admin: admin@municipalidad.com / admin123
```

**El sistema está listo para uso y testing completo.** 🚀