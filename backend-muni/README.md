# 🏛️ Sistema de Trámite Documentario Municipal

## 📋 Descripción

Sistema integral de gestión de expedientes municipales desarrollado en **Laravel 11** con **workflows personalizables**. Permite a ciudadanos registrar solicitudes y a funcionarios procesarlas según roles y permisos específicos.

---

## ✨ Características Principales del Sistema

### 🔐 **Seguridad y Autenticación Completa**
- **Laravel Sanctum** para tokens API seguros
- **59 permisos granulares** con Spatie Permission
- **7 roles predefinidos** con jerarquía de permisos
- **Middleware de autorización** en todas las rutas protegidas
- **Guards web y api** configurados

### 👥 **Gestión Completa de Usuarios y Roles**
- ✅ **CRUD de usuarios** con validaciones
- ✅ **Creación y edición de roles** personalizados
- ✅ **Asignación de permisos** granular por usuario/rol
- ✅ **Gestión de estados** (activo/inactivo)
- ✅ **Campos personalizados**: teléfono, cargo, gerencia
- ✅ **Cambio de contraseñas** seguro
- ✅ **Verificación de email** disponible

### 🏢 **Arquitectura de Gerencias Jerárquica**
- ✅ **Creación de gerencias** principales
- ✅ **Subgerencias ilimitadas** (estructura padre-hijo)
- ✅ **Asignación de usuarios** a múltiples gerencias
- ✅ **Flujos específicos** por tipo de gerencia
- ✅ **Estadísticas por gerencia** individuales
- ✅ **Jerarquía completa** visualizable

### 📋 **Gestión Avanzada de Expedientes**
- ✅ **CRUD completo** con validaciones
- ✅ **Estados dinámicos** según workflow asignado
- ✅ **Derivaciones entre gerencias** con trazabilidad
- ✅ **Asignación a funcionarios** específicos
- ✅ **Historial completo** de cambios y movimientos
- ✅ **Gestión documental** integrada (carga de archivos)
- ✅ **Búsqueda avanzada** por múltiples criterios
- ✅ **Exportación de datos** (Excel, PDF)
- ✅ **Prioridades** y estados personalizados

### 🔄 **Workflows Personalizables Avanzados** ⭐ **NUEVO**
- ✅ **Creación visual** desde la interfaz web
- ✅ **Pasos configurables**: Inicio, Proceso, Decisión, Fin
- ✅ **Transiciones condicionales** con reglas JSON
- ✅ **Activación/Desactivación** dinámica de workflows
- ✅ **Clonación** de workflows existentes
- ✅ **Múltiples tipos**: Expediente, Trámite, Proceso
- ✅ **Configuración JSON** para pasos y transiciones
- ✅ **API REST completa** para integración frontend

### 📝 **Mesa de Partes Completa**
- ✅ **Registro de documentos** de entrada
- ✅ **Códigos de seguimiento** únicos automáticos
- ✅ **Consulta pública** por código de seguimiento
- ✅ **Tipos de documento** configurables
- ✅ **Tipos de trámite** con documentos requeridos
- ✅ **Derivación automática** según reglas
- ✅ **Observaciones** y seguimiento de estados

### 📊 **Reportes y Estadísticas**
- ✅ **Dashboard administrativo** con métricas
- ✅ **Estadísticas por gerencia** individuales
- ✅ **Reportes de expedientes** (creados, procesados, tiempos)
- ✅ **Estadísticas de usuarios** y actividad
- ✅ **Métricas de mesa de partes** y recepción

---

## 🎯 Funcionalidades Detalladas del Administrador

### 👤 **Gestión de Usuarios**
El administrador puede realizar las siguientes acciones:

#### **Crear Usuarios**
```http
POST /api/usuarios
{
    "name": "Juan Pérez",
    "email": "juan@municipalidad.com",
    "password": "password123",
    "telefono": "+51987654321",
    "cargo": "Funcionario de Licencias",
    "activo": true
}
```

#### **Asignar Roles a Usuarios**
```http
POST /api/usuarios/{user}/roles
{
    "role": "funcionario"
}
```

#### **Asignar Permisos Específicos**
```http
POST /api/usuarios/{user}/permissions
{
    "permissions": ["crear_expedientes", "derivar_expediente"]
}
```

#### **Sincronizar Permisos**
```http
POST /api/usuarios/{user}/permissions/sync
{
    "permissions": ["ver_expedientes", "crear_expedientes", "editar_expedientes"]
}
```

### 🎭 **Gestión de Roles y Permisos**

#### **Crear Roles Personalizados**
```http
POST /api/roles
{
    "name": "Supervisor de Licencias",
    "guard_name": "web",
    "permissions": ["ver_expedientes", "aprobar_expediente"]
}
```

#### **Crear Permisos Personalizados**
```http
POST /api/permissions
{
    "name": "revisar_licencias_comerciales",
    "guard_name": "web"
}
```

#### **Editar Roles Existentes**
```http
PUT /api/roles/{role}
{
    "name": "Supervisor de Licencias Actualizado",
    "permissions": ["ver_expedientes", "aprobar_expediente", "rechazar_expediente"]
}
```

### 🏢 **Gestión de Gerencias**

#### **Crear Gerencias Principales**
```http
POST /api/gerencias
{
    "nombre": "Gerencia de Desarrollo Urbano",
    "codigo": "GDU",
    "descripcion": "Encargada del desarrollo urbano municipal",
    "tipo": "operativa",
    "activo": true
}
```

#### **Crear Subgerencias**
```http
POST /api/gerencias
{
    "nombre": "Subgerencia de Licencias",
    "codigo": "SGL",
    "descripcion": "Manejo de licencias de funcionamiento",
    "tipo": "subgerencia",
    "parent_id": 1,
    "activo": true
}
```

#### **Asignar Usuarios a Gerencias**
```http
POST /api/gerencias/{gerencia}/usuarios
{
    "user_id": 5,
    "cargo_especifico": "Especialista en Licencias"
}
```

#### **Obtener Jerarquía Completa**
```http
GET /api/gerencias/jerarquia
```

### � **Gestión de Expedientes**

#### **Derivar Expedientes Entre Gerencias**
```http
POST /api/expedientes/{expediente}/derivar
{
    "gerencia_destino_id": 2,
    "usuario_destino_id": 8,
    "observaciones": "Requiere revisión técnica especializada",
    "prioridad": "alta"
}
```

#### **Aprobar/Rechazar Expedientes**
```http
POST /api/expedientes/{expediente}/aprobar
{
    "observaciones": "Expediente aprobado según normativa vigente",
    "documento_resolution": "RES-2025-001"
}

POST /api/expedientes/{expediente}/rechazar
{
    "motivo": "Documentación incompleta",
    "observaciones": "Falta certificado de zonificación"
}
```

#### **Subir Documentos**
```http
POST /api/expedientes/{expediente}/documentos
Content-Type: multipart/form-data
{
    "archivo": [archivo],
    "tipo_documento": "resolucion",
    "descripcion": "Resolución de aprobación"
}
```

### 🔄 **Gestión de Workflows Personalizables**

#### **Crear Workflow Completo**
```http
POST /api/custom-workflows
{
    "nombre": "Flujo de Licencias Comerciales",
    "descripcion": "Proceso completo para licencias de funcionamiento",
    "tipo": "expediente",
    "activo": true
}
```

#### **Crear Pasos del Workflow**
```http
POST /api/custom-workflow-steps
{
    "custom_workflow_id": 1,
    "nombre": "Revisión Inicial",
    "descripcion": "Verificación de documentos básicos",
    "tipo": "proceso",
    "orden": 1,
    "configuracion": {
        "requiere_aprobacion": true,
        "tiempo_limite_dias": 5,
        "usuarios_autorizados": ["funcionario", "supervisor"]
    },
    "activo": true
}
```

#### **Crear Transiciones**
```http
POST /api/custom-workflow-transitions
{
    "custom_workflow_id": 1,
    "from_step_id": 1,
    "to_step_id": 2,
    "nombre": "Aprobar Revisión",
    "descripcion": "Transición cuando la revisión es aprobada",
    "condicion": {
        "estado_anterior": "revision_inicial",
        "accion": "aprobar",
        "rol_requerido": "supervisor"
    },
    "orden": 1,
    "activo": true
}
```

#### **Clonar Workflows**
```http
POST /api/custom-workflows/{id}/clone
{
    "nuevo_nombre": "Flujo de Licencias Comerciales - Copia",
    "modificaciones": {
        "tipo": "tramite"
    }
}
```

### 📝 **Gestión de Mesa de Partes**

#### **Configurar Tipos de Trámite**
```http
POST /api/tipos-tramite
{
    "nombre": "Licencia de Funcionamiento",
    "codigo": "LF",
    "descripcion": "Trámite para obtener licencia comercial",
    "documentos_requeridos": [
        "DNI del solicitante",
        "Certificado de zonificación",
        "Plano de distribución"
    ],
    "costo": 150.00,
    "tiempo_respuesta_dias": 15
}
```

#### **Derivar Documentos Automáticamente**
```http
POST /api/mesa-partes/{id}/derivar
{
    "gerencia_destino_id": 2,
    "usuario_asignado_id": 5,
    "prioridad": "normal",
    "observaciones": "Derivado según tipo de trámite"
}
```

---

## 🚀 Tecnologías

- **Backend**: Laravel 11
- **Base de Datos**: SQLite/MySQL
- **Autenticación**: Laravel Sanctum
- **Permisos**: Spatie Laravel Permission
- **Documentación**: Markdown completo

---

## 📦 Instalación Rápida

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar environment
cp .env.example .env
php artisan key:generate

# 3. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_DATABASE=tramite_muni
DB_USERNAME=usuario
DB_PASSWORD=password

# 4. Ejecutar migraciones y seeders
php artisan migrate
php artisan db:seed

# 5. Crear enlace simbólico para storage
php artisan storage:link

# 6. Iniciar servidor
php artisan serve
```

---

## 🌐 URLs de Prueba

### **Interfaz Administrativa**
```
http://localhost:8000/test_admin.html
```

### **API de Expedientes**
```
http://localhost:8000/test_api.html
```

### **Mesa de Partes**
```
http://localhost:8000/test_mesa_partes_api.html
```

---

## 👤 Usuarios de Prueba

| Rol | Email | Password | Permisos |
|-----|-------|----------|----------|
| Super Admin | `superadmin@example.com` | `password` | Todos (59 permisos) |
| Admin | `admin@example.com` | `password` | Gestión completa + workflows |
| Jefe de Gerencia | `jefe@example.com` | `password` | Gestión de gerencia + workflows básicos |
| Funcionario | `funcionario@example.com` | `password` | Procesamiento de expedientes |
| Ciudadano | `ciudadano@example.com` | `password` | Creación y consulta |

---

## 📱 API Endpoints Completos

### 🔐 **Autenticación** (`/api/auth/*`)
```http
POST   /api/auth/login                    # Login
POST   /api/auth/register                 # Registro
POST   /api/auth/logout                   # Logout
GET    /api/auth/user                     # Usuario actual
POST   /api/auth/refresh                  # Refresh token
POST   /api/auth/change-password          # Cambiar contraseña
GET    /api/auth/check-email              # Verificar email
```

### 👥 **Gestión de Usuarios** (`/api/usuarios/*`)
```http
GET    /api/usuarios                      # Listar usuarios
POST   /api/usuarios                      # Crear usuario
GET    /api/usuarios/{id}                 # Obtener usuario
PUT    /api/usuarios/{id}                 # Actualizar usuario
DELETE /api/usuarios/{id}                 # Eliminar usuario
POST   /api/usuarios/{id}/estado          # Cambiar estado
POST   /api/usuarios/{id}/roles           # Asignar rol
DELETE /api/usuarios/{id}/roles/{role}    # Remover rol
POST   /api/usuarios/{id}/permissions     # Asignar permisos
POST   /api/usuarios/{id}/permissions/sync # Sincronizar permisos
POST   /api/usuarios/{id}/password        # Cambiar contraseña
GET    /api/usuarios/role/{role}          # Usuarios por rol
GET    /api/usuarios/gerencia/{gerencia}  # Usuarios por gerencia
```

### 🎭 **Roles y Permisos** (`/api/roles/*`, `/api/permissions/*`)
```http
GET    /api/roles                         # Listar roles
POST   /api/roles                         # Crear rol
GET    /api/roles/{role}                  # Obtener rol
PUT    /api/roles/{role}                  # Actualizar rol
DELETE /api/roles/{role}                  # Eliminar rol

GET    /api/permissions                   # Listar permisos
POST   /api/permissions                   # Crear permiso
GET    /api/permissions/{permission}      # Obtener permiso
PUT    /api/permissions/{permission}      # Actualizar permiso
DELETE /api/permissions/{permission}      # Eliminar permiso
```

### 🏢 **Gerencias** (`/api/gerencias/*`)
```http
GET    /api/gerencias                     # Listar gerencias
POST   /api/gerencias                     # Crear gerencia
GET    /api/gerencias/{id}                # Obtener gerencia
PUT    /api/gerencias/{id}                # Actualizar gerencia
DELETE /api/gerencias/{id}                # Eliminar gerencia
POST   /api/gerencias/{id}/estado         # Cambiar estado
GET    /api/gerencias/{id}/subgerencias   # Obtener subgerencias
GET    /api/gerencias/{id}/usuarios       # Usuarios de gerencia
POST   /api/gerencias/{id}/usuarios       # Asignar usuario
DELETE /api/gerencias/{id}/usuarios/{user} # Remover usuario
GET    /api/gerencias/jerarquia           # Jerarquía completa
GET    /api/gerencias/tipo/{tipo}         # Gerencias por tipo
```

### 📋 **Expedientes** (`/api/expedientes/*`)
```http
GET    /api/expedientes                   # Listar expedientes
POST   /api/expedientes                   # Crear expediente
GET    /api/expedientes/{id}              # Obtener expediente
PUT    /api/expedientes/{id}              # Actualizar expediente
DELETE /api/expedientes/{id}              # Eliminar expediente
POST   /api/expedientes/{id}/derivar      # Derivar expediente
POST   /api/expedientes/{id}/aprobar      # Aprobar expediente
POST   /api/expedientes/{id}/rechazar     # Rechazar expediente
POST   /api/expedientes/{id}/documentos   # Subir documento
GET    /api/expedientes/{id}/history      # Historial de cambios
POST   /api/expedientes/{id}/assign       # Asignar a usuario
GET    /api/expedientes/estadisticas      # Estadísticas
GET    /api/expedientes/exportar          # Exportar datos
```

### 🔄 **Workflows Personalizables** ⭐ (`/api/custom-workflows/*`)
```http
GET    /api/custom-workflows              # Listar workflows
POST   /api/custom-workflows              # Crear workflow
GET    /api/custom-workflows/{id}         # Obtener workflow
PUT    /api/custom-workflows/{id}         # Actualizar workflow
DELETE /api/custom-workflows/{id}         # Eliminar workflow
POST   /api/custom-workflows/{id}/toggle  # Activar/desactivar
POST   /api/custom-workflows/{id}/clone   # Clonar workflow
GET    /api/custom-workflows/tipo/{tipo}  # Por tipo

# Pasos de Workflow
GET    /api/custom-workflow-steps         # Listar pasos
POST   /api/custom-workflow-steps         # Crear paso
GET    /api/custom-workflow-steps/{id}    # Obtener paso
PUT    /api/custom-workflow-steps/{id}    # Actualizar paso
DELETE /api/custom-workflow-steps/{id}    # Eliminar paso

# Transiciones de Workflow
GET    /api/custom-workflow-transitions   # Listar transiciones
POST   /api/custom-workflow-transitions   # Crear transición
GET    /api/custom-workflow-transitions/{id} # Obtener transición
PUT    /api/custom-workflow-transitions/{id} # Actualizar transición
DELETE /api/custom-workflow-transitions/{id} # Eliminar transición
```

### 📝 **Mesa de Partes** (`/api/mesa-partes/*`)
```http
GET    /api/mesa-partes                   # Listar documentos
POST   /api/mesa-partes                   # Crear documento
GET    /api/mesa-partes/{id}              # Obtener documento
PUT    /api/mesa-partes/{id}              # Actualizar documento
POST   /api/mesa-partes/{id}/derivar      # Derivar documento
POST   /api/mesa-partes/{id}/observar     # Agregar observación
GET    /api/mesa-partes/tipos/tramites    # Tipos de trámite
GET    /api/mesa-partes/tipos/documentos  # Tipos de documento
GET    /api/mesa-partes/reportes/estadisticas # Estadísticas
```

---

## 🗄️ Base de Datos Completa

### 📊 **Tablas Principales**
- `users` - Usuarios del sistema con roles y permisos
- `expedientes` - Expedientes municipales con workflows
- `custom_workflows` ⭐ - Workflows personalizables
- `custom_workflow_steps` ⭐ - Pasos de workflow
- `custom_workflow_transitions` ⭐ - Transiciones de workflow
- `gerencias` - Estructura jerárquica de gerencias
- `mesa_partes` - Documentos de entrada y seguimiento
- `roles` / `permissions` - Sistema de permisos granular

### 🔗 **Relaciones Clave**
- Users ↔ Roles/Permissions (Many-to-Many)
- Users ↔ Gerencias (Many-to-Many)
- Expedientes → CustomWorkflows (Utiliza workflow)
- CustomWorkflows → CustomWorkflowSteps (Tiene pasos)
- CustomWorkflowSteps → CustomWorkflowTransitions (Conecta pasos)
- Gerencias → Gerencias (Padre-Hijo para jerarquía)

---

## 🔑 Sistema de Permisos - 59 Permisos Granulares

### **Permisos de Expedientes** (13 permisos)
- `ver_expedientes`, `crear_expedientes`, `editar_expedientes`
- `eliminar_expedientes`, `derivar_expediente`, `aprobar_expediente`
- `rechazar_expediente`, `finalizar_expediente`, `archivar_expediente`
- `subir_documento`, `eliminar_documento`, `ver_expedientes_todos`

### **Permisos de Usuarios** (11 permisos)
- `gestionar_usuarios`, `crear_usuarios`, `editar_usuarios`
- `eliminar_usuarios`, `asignar_roles`, `gestionar_permisos`
- `ver_usuarios_todos`, `cambiar_contraseña`, `ver_logs`

### **Permisos de Gerencias** (8 permisos)
- `gestionar_gerencias`, `crear_gerencias`, `editar_gerencias`
- `eliminar_gerencias`, `asignar_usuarios_gerencia`, `ver_estadisticas_gerencia`

### **Permisos de Workflows** ⭐ (7 permisos)
- `gestionar_workflows`, `crear_workflows`, `editar_workflows`
- `eliminar_workflows`, `ver_workflows`, `activar_workflows`, `clonar_workflows`

### **Permisos de Mesa de Partes** (6 permisos)
- `ver_mesa_partes`, `crear_mesa_partes`, `editar_mesa_partes`
- `derivar_mesa_partes`, `observar_mesa_partes`, `ver_estadisticas_mesa_partes`

### **Permisos Adicionales** (14 permisos)
- Reportes, estadísticas, configuración, notificaciones, pagos, quejas, flujos

---

## 📊 Estado de Implementación

### ✅ **Backend Completo (100%)**
- ✅ Sistema de autenticación con Sanctum
- ✅ Gestión completa de usuarios, roles y permisos (59 permisos)
- ✅ Arquitectura de gerencias jerárquica implementada
- ✅ Sistema de expedientes con derivaciones completo
- ✅ Mesa de partes operativa
- ✅ **Workflows personalizables completos** ⭐
- ✅ API RESTful completa (40+ endpoints)
- ✅ Base de datos optimizada con 15+ tablas
- ✅ Validaciones y middleware de seguridad
- ✅ Seeders completos con datos de prueba

### 🚧 **Próximas Fases**
- 🔄 Interfaz web para gestión visual de workflows
- 🔄 Dashboard administrativo completo
- 🔄 Motor de ejecución automática de workflows
- 🔄 Sistema de notificaciones por email
- 🔄 Reportes avanzados con gráficos

---

## 📞 Soporte

- 📧 Email: soporte@municipalidad.com
- 📱 WhatsApp: +51 999 999 999
- 📋 Issues: [GitHub Issues]

---

**Versión**: 2.0.0  
**Estado**: Backend Completo ✅  
**Workflows**: Implementados ⭐  
**Última actualización**: Septiembre 2025

## 📦 Instalación Rápida

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar environment
cp .env.example .env
php artisan key:generate

# 3. Configurar base de datos en .env
DB_CONNECTION=mysql
DB_DATABASE=tramite_muni
DB_USERNAME=usuario
DB_PASSWORD=password

# 4. Ejecutar migraciones y seeders
php artisan migrate
php artisan db:seed

# 5. Crear enlace simbólico para storage
php artisan storage:link

# 6. Iniciar servidor
php artisan serve
```

## 🌐 URLs de Prueba

### **Interfaz Administrativa**
```
http://localhost:8000/test_admin.html
```

### **API de Expedientes**
```
http://localhost:8000/test_api.html
```

### **Mesa de Partes**
```
http://localhost:8000/test_mesa_partes_api.html
```

## 👤 Usuarios de Prueba

| Rol | Email | Password | Permisos |
|-----|-------|----------|----------|
| Super Admin | `superadmin@example.com` | `password` | Todos |
| Admin | `admin@example.com` | `password` | Gestión completa |
| Jefe de Gerencia | `jefe@example.com` | `password` | Gestión de gerencia |
| Funcionario | `funcionario@example.com` | `password` | Procesamiento básico |
| Ciudadano | `ciudadano@example.com` | `password` | Creación y consulta |

## 📱 API Endpoints Principales

### 🔐 **Autenticación**
```http
POST /api/auth/login
POST /api/auth/register
POST /api/auth/logout
GET  /api/auth/user
```

### 📋 **Expedientes**
```http
GET    /api/expedientes
POST   /api/expedientes
GET    /api/expedientes/{id}
PUT    /api/expedientes/{id}
DELETE /api/expedientes/{id}
```

### 🔄 **Workflows Personalizables** ⭐
```http
GET    /api/custom-workflows
POST   /api/custom-workflows
GET    /api/custom-workflows/{id}
PUT    /api/custom-workflows/{id}
DELETE /api/custom-workflows/{id}
POST   /api/custom-workflows/{id}/clone
```

### 🏢 **Gerencias**
```http
GET    /api/gerencias
POST   /api/gerencias
GET    /api/gerencias/{id}
PUT    /api/gerencias/{id}
DELETE /api/gerencias/{id}
```

### 📝 **Mesa de Partes**
```http
GET    /api/mesa-partes
POST   /api/mesa-partes
GET    /api/mesa-partes/{id}
PUT    /api/mesa-partes/{id}
```

## 🗄️ Base de Datos

### 📊 **Tablas Principales**
- `users` - Usuarios del sistema
- `expedientes` - Expedientes municipales
- `custom_workflows` ⭐ - Workflows personalizables
- `custom_workflow_steps` ⭐ - Pasos de workflow
- `custom_workflow_transitions` ⭐ - Transiciones de workflow
- `gerencias` - Estructura de gerencias
- `mesa_partes` - Documentos de entrada
- `roles` / `permissions` - Sistema de permisos

### 🔗 **Relaciones Clave**
- Users ↔ Roles/Permissions (Many-to-Many)
- Expedientes → CustomWorkflows (Utiliza workflow)
- CustomWorkflows → CustomWorkflowSteps (Tiene pasos)
- CustomWorkflowSteps → CustomWorkflowTransitions (Conecta pasos)

## 📊 Estado de Implementación

### ✅ **Backend Completo (100%)**
- ✅ Sistema de autenticación con Sanctum
- ✅ Gestión completa de usuarios, roles y permisos (59 permisos)
- ✅ Arquitectura de gerencias implementada
- ✅ Sistema de expedientes funcional
- ✅ Mesa de partes operativa
- ✅ **Workflows personalizables completos** ⭐
- ✅ API RESTful completa (25+ endpoints)
- ✅ Base de datos optimizada
- ✅ Validaciones y middleware
- ✅ Seeders y migraciones completos

### 🚧 **Próximas Fases**
- 🔄 Interfaz web para gestión de workflows
- 🔄 Dashboard administrativo completo
- 🔄 Motor de ejecución automática de workflows
- 🔄 Sistema de notificaciones
- 🔄 Reportes avanzados con gráficos

## 📚 Documentación Completa

Para documentación detallada, ver:
- **[DOCUMENTACION_COMPLETA.md](DOCUMENTACION_COMPLETA.md)** - Documentación técnica completa
- **[DOCUMENTACION_FINAL.md](DOCUMENTACION_FINAL.md)** - Guía de implementación

## 📞 Soporte

- 📧 Email: soporte@municipalidad.com
- 📱 WhatsApp: +51 999 999 999
- 📋 Issues: [GitHub Issues]

---

**Versión**: 2.0.0
**Estado**: Backend Completo ✅
**Última actualización**: Septiembre 2025
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

## 🏗️ Arquitectura del Sistema

### Modelos Principales
- **User**: Usuarios del sistema con roles específicos
- **Gerencia**: Estructura organizacional unificada
- **Expediente**: Trámites y solicitudes ciudadanas
- **DocumentoExpediente**: Archivos adjuntos
- **HistorialExpediente**: Auditoría de cambios

### Roles del Sistema
- **Mesa de Partes**: Registro y derivación inicial
- **Gerente Urbano**: Revisión técnica especializada
- **Inspector**: Inspecciones de campo
- **Secretaria General**: Revisión legal y resoluciones
- **Alcalde**: Firma de actos administrativos mayores
- **Admin**: Gestión completa del sistema

## 🔄 Flujo de Trabajo

### 1. Ciudadano
- Registra solicitud de trámite
- Sube documentos requeridos
- Recibe número de expediente

### 2. Mesa de Partes
- Valida requisitos mínimos
- Deriva a gerencia correspondiente
- Puede rechazar si no cumple requisitos

### 3. Gerencia/Subgerencia
- Realiza revisión técnica
- Ejecuta inspecciones (si aplica)
- Determina si requiere revisión legal

### 4. Secretaría General
- Revisión legal cuando es requerida
- Emite resoluciones
- Determina si requiere firma alcalde

### 5. Alcalde
- Firma actos administrativos mayores
- Resoluciones de alto impacto

## 📚 Documentación

**📖 [DOCUMENTACIÓN COMPLETA](./DOCUMENTACION_COMPLETA.md)** - Toda la información del sistema en un solo lugar

La documentación completa incluye:
- ✅ **Instalación y configuración** paso a paso
- ✅ **Arquitectura del sistema** completa
- ✅ **API RESTful** con todos los endpoints
- ✅ **Sistema de permisos** detallado
- ✅ **Instrucciones de prueba** exhaustivas
- ✅ **Estado de implementación** actual

### 🎯 Enlaces Rápidos
- **Archivos de prueba**: `test_api.html` y `test_mesa_partes_api.html`
- **Backend**: `http://localhost:8000`
- **API**: `http://localhost:8000/api`

## 🧪 Testing

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter ExpedienteTest
```

## 📱 Endpoints Principales

```
GET    /api/expedientes          # Listar expedientes
POST   /api/expedientes          # Crear expediente
GET    /api/expedientes/{id}     # Ver expediente
PUT    /api/expedientes/{id}     # Actualizar expediente
POST   /api/expedientes/{id}/derivar   # Derivar expediente
POST   /api/expedientes/{id}/documents # Subir documentos
```

## 🔧 Configuración

### Variables de Entorno Importantes
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:4200
SESSION_DOMAIN=localhost
```

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

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Equipo de Desarrollo

Desarrollado para la gestión municipal de trámites documentarios.

---

### 🚀 Enlaces Rápidos

- **Backend API**: `http://localhost:8000/api`
- **Documentación**: Ver archivos .md en el directorio raíz
- **Pruebas**: `test_api.html` y `test_mesa_partes_api.html`
