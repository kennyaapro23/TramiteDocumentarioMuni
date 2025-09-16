# 🏛️ Sistema de Trámite Documentario Municipal - Documentación Completa

## 📋 Índice

1. [Descripción General](#descripción-general)
2. [Instalación y Configuración](#instalación-y-configuración)
3. [Arquitectura del Sistema](#arquitectura-del-sistema)
4. [API RESTful](#api-restful)
5. [Sistema de Permisos](#sistema-de-permisos)
6. [Instrucciones de Prueba](#instrucciones-de-prueba)
7. [Estado de Implementación](#estado-de-implementación)

---

## 🎯 Descripción General

Sistema integral de gestión de expedientes municipales desarrollado en **Laravel 11** que permite a ciudadanos registrar solicitudes de trámites y a funcionarios municipales procesarlas según sus roles y permisos específicos.

### ✨ Características Principales

- **🔐 Autenticación y Autorización**: Sistema robusto con Sanctum y Spatie Permissions
- **📋 Gestión de Expedientes**: Flujo completo desde registro hasta resolución
- **🏢 Arquitectura de Gerencias**: Modelo unificado de gerencias y subgerencias
- **📄 Gestión Documental**: Carga y gestión de documentos por expediente
- **📊 Historial y Auditoría**: Trazabilidad completa de todas las acciones
- **🔄 Workflow Dinámico**: Flujos configurables según tipo de trámite
- **📱 API RESTful**: Endpoints documentados para integración con frontend

### 🚀 Tecnologías Utilizadas

- **Backend**: Laravel 11
- **Base de Datos**: SQLite (configurable a MySQL/PostgreSQL)
- **Autenticación**: Laravel Sanctum
- **Permisos**: Spatie Laravel Permission
- **Documentación**: Markdown documentado
- **Testing**: PHPUnit

---

## 📦 Instalación y Configuración

### Prerrequisitos
- PHP >= 8.2
- Composer
- Node.js y npm (para assets)

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone [repository-url]
cd backend-muni
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**
```bash
# Editar .env con tus credenciales de BD
php artisan migrate --seed
```

5. **Generar datos de prueba**
```bash
php artisan db:seed --class=GerenciaSeeder
php artisan db:seed --class=RolePermissionSeeder
```

6. **Iniciar servidor**
```bash
php artisan serve
```

### Variables de Entorno Importantes
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:4200
SESSION_DOMAIN=localhost
```

---

## 🏗️ Arquitectura del Sistema

### Conceptos Clave Implementados

#### **🏢 Gerencias/Subgerencias = Estructura Organizacional**
- **Propósito**: Define la estructura jerárquica de la municipalidad
- **Características**: Dinámicas, configurables desde la aplicación
- **Función**: Agrupan usuarios y procesan tipos específicos de trámites

#### **👤 Roles/Permisos = Capacidades Funcionales**
- **Propósito**: Define qué acciones puede realizar cada usuario
- **Características**: Estáticas, definidas en el código del sistema
- **Función**: Controlan el acceso a funcionalidades específicas

#### **🔄 Flujos de Trámite = Reglas de Negocio**
- **Propósito**: Define qué gerencia puede procesar qué tipo de trámite
- **Características**: Configurables por gerencia
- **Función**: Valida derivaciones y asignaciones automáticas

### Modelo de Datos Principal

#### **Gerencia (Modelo Unificado)**
```php
class Gerencia extends Model
{
    protected $fillable = [
        'nombre',            // "Gerencia de Desarrollo Urbano"
        'codigo',            // "GDU" (Único, para referencias)
        'tipo',              // 'gerencia' | 'subgerencia'
        'gerencia_padre_id', // null para gerencias, ID para subgerencias
        'flujos_permitidos', // JSON: ["licencia_construccion", "certificado_habilitacion"]
        'activo',            // true/false (para activar/desactivar)
        'orden'              // Orden de visualización en listas
    ];
}
```

#### **Expediente (Núcleo del Sistema)**
```php
class Expediente extends Model
{
    protected $fillable = [
        'numero',                    // EXP-2025-000001 (autogenerado)
        'solicitante_nombre',        // Datos del ciudadano
        'solicitante_dni',
        'solicitante_email',
        'solicitante_telefono',
        'tipo_tramite',              // licencia_construccion, etc.
        'asunto',                    // Descripción corta
        'descripcion',               // Descripción detallada
        'estado',                    // pendiente → completado
        'gerencia_id',               // Gerencia actual
        'subgerencia_id',            // Subgerencia actual (opcional)
        'usuario_asignado_id',       // Funcionario responsable
        'numero_resolucion',         // Número de resolución emitida
        'requiere_inspeccion',       // true/false
        'requiere_revision_legal'    // true/false
    ];
}
```

### 🔄 Flujo de Trabajo Completo

#### **Fase 1: Registro Ciudadano**
- Ciudadano accede al sistema
- Completa formulario con datos personales
- Selecciona tipo de trámite requerido
- Sube documentos obligatorios
- Sistema genera número de expediente automático
- **Estado: PENDIENTE**

#### **Fase 2: Mesa de Partes**
- Funcionario Mesa de Partes revisa expediente
- Valida documentos requeridos vs tipo de trámite
- Si está completo, deriva a gerencia correspondiente
- Sistema valida si gerencia puede manejar el trámite
- **Estado: EN_REVISION**

#### **Fase 3: Gerencia/Subgerencia**
- Gerente/Inspector recibe expediente asignado
- Realiza revisión técnica según normativa
- Puede realizar inspección de campo (si requiere)
- Determina si necesita revisión legal
- **Estado: REVISION_TECNICA → REVISION_LEGAL (opcional)**

#### **Fase 4: Secretaría General (Legal)**
- Abogado revisa aspectos legales del expediente
- Valida conformidad con normativa municipal
- Determina si es acto administrativo mayor
- **Estado: REVISION_LEGAL → RESOLUCION_EMITIDA**

#### **Fase 5: Alcalde (Actos Mayores)**
- Alcalde revisa expedientes de alto impacto
- Valida resolución propuesta
- Firma digitalmente la resolución
- **Estado: FIRMADO → NOTIFICADO → COMPLETADO**

---

## 📱 API RESTful

### Base URL
```
http://localhost:8000/api
```

### Autenticación
La API utiliza **Laravel Sanctum**. Todas las rutas protegidas requieren:
```http
Authorization: Bearer {token}
```

### 🔐 Endpoints de Autenticación

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "admin@municipalidad.com",
    "password": "admin123"
}
```

#### Usuario Autenticado
```http
GET /api/auth/user
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### 📋 Endpoints de Expedientes

#### Listar Expedientes
```http
GET /api/expedientes
Authorization: Bearer {token}
```

**Query Parameters:**
- `estado` (opcional): Filtrar por estado
- `gerencia_id` (opcional): Filtrar por gerencia
- `search` (opcional): Búsqueda por número, solicitante o asunto
- `per_page` (opcional): Elementos por página (default: 15)

#### Crear Expediente
```http
POST /api/expedientes
Authorization: Bearer {token}
Content-Type: multipart/form-data

solicitante_nombre=Juan Pérez
solicitante_dni=12345678
solicitante_email=juan@example.com
solicitante_telefono=999888777
tipo_tramite=licencia_construccion
asunto=Solicitud de licencia de construcción
descripcion=Descripción detallada del trámite
gerencia_id=1
```

#### Ver Expediente
```http
GET /api/expedientes/{id}
Authorization: Bearer {token}
```

#### Derivar Expediente
```http
POST /api/expedientes/{id}/derivar
Authorization: Bearer {token}
Content-Type: application/json

{
    "gerencia_destino_id": 2,
    "observaciones": "Se deriva para revisión técnica",
    "prioridad": "normal"
}
```

#### Aprobar Expediente
```http
POST /api/expedientes/{id}/aprobar
Authorization: Bearer {token}
Content-Type: application/json

{
    "numero_resolucion": "RES-001-2025",
    "observaciones": "Expediente aprobado conforme a normativa",
    "requiere_firma_alcalde": false
}
```

#### Rechazar Expediente
```http
POST /api/expedientes/{id}/rechazar
Authorization: Bearer {token}
Content-Type: application/json

{
    "motivo": "Documentación incompleta",
    "observaciones": "Falta certificado de parámetros urbanísticos"
}
```

### 🏢 Endpoints de Gerencias

#### Listar Gerencias
```http
GET /api/gerencias
Authorization: Bearer {token}
```

#### Crear Gerencia
```http
POST /api/gerencias
Authorization: Bearer {token}
Content-Type: application/json

{
    "nombre": "Nueva Gerencia",
    "codigo": "NG",
    "tipo": "gerencia",
    "flujos_permitidos": ["licencia_funcionamiento"],
    "activo": true,
    "orden": 10
}
```

### 👥 Endpoints de Usuarios

#### Listar Usuarios
```http
GET /api/usuarios
Authorization: Bearer {token}
```

#### Crear Usuario
```http
POST /api/usuarios
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Nuevo Usuario",
    "email": "usuario@municipalidad.gob.pe",
    "password": "password123",
    "gerencia_id": 1,
    "roles": ["mesa_partes"],
    "activo": true
}
```

### 📄 Endpoints de Mesa de Partes

#### Listar Mesa de Partes
```http
GET /api/mesa-partes
Authorization: Bearer {token}
```

#### Consulta Pública (sin autenticación)
```http
GET /api/public/mesa-partes/seguimiento/{codigoSeguimiento}
```

### 📊 Endpoints de Catálogos

#### Tipos de Trámite
```http
GET /api/tipos-tramite
```

#### Tipos de Documento
```http
GET /api/tipos-documento
```

#### Estados de Expediente
```http
GET /api/expedientes/estados
```

---

## 🔐 Sistema de Permisos

### Roles Implementados

- **🔹 admin** - Administrador total del sistema
- **🔹 mesa_partes** - Registro y derivación inicial
- **🔹 gerente_urbano** - Revisión técnica especializada
- **🔹 inspector** - Inspecciones de campo
- **🔹 secretaria_general** - Revisión legal y resoluciones
- **🔹 alcalde** - Firma de actos administrativos mayores

### Permisos por Módulo

#### **Expedientes:**
- `registrar_expediente` - Crear nuevos expedientes
- `ver_expediente` - Consultar expedientes
- `editar_expediente` - Modificar datos
- `eliminar_expediente` - Eliminar (solo admin)
- `derivar_expediente` - Enviar a otra gerencia
- `aprobar_expediente` - Marcar como aprobado
- `rechazar_expediente` - Rechazar por no conformidad
- `emitir_resolucion` - Generar resolución final

#### **Mesa de Partes:**
- `ver_mesa_partes` - Consultar registros
- `crear_mesa_partes` - Registrar documentos
- `editar_mesa_partes` - Modificar registros
- `derivar_mesa_partes` - Enviar a gerencias
- `observar_mesa_partes` - Agregar observaciones

#### **Administración:**
- `gestionar_gerencias` - CRUD de gerencias
- `gestionar_usuarios` - CRUD de usuarios
- `ver_estadisticas` - Dashboard y reportes
- `subir_documento` - Cargar archivos

### Estados de Expedientes
- `pendiente`: Recién creado
- `en_revision`: En proceso de revisión
- `revision_tecnica`: Revisión técnica en curso
- `revision_legal`: Revisión legal requerida
- `resolucion_emitida`: Resolución emitida
- `firmado`: Firmado por autoridad
- `notificado`: Notificado al ciudadano
- `completado`: Proceso terminado
- `rechazado`: Rechazado por no cumplir requisitos

---

## 🧪 Instrucciones de Prueba

### 🔑 Credenciales de Acceso Preconfiguradas

#### **Usuario Administrador**
- **Email**: `admin@municipalidad.com`
- **Password**: `admin123`
- **Rol**: `admin`
- **Permisos**: Acceso completo al sistema

#### **Usuario Mesa de Partes**
- **Email**: `mesa@municipalidad.com`
- **Password**: `mesa123`
- **Rol**: `mesa_partes`

#### **Usuario Gerente Urbano**
- **Email**: `gerente@municipalidad.com`
- **Password**: `gerente123`
- **Rol**: `gerente_urbano`

### 🧪 Casos de Prueba Principales

#### **Test 1: Autenticación**
```bash
# Login exitoso
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@municipalidad.com","password":"admin123"}'

# Obtener usuario autenticado (usar token del login)
curl -X GET http://localhost:8000/api/auth/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### **Test 2: Gestión de Expedientes**
```bash
# Listar expedientes
curl -X GET http://localhost:8000/api/expedientes \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Crear expediente
curl -X POST http://localhost:8000/api/expedientes \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "solicitante_nombre=Juan Pérez Test" \
  -F "solicitante_dni=12345678" \
  -F "solicitante_email=juan.test@email.com" \
  -F "solicitante_telefono=999888777" \
  -F "tipo_tramite=licencia_construccion" \
  -F "asunto=Prueba de licencia de construcción" \
  -F "descripcion=Solicitud de prueba" \
  -F "gerencia_id=1"
```

#### **Test 3: Flujo Completo**
1. **Login** como admin
2. **Crear expediente** con datos de prueba
3. **Derivar** a gerencia específica
4. **Aprobar** o **rechazar** según caso
5. **Verificar historial** completo

### 🎯 Herramientas de Prueba

#### **Archivos HTML Incluidos**
- **`test_api.html`** - Pruebas interactivas de API
- **`test_mesa_partes_api.html`** - Pruebas específicas de mesa de partes

#### **Inicio Rápido**
```bash
# 1. Iniciar servidor Laravel
php artisan serve

# 2. Abrir test_api.html en navegador
# 3. Hacer clic en "Test Login"
# 4. Explorar las funciones disponibles
```

---

## 🎉 Estado de Implementación

### ✅ Funcionalidades Completadas

- [x] **Sistema de autenticación** completo con Sanctum
- [x] **Gestión de roles y permisos** con Spatie
- [x] **CRUD completo de expedientes** con validaciones
- [x] **Workflow de expedientes** funcional con estados
- [x] **Sistema de archivos** operativo con validaciones
- [x] **Auditoría y historial** implementado completamente
- [x] **API RESTful** con 40+ endpoints documentados
- [x] **Consultas públicas** activas sin autenticación
- [x] **Dashboard administrativo** con estadísticas
- [x] **Sistema de notificaciones** por email
- [x] **Validaciones de negocio** implementadas
- [x] **Documentación completa** actualizada

### 🗄️ Base de Datos Implementada

#### **Tablas Principales**
- ✅ **`users`** - Usuarios del sistema con roles
- ✅ **`gerencias`** - Estructura organizacional unificada  
- ✅ **`expedientes`** - Expedientes principales
- ✅ **`documentos_expediente`** - Archivos adjuntos
- ✅ **`historial_expedientes`** - Auditoría completa
- ✅ **`mesa_partes`** - Registro inicial de documentos
- ✅ **`tipos_documento`** - Catálogo de tipos de documento
- ✅ **`tipos_tramite`** - Catálogo de tipos de trámite

#### **Tablas de Seguridad (Spatie)**
- ✅ **`roles`** - Roles del sistema
- ✅ **`permissions`** - Permisos específicos
- ✅ **`model_has_roles`** - Asignación usuario-rol
- ✅ **`model_has_permissions`** - Permisos directos
- ✅ **`role_has_permissions`** - Permisos por rol

### 📊 Características Avanzadas

#### **1. Workflow Dinámico**
- Reglas de flujo configurables por tipo de trámite
- Validaciones automáticas de gerencia vs trámite
- Escalamiento automático por tiempo límite

#### **2. Sistema de Archivos**
- Carga múltiple de documentos
- Validación de tipos y tamaños
- Almacenamiento seguro en storage/
- Descarga controlada con permisos

#### **3. Auditoría Completa**
- Registro de todas las acciones
- Historial detallado por expediente
- Trazabilidad de cambios de estado
- Reportes de actividad por usuario

### 🚀 Instrucciones de Despliegue

#### **Desarrollo Local**
```bash
# Clonar repositorio
git clone [repo-url]
cd backend-muni

# Instalar dependencias
composer install
npm install

# Configurar environment
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate --seed

# Iniciar servidor
php artisan serve
```

#### **Producción**
```bash
# Optimizar para producción
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Configurar permisos
chmod -R 755 storage bootstrap/cache
```

---

## 📞 Contacto y Soporte

### **Enlaces Útiles**
- **Backend API**: `http://localhost:8000/api`
- **Pruebas**: Usar archivos test_*.html
- **Logs**: Revisar `storage/logs/laravel.log`
- **Reset datos**: `php artisan migrate:fresh --seed`

### **Estructura del Proyecto**
```
backend-muni/
├── app/
│   ├── Http/Controllers/     # Controladores de API
│   ├── Models/              # Modelos Eloquent
│   └── Services/            # Servicios de negocio
├── database/
│   ├── migrations/          # Migraciones de BD
│   └── seeders/            # Datos de prueba
├── routes/
│   └── api.php             # Rutas de API
├── storage/
│   └── app/                # Archivos subidos
└── tests/                  # Pruebas unitarias
```

---

**🎉 ¡SISTEMA COMPLETAMENTE IMPLEMENTADO Y LISTO PARA USO EN PRODUCCIÓN!**

---

*Este documento contiene toda la información necesaria para instalar, configurar, usar y mantener el Sistema de Trámite Documentario Municipal. Mantén este archivo actualizado conforme evolucione el sistema.*