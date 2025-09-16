# 📋 API del Sistema de Trámites Documentarios Municipal# 🏛️ Sistema de Trámite Documentario Municipal



## 🚀 Estado del Servidor## 📋 Descripción

**Servidor activo:** `http://127.0.0.1:8000`

Sistema integral de gestión de expedientes municipales desarrollado en **Laravel 11** con **workflows personalizables**. Permite a ciudadanos registrar solicitudes y a funcionarios procesarlas según roles y permisos específicos.

---

---

## 📑 Índice de Endpoints

## ✨ Características Principales del Sistema

### 🔑 [Autenticación](#autenticacion)

### 👥 [Usuarios](#usuarios)### 🔐 **Seguridad y Autenticación Completa**

### 🏢 [Gerencias](#gerencias)- **Laravel Sanctum** para tokens API seguros

### 📄 [Expedientes](#expedientes)- **59 permisos granulares** con Spatie Permission

### 📋 [Mesa de Partes](#mesa-de-partes)- **7 roles predefinidos** con jerarquía de permisos

### 🔄 [Workflows Personalizables](#workflows)- **Middleware de autorización** en todas las rutas protegidas

### 👮‍♂️ [Roles y Permisos](#roles-y-permisos)- **Guards web y api** configurados

### 📊 [Catálogos](#catalogos)

### 👥 **Gestión Completa de Usuarios y Roles**

---- ✅ **CRUD de usuarios** con validaciones

- ✅ **Creación y edición de roles** personalizados

## 🔑 Autenticación {#autenticacion}- ✅ **Asignación de permisos** granular por usuario/rol

- ✅ **Gestión de estados** (activo/inactivo)

### Login- ✅ **Campos personalizados**: teléfono, cargo, gerencia

```http- ✅ **Cambio de contraseñas** seguro

POST /api/auth/login- ✅ **Verificación de email** disponible

Content-Type: application/json

### 🏢 **Arquitectura de Gerencias Jerárquica**

{- ✅ **Creación de gerencias** principales

    "email": "admin@municipalidad.gob.pe",- ✅ **Subgerencias ilimitadas** (estructura padre-hijo)

    "password": "admin123"- ✅ **Asignación de usuarios** a múltiples gerencias

}- ✅ **Flujos específicos** por tipo de gerencia

```- ✅ **Estadísticas por gerencia** individuales

- ✅ **Jerarquía completa** visualizable

**Respuesta exitosa:**

```json### 📋 **Gestión Avanzada de Expedientes**

{- ✅ **CRUD completo** con validaciones

    "success": true,- ✅ **Estados dinámicos** según workflow asignado

    "data": {- ✅ **Derivaciones entre gerencias** con trazabilidad

        "user": {- ✅ **Asignación a funcionarios** específicos

            "id": 1,- ✅ **Historial completo** de cambios y movimientos

            "name": "Administrador Principal",- ✅ **Gestión documental** integrada (carga de archivos)

            "email": "admin@municipalidad.gob.pe",- ✅ **Búsqueda avanzada** por múltiples criterios

            "roles": ["admin"]- ✅ **Exportación de datos** (Excel, PDF)

        },- ✅ **Prioridades** y estados personalizados

        "token": "1|abcd1234efgh5678ijkl9012mnop3456"

    }### 🔄 **Workflows Personalizables Avanzados** ⭐ **NUEVO**

}- ✅ **Creación visual** desde la interfaz web

```- ✅ **Pasos configurables**: Inicio, Proceso, Decisión, Fin

- ✅ **Transiciones condicionales** con reglas JSON

### Registro- ✅ **Activación/Desactivación** dinámica de workflows

```http- ✅ **Clonación** de workflows existentes

POST /api/auth/register- ✅ **Múltiples tipos**: Expediente, Trámite, Proceso

Content-Type: application/json- ✅ **Configuración JSON** para pasos y transiciones

- ✅ **API REST completa** para integración frontend

{

    "name": "Juan Pérez",### 📝 **Mesa de Partes Completa**

    "email": "juan.perez@municipalidad.gob.pe",- ✅ **Registro de documentos** de entrada

    "password": "password123",- ✅ **Códigos de seguimiento** únicos automáticos

    "password_confirmation": "password123",- ✅ **Consulta pública** por código de seguimiento

    "gerencia_id": 2- ✅ **Tipos de documento** configurables

}- ✅ **Tipos de trámite** con documentos requeridos

```- ✅ **Derivación automática** según reglas

- ✅ **Observaciones** y seguimiento de estados

### Obtener Usuario Autenticado

```http### 📊 **Reportes y Estadísticas**

GET /api/auth/user- ✅ **Dashboard administrativo** con métricas

Authorization: Bearer {token}- ✅ **Estadísticas por gerencia** individuales

```- ✅ **Reportes de expedientes** (creados, procesados, tiempos)

- ✅ **Estadísticas de usuarios** y actividad

### Logout- ✅ **Métricas de mesa de partes** y recepción

```http

POST /api/auth/logout---

Authorization: Bearer {token}

```## 🎯 Funcionalidades Detalladas del Administrador



---### 👤 **Gestión de Usuarios**

El administrador puede realizar las siguientes acciones:

## 👥 Usuarios {#usuarios}

#### **Crear Usuarios**

### Listar Usuarios```http

```httpPOST /api/usuarios

GET /api/usuarios{

Authorization: Bearer {token}    "name": "Juan Pérez",

```    "email": "juan@municipalidad.com",

    "password": "password123",

### Crear Usuario    "telefono": "+51987654321",

```http    "cargo": "Funcionario de Licencias",

POST /api/usuarios    "activo": true

Authorization: Bearer {token}}

Content-Type: application/json```



{#### **Asignar Roles a Usuarios**

    "name": "María García",```http

    "email": "maria.garcia@municipalidad.gob.pe",POST /api/usuarios/{user}/roles

    "password": "password123",{

    "gerencia_id": 3,    "role": "funcionario"

    "telefono": "987654321",}

    "cargo": "Especialista en Licencias"```

}

```#### **Asignar Permisos Específicos**

```http

### Obtener UsuarioPOST /api/usuarios/{user}/permissions

```http{

GET /api/usuarios/{id}    "permissions": ["crear_expedientes", "derivar_expediente"]

Authorization: Bearer {token}}

``````



### Actualizar Usuario#### **Sincronizar Permisos**

```http```http

PUT /api/usuarios/{id}POST /api/usuarios/{user}/permissions/sync

Authorization: Bearer {token}{

Content-Type: application/json    "permissions": ["ver_expedientes", "crear_expedientes", "editar_expedientes"]

}

{```

    "name": "María García Actualizada",

    "telefono": "987654322",### 🎭 **Gestión de Roles y Permisos**

    "cargo": "Jefe de Licencias"

}#### **Crear Roles Personalizados**

``````http

POST /api/roles

### Asignar Rol{

```http    "name": "Supervisor de Licencias",

POST /api/usuarios/{id}/roles    "guard_name": "web",

Authorization: Bearer {token}    "permissions": ["ver_expedientes", "aprobar_expediente"]

Content-Type: application/json}

```

{

    "role": "funcionario"#### **Crear Permisos Personalizados**

}```http

```POST /api/permissions

{

---    "name": "revisar_licencias_comerciales",

    "guard_name": "web"

## 🏢 Gerencias {#gerencias}}

```

### Listar Gerencias

```http#### **Editar Roles Existentes**

GET /api/gerencias```http

Authorization: Bearer {token}PUT /api/roles/{role}

```{

    "name": "Supervisor de Licencias Actualizado",

### Crear Gerencia    "permissions": ["ver_expedientes", "aprobar_expediente", "rechazar_expediente"]

```http}

POST /api/gerencias```

Authorization: Bearer {token}

Content-Type: application/json### 🏢 **Gestión de Gerencias**



{#### **Crear Gerencias Principales**

    "nombre": "Gerencia de Medio Ambiente",```http

    "codigo": "GMA",POST /api/gerencias

    "descripcion": "Encargada de temas ambientales",{

    "tipo": "gerencia",    "nombre": "Gerencia de Desarrollo Urbano",

    "gerencia_padre_id": 1,    "codigo": "GDU",

    "activo": true    "descripcion": "Encargada del desarrollo urbano municipal",

}    "tipo": "operativa",

```    "activo": true

}

### Obtener Gerencia```

```http

GET /api/gerencias/{id}#### **Crear Subgerencias**

Authorization: Bearer {token}```http

```POST /api/gerencias

{

### Actualizar Gerencia    "nombre": "Subgerencia de Licencias",

```http    "codigo": "SGL",

PUT /api/gerencias/{id}    "descripcion": "Manejo de licencias de funcionamiento",

Authorization: Bearer {token}    "tipo": "subgerencia",

Content-Type: application/json    "parent_id": 1,

    "activo": true

{}

    "nombre": "Gerencia de Medio Ambiente y Salud",```

    "descripcion": "Encargada de temas ambientales y de salud pública"

}#### **Asignar Usuarios a Gerencias**

``````http

POST /api/gerencias/{gerencia}/usuarios

### Obtener Subgerencias{

```http    "user_id": 5,

GET /api/gerencias/{id}/subgerencias    "cargo_especifico": "Especialista en Licencias"

Authorization: Bearer {token}}

``````



### Obtener Usuarios de Gerencia#### **Obtener Jerarquía Completa**

```http```http

GET /api/gerencias/{id}/usuariosGET /api/gerencias/jerarquia

Authorization: Bearer {token}```

```

### � **Gestión de Expedientes**

---

#### **Derivar Expedientes Entre Gerencias**

## 📄 Expedientes {#expedientes}```http

POST /api/expedientes/{expediente}/derivar

### Listar Expedientes{

```http    "gerencia_destino_id": 2,

GET /api/expedientes    "usuario_destino_id": 8,

Authorization: Bearer {token}    "observaciones": "Requiere revisión técnica especializada",

```    "prioridad": "alta"

}

### Crear Expediente```

```http

POST /api/expedientes#### **Aprobar/Rechazar Expedientes**

Authorization: Bearer {token}```http

Content-Type: application/jsonPOST /api/expedientes/{expediente}/aprobar

{

{    "observaciones": "Expediente aprobado según normativa vigente",

    "numero_expediente": "EXP-2025-000001",    "documento_resolution": "RES-2025-001"

    "asunto": "Solicitud de Licencia de Funcionamiento",}

    "tipo_tramite_id": 1,

    "solicitante_nombre": "Carlos Mendoza",POST /api/expedientes/{expediente}/rechazar

    "solicitante_email": "carlos.mendoza@email.com",{

    "solicitante_telefono": "987654321",    "motivo": "Documentación incompleta",

    "solicitante_dni": "12345678",    "observaciones": "Falta certificado de zonificación"

    "gerencia_id": 2,}

    "prioridad": "normal",```

    "observaciones": "Documentos completos"

}#### **Subir Documentos**

``````http

POST /api/expedientes/{expediente}/documentos

### Obtener ExpedienteContent-Type: multipart/form-data

```http{

GET /api/expedientes/{id}    "archivo": [archivo],

Authorization: Bearer {token}    "tipo_documento": "resolucion",

```    "descripcion": "Resolución de aprobación"

}

### Actualizar Expediente```

```http

PUT /api/expedientes/{id}### 🔄 **Gestión de Workflows Personalizables**

Authorization: Bearer {token}

Content-Type: application/json#### **Crear Workflow Completo**

```http

{POST /api/custom-workflows

    "asunto": "Solicitud de Licencia de Funcionamiento - Actualizada",{

    "observaciones": "Documentos completos y revisados"    "nombre": "Flujo de Licencias Comerciales",

}    "descripcion": "Proceso completo para licencias de funcionamiento",

```    "tipo": "expediente",

    "activo": true

### Derivar Expediente}

```http```

POST /api/expedientes/{id}/derivar

Authorization: Bearer {token}#### **Crear Pasos del Workflow**

Content-Type: application/json```http

POST /api/custom-workflow-steps

{{

    "gerencia_destino_id": 3,    "custom_workflow_id": 1,

    "usuario_destino_id": 5,    "nombre": "Revisión Inicial",

    "observaciones": "Derivado para evaluación técnica"    "descripcion": "Verificación de documentos básicos",

}    "tipo": "proceso",

```    "orden": 1,

    "configuracion": {

### Aprobar Expediente        "requiere_aprobacion": true,

```http        "tiempo_limite_dias": 5,

POST /api/expedientes/{id}/aprobar        "usuarios_autorizados": ["funcionario", "supervisor"]

Authorization: Bearer {token}    },

Content-Type: application/json    "activo": true

}

{```

    "observaciones": "Expediente aprobado conforme a normativa"

}#### **Crear Transiciones**

``````http

POST /api/custom-workflow-transitions

### Rechazar Expediente{

```http    "custom_workflow_id": 1,

POST /api/expedientes/{id}/rechazar    "from_step_id": 1,

Authorization: Bearer {token}    "to_step_id": 2,

Content-Type: application/json    "nombre": "Aprobar Revisión",

    "descripcion": "Transición cuando la revisión es aprobada",

{    "condicion": {

    "motivo": "Documentación incompleta",        "estado_anterior": "revision_inicial",

    "observaciones": "Falta certificado de zonificación"        "accion": "aprobar",

}        "rol_requerido": "supervisor"

```    },

    "orden": 1,

### Subir Documento    "activo": true

```http}

POST /api/expedientes/{id}/documentos```

Authorization: Bearer {token}

Content-Type: multipart/form-data#### **Clonar Workflows**

```http

{POST /api/custom-workflows/{id}/clone

    "file": [archivo],{

    "tipo_documento": "licencia",    "nuevo_nombre": "Flujo de Licencias Comerciales - Copia",

    "descripcion": "Licencia de funcionamiento aprobada"    "modificaciones": {

}        "tipo": "tramite"

```    }

}

---```



## 📋 Mesa de Partes {#mesa-de-partes}### 📝 **Gestión de Mesa de Partes**



### Listar Mesa de Partes#### **Configurar Tipos de Trámite**

```http```http

GET /api/mesa-partesPOST /api/tipos-tramite

Authorization: Bearer {token}{

```    "nombre": "Licencia de Funcionamiento",

    "codigo": "LF",

### Crear Registro en Mesa de Partes    "descripcion": "Trámite para obtener licencia comercial",

```http    "documentos_requeridos": [

POST /api/mesa-partes        "DNI del solicitante",

Authorization: Bearer {token}        "Certificado de zonificación",

Content-Type: application/json        "Plano de distribución"

    ],

{    "costo": 150.00,

    "tipo_documento_id": 1,    "tiempo_respuesta_dias": 15

    "tipo_tramite_id": 1,}

    "remitente_nombre": "Ana López",```

    "remitente_email": "ana.lopez@email.com",

    "remitente_telefono": "987654321",#### **Derivar Documentos Automáticamente**

    "remitente_dni": "87654321",```http

    "asunto": "Solicitud de certificado de numeración",POST /api/mesa-partes/{id}/derivar

    "folio_inicio": 1,{

    "folio_fin": 5,    "gerencia_destino_id": 2,

    "observaciones": "Documentos en original",    "usuario_asignado_id": 5,

    "gerencia_destino_id": 2    "prioridad": "normal",

}    "observaciones": "Derivado según tipo de trámite"

```}

```

### Obtener Registro

```http---

GET /api/mesa-partes/{id}

Authorization: Bearer {token}## 🚀 Tecnologías

```

- **Backend**: Laravel 11

### Derivar Documento- **Base de Datos**: SQLite/MySQL

```http- **Autenticación**: Laravel Sanctum

POST /api/mesa-partes/{id}/derivar- **Permisos**: Spatie Laravel Permission

Authorization: Bearer {token}- **Documentación**: Markdown completo

Content-Type: application/json

---

{

    "gerencia_destino_id": 3,## 📦 Instalación Rápida

    "observaciones": "Derivado para evaluación"

}```bash

```# 1. Instalar dependencias

composer install

### Consulta Pública por Código

```http# 2. Configurar environment

GET /api/mesa-partes/consultar/{codigoSeguimiento}cp .env.example .env

```php artisan key:generate



**Ejemplo:**# 3. Configurar base de datos en .env

```httpDB_CONNECTION=mysql

GET /api/mesa-partes/consultar/MP-2025-000001DB_DATABASE=tramite_muni

```DB_USERNAME=usuario

DB_PASSWORD=password

---

# 4. Ejecutar migraciones y seeders

## 🔄 Workflows Personalizables {#workflows}php artisan migrate

php artisan db:seed

### Listar Workflows

```http# 5. Crear enlace simbólico para storage

GET /api/custom-workflowsphp artisan storage:link

Authorization: Bearer {token}

```# 6. Iniciar servidor

php artisan serve

### Crear Workflow```

```http

POST /api/custom-workflows---

Authorization: Bearer {token}

Content-Type: application/json## 🌐 URLs de Prueba



{### **Interfaz Administrativa**

    "nombre": "Proceso de Licencia de Funcionamiento",```

    "descripcion": "Workflow para licencias comerciales",http://localhost:8000/test_admin.html

    "tipo": "expediente",```

    "gerencia_id": 2,

    "activo": true,### **API de Expedientes**

    "configuracion": {```

        "tiempo_limite_dias": 15,http://localhost:8000/test_api.html

        "requiere_aprobacion": true```

    }

}### **Mesa de Partes**

``````

http://localhost:8000/test_mesa_partes_api.html

### Obtener Workflow```

```http

GET /api/custom-workflows/{id}---

Authorization: Bearer {token}

```## 👤 Usuarios de Prueba



### Crear Paso de Workflow| Rol | Email | Password | Permisos |

```http|-----|-------|----------|----------|

POST /api/custom-workflow-steps| Super Admin | `superadmin@example.com` | `password` | Todos (59 permisos) |

Authorization: Bearer {token}| Admin | `admin@example.com` | `password` | Gestión completa + workflows |

Content-Type: application/json| Jefe de Gerencia | `jefe@example.com` | `password` | Gestión de gerencia + workflows básicos |

| Funcionario | `funcionario@example.com` | `password` | Procesamiento de expedientes |

{| Ciudadano | `ciudadano@example.com` | `password` | Creación y consulta |

    "custom_workflow_id": 1,

    "nombre": "Revisión Inicial",---

    "descripcion": "Revisión de documentos",

    "orden": 1,## 📱 API Endpoints Completos

    "tipo": "manual",

    "responsable_rol": "funcionario",### 🔐 **Autenticación** (`/api/auth/*`)

    "tiempo_limite_horas": 48,```http

    "configuracion": {POST   /api/auth/login                    # Login

        "campos_requeridos": ["observaciones"],POST   /api/auth/register                 # Registro

        "puede_rechazar": truePOST   /api/auth/logout                   # Logout

    }GET    /api/auth/user                     # Usuario actual

}POST   /api/auth/refresh                  # Refresh token

```POST   /api/auth/change-password          # Cambiar contraseña

GET    /api/auth/check-email              # Verificar email

---```



## 👮‍♂️ Roles y Permisos {#roles-y-permisos}### 👥 **Gestión de Usuarios** (`/api/usuarios/*`)

```http

### Listar RolesGET    /api/usuarios                      # Listar usuarios

```httpPOST   /api/usuarios                      # Crear usuario

GET /api/rolesGET    /api/usuarios/{id}                 # Obtener usuario

Authorization: Bearer {token}PUT    /api/usuarios/{id}                 # Actualizar usuario

```DELETE /api/usuarios/{id}                 # Eliminar usuario

POST   /api/usuarios/{id}/estado          # Cambiar estado

### Crear RolPOST   /api/usuarios/{id}/roles           # Asignar rol

```httpDELETE /api/usuarios/{id}/roles/{role}    # Remover rol

POST /api/rolesPOST   /api/usuarios/{id}/permissions     # Asignar permisos

Authorization: Bearer {token}POST   /api/usuarios/{id}/permissions/sync # Sincronizar permisos

Content-Type: application/jsonPOST   /api/usuarios/{id}/password        # Cambiar contraseña

GET    /api/usuarios/role/{role}          # Usuarios por rol

{GET    /api/usuarios/gerencia/{gerencia}  # Usuarios por gerencia

    "name": "especialista_licencias",```

    "guard_name": "web",

    "permissions": ["ver_expediente", "editar_expediente", "derivar_expediente"]### 🎭 **Roles y Permisos** (`/api/roles/*`, `/api/permissions/*`)

}```http

```GET    /api/roles                         # Listar roles

POST   /api/roles                         # Crear rol

### Listar PermisosGET    /api/roles/{role}                  # Obtener rol

```httpPUT    /api/roles/{role}                  # Actualizar rol

GET /api/permissionsDELETE /api/roles/{role}                  # Eliminar rol

Authorization: Bearer {token}

```GET    /api/permissions                   # Listar permisos

POST   /api/permissions                   # Crear permiso

### Crear PermisoGET    /api/permissions/{permission}      # Obtener permiso

```httpPUT    /api/permissions/{permission}      # Actualizar permiso

POST /api/permissionsDELETE /api/permissions/{permission}      # Eliminar permiso

Authorization: Bearer {token}```

Content-Type: application/json

### 🏢 **Gerencias** (`/api/gerencias/*`)

{```http

    "name": "generar_reporte_avanzado",GET    /api/gerencias                     # Listar gerencias

    "guard_name": "web"POST   /api/gerencias                     # Crear gerencia

}GET    /api/gerencias/{id}                # Obtener gerencia

```PUT    /api/gerencias/{id}                # Actualizar gerencia

DELETE /api/gerencias/{id}                # Eliminar gerencia

---POST   /api/gerencias/{id}/estado         # Cambiar estado

GET    /api/gerencias/{id}/subgerencias   # Obtener subgerencias

## 📊 Catálogos {#catalogos}GET    /api/gerencias/{id}/usuarios       # Usuarios de gerencia

POST   /api/gerencias/{id}/usuarios       # Asignar usuario

### Tipos de DocumentoDELETE /api/gerencias/{id}/usuarios/{user} # Remover usuario

```httpGET    /api/gerencias/jerarquia           # Jerarquía completa

GET /api/tipos-documentoGET    /api/gerencias/tipo/{tipo}         # Gerencias por tipo

``````



**Respuesta:**### 📋 **Expedientes** (`/api/expedientes/*`)

```json```http

{GET    /api/expedientes                   # Listar expedientes

    "data": [POST   /api/expedientes                   # Crear expediente

        {GET    /api/expedientes/{id}              # Obtener expediente

            "id": 1,PUT    /api/expedientes/{id}              # Actualizar expediente

            "nombre": "Solicitud Simple",DELETE /api/expedientes/{id}              # Eliminar expediente

            "codigo": "SOL-001",POST   /api/expedientes/{id}/derivar      # Derivar expediente

            "descripcion": "Solicitud de trámite simple",POST   /api/expedientes/{id}/aprobar      # Aprobar expediente

            "requiere_firma": true,POST   /api/expedientes/{id}/rechazar     # Rechazar expediente

            "vigencia_dias": 30POST   /api/expedientes/{id}/documentos   # Subir documento

        }GET    /api/expedientes/{id}/history      # Historial de cambios

    ]POST   /api/expedientes/{id}/assign       # Asignar a usuario

}GET    /api/expedientes/estadisticas      # Estadísticas

```GET    /api/expedientes/exportar          # Exportar datos

```

### Tipos de Trámite

```http### 🔄 **Workflows Personalizables** ⭐ (`/api/custom-workflows/*`)

GET /api/tipos-tramite```http

```GET    /api/custom-workflows              # Listar workflows

POST   /api/custom-workflows              # Crear workflow

**Respuesta:**GET    /api/custom-workflows/{id}         # Obtener workflow

```jsonPUT    /api/custom-workflows/{id}         # Actualizar workflow

{DELETE /api/custom-workflows/{id}         # Eliminar workflow

    "data": [POST   /api/custom-workflows/{id}/toggle  # Activar/desactivar

        {POST   /api/custom-workflows/{id}/clone   # Clonar workflow

            "id": 1,GET    /api/custom-workflows/tipo/{tipo}  # Por tipo

            "nombre": "Licencia de Funcionamiento",

            "codigo": "LF-001",# Pasos de Workflow

            "costo": 125.50,GET    /api/custom-workflow-steps         # Listar pasos

            "tiempo_estimado_dias": 15,POST   /api/custom-workflow-steps         # Crear paso

            "gerencia": {GET    /api/custom-workflow-steps/{id}    # Obtener paso

                "id": 2,PUT    /api/custom-workflow-steps/{id}    # Actualizar paso

                "nombre": "Gerencia de Desarrollo Económico"DELETE /api/custom-workflow-steps/{id}    # Eliminar paso

            }

        }# Transiciones de Workflow

    ]GET    /api/custom-workflow-transitions   # Listar transiciones

}POST   /api/custom-workflow-transitions   # Crear transición

```GET    /api/custom-workflow-transitions/{id} # Obtener transición

PUT    /api/custom-workflow-transitions/{id} # Actualizar transición

### Gerencias (Público)DELETE /api/custom-workflow-transitions/{id} # Eliminar transición

```http```

GET /api/gerencias

```### 📝 **Mesa de Partes** (`/api/mesa-partes/*`)

```http

---GET    /api/mesa-partes                   # Listar documentos

POST   /api/mesa-partes                   # Crear documento

## 🔐 Autenticación Bearer TokenGET    /api/mesa-partes/{id}              # Obtener documento

PUT    /api/mesa-partes/{id}              # Actualizar documento

Todas las rutas protegidas requieren el token en el header:POST   /api/mesa-partes/{id}/derivar      # Derivar documento

POST   /api/mesa-partes/{id}/observar     # Agregar observación

```httpGET    /api/mesa-partes/tipos/tramites    # Tipos de trámite

Authorization: Bearer 1|abcd1234efgh5678ijkl9012mnop3456GET    /api/mesa-partes/tipos/documentos  # Tipos de documento

```GET    /api/mesa-partes/reportes/estadisticas # Estadísticas

```

---

---

## 👥 Usuarios de Prueba

## 🗄️ Base de Datos Completa

| Email | Contraseña | Rol | Gerencia |

|-------|------------|-----|----------|### 📊 **Tablas Principales**

| admin@municipalidad.gob.pe | admin123 | super_admin | Alcaldía |- `users` - Usuarios del sistema con roles y permisos

| gerente.desarrollo@municipalidad.gob.pe | password123 | jefe_gerencia | Desarrollo Económico |- `expedientes` - Expedientes municipales con workflows

| funcionario.licencias@municipalidad.gob.pe | password123 | funcionario | Desarrollo Económico |- `custom_workflows` ⭐ - Workflows personalizables

| ciudadano@email.com | password123 | ciudadano | N/A |- `custom_workflow_steps` ⭐ - Pasos de workflow

- `custom_workflow_transitions` ⭐ - Transiciones de workflow

---- `gerencias` - Estructura jerárquica de gerencias

- `mesa_partes` - Documentos de entrada y seguimiento

## 🚦 Códigos de Estado- `roles` / `permissions` - Sistema de permisos granular



- `200` - OK### 🔗 **Relaciones Clave**

- `201` - Creado exitosamente- Users ↔ Roles/Permissions (Many-to-Many)

- `400` - Error de validación- Users ↔ Gerencias (Many-to-Many)

- `401` - No autorizado- Expedientes → CustomWorkflows (Utiliza workflow)

- `403` - Prohibido (sin permisos)- CustomWorkflows → CustomWorkflowSteps (Tiene pasos)

- `404` - No encontrado- CustomWorkflowSteps → CustomWorkflowTransitions (Conecta pasos)

- `422` - Error de validación de datos- Gerencias → Gerencias (Padre-Hijo para jerarquía)

- `500` - Error del servidor

---

---

## 🔑 Sistema de Permisos - 59 Permisos Granulares

## 📝 Notas Importantes

### **Permisos de Expedientes** (13 permisos)

1. **Permisos:** Muchos endpoints requieren permisos específicos- `ver_expedientes`, `crear_expedientes`, `editar_expedientes`

2. **Paginación:** Los listados soportan parámetros `page` y `per_page`- `eliminar_expedientes`, `derivar_expediente`, `aprobar_expediente`

3. **Filtros:** Usar parámetros de query para filtrar resultados- `rechazar_expediente`, `finalizar_expediente`, `archivar_expediente`

4. **Archivos:** Usar `multipart/form-data` para subir archivos- `subir_documento`, `eliminar_documento`, `ver_expedientes_todos`

5. **Códigos de Seguimiento:** Se generan automáticamente para mesa de partes

### **Permisos de Usuarios** (11 permisos)

---- `gestionar_usuarios`, `crear_usuarios`, `editar_usuarios`

- `eliminar_usuarios`, `asignar_roles`, `gestionar_permisos`

## 🔍 Ejemplos de Filtros- `ver_usuarios_todos`, `cambiar_contraseña`, `ver_logs`



```http### **Permisos de Gerencias** (8 permisos)

GET /api/expedientes?estado=pendiente&gerencia_id=2&page=1&per_page=10- `gestionar_gerencias`, `crear_gerencias`, `editar_gerencias`

GET /api/mesa-partes?fecha_inicio=2025-01-01&fecha_fin=2025-12-31- `eliminar_gerencias`, `asignar_usuarios_gerencia`, `ver_estadisticas_gerencia`

GET /api/usuarios?role=funcionario&gerencia_id=3

```### **Permisos de Workflows** ⭐ (7 permisos)

- `gestionar_workflows`, `crear_workflows`, `editar_workflows`

---- `eliminar_workflows`, `ver_workflows`, `activar_workflows`, `clonar_workflows`



**🎯 Sistema listo para pruebas con Postman!**### **Permisos de Mesa de Partes** (6 permisos)
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
