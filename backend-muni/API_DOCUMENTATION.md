# API Documentation - Sistema de Expedientes Municipales

## Descripción General

Esta API implementa el flujo completo de expedientes municipales según el diagrama UML proporcionado. Permite a los ciudadanos registrar solicitudes, a los funcionarios municipales procesarlas según sus roles, y mantiene un historial completo de todas las acciones.

## Base URL

```
http://localhost:8000/api
```

## Autenticación

La API utiliza autenticación mediante Sanctum. Todas las rutas requieren un token válido en el header:

```
Authorization: Bearer {token}
```

## Endpoints

### 1. Expedientes

#### Listar Expedientes
```http
GET /api/expedientes
```

**Headers:**
- Authorization: Bearer {token}

**Query Parameters:**
- `estado` (opcional): Filtrar por estado
- `gerencia_id` (opcional): Filtrar por gerencia
- `search` (opcional): Búsqueda por número, solicitante o asunto
- `per_page` (opcional): Elementos por página (default: 15)

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "numero": "EXP-2025-000001",
                "solicitante_nombre": "Juan Pérez",
                "solicitante_dni": "12345678",
                "solicitante_email": "juan@example.com",
                "tipo_tramite": "licencia_construccion",
                "asunto": "Solicitud de licencia de construcción",
                "estado": "pendiente",
                "gerencia": {
                    "id": 1,
                    "nombre": "Gerencia de Desarrollo Urbano"
                },
                "created_at": "2025-01-01T00:00:00.000000Z"
            }
        ],
        "total": 1,
        "per_page": 15
    },
    "estados": {
        "pendiente": "Pendiente",
        "en_revision": "En Revisión",
        "revision_tecnica": "Revisión Técnica",
        "revision_legal": "Revisión Legal",
        "resolucion_emitida": "Resolución Emitida",
        "firmado": "Firmado",
        "notificado": "Notificado",
        "rechazado": "Rechazado",
        "completado": "Completado"
    },
    "tipos_tramite": {
        "licencia_construccion": "Licencia de Construcción",
        "licencia_funcionamiento": "Licencia de Funcionamiento",
        "certificado_habilitacion": "Certificado de Habilitación",
        "autorizacion_especial": "Autorización Especial",
        "otro": "Otro"
    }
}
```

#### Crear Expediente (Ciudadano)
```http
POST /api/expedientes
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: multipart/form-data

**Body (Form Data):**
- `solicitante_nombre`: string (requerido)
- `solicitante_dni`: string (requerido)
- `solicitante_email`: email (requerido)
- `solicitante_telefono`: string (requerido)
- `tipo_tramite`: string (requerido, uno de los tipos disponibles)
- `asunto`: string (requerido, max 500)
- `descripcion`: text (requerido)
- `gerencia_id`: integer (requerido, ID de gerencia)
- `subgerencia_id`: integer (opcional, ID de subgerencia)
- `documentos[]`: array de archivos (requerido, min 1)
  - `documentos[0][nombre]`: string (nombre del documento)
  - `documentos[0][tipo_documento]`: string (tipo de documento)
  - `documentos[0][archivo]`: file (archivo, max 10MB)

**Response:**
```json
{
    "success": true,
    "message": "Expediente registrado exitosamente",
    "data": {
        "numero": "EXP-2025-000001",
        "id": 1,
        "estado": "pendiente"
    }
}
```

#### Ver Expediente
```http
GET /api/expedientes/{id}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "numero": "EXP-2025-000001",
        "solicitante_nombre": "Juan Pérez",
        "solicitante_dni": "12345678",
        "solicitante_email": "juan@example.com",
        "solicitante_telefono": "999888777",
        "tipo_tramite": "licencia_construccion",
        "asunto": "Solicitud de licencia de construcción",
        "descripcion": "Descripción detallada del trámite...",
        "estado": "pendiente",
        "fecha_registro": "2025-01-01T00:00:00.000000Z",
        "gerencia": {
            "id": 1,
            "nombre": "Gerencia de Desarrollo Urbano",
            "codigo": "GDU"
        },
        "subgerencia": {
            "id": 1,
            "nombre": "Subgerencia de Obras Públicas",
            "codigo": "SOP"
        },
        "documentos": [
            {
                "id": 1,
                "nombre": "DNI del solicitante",
                "tipo_documento": "dni",
                "extension": "pdf",
                "tamaño": 1024000,
                "requerido": true,
                "aprobado": false
            }
        ],
        "historial": [
            {
                "id": 1,
                "accion": "crear",
                "estado_anterior": null,
                "estado_nuevo": "pendiente",
                "descripcion": "Expediente creado por ciudadano",
                "usuario": {
                    "id": 1,
                    "name": "Test User"
                },
                "created_at": "2025-01-01T00:00:00.000000Z"
            }
        ]
    }
}
```

#### Derivar Expediente (Mesa de Partes)
```http
PATCH /api/expedientes/{id}/derivar
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Body:**
```json
{
    "gerencia_id": 1,
    "subgerencia_id": 1,
    "observaciones": "Derivado para revisión técnica"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Expediente derivado exitosamente",
    "data": {
        "id": 1,
        "estado": "en_revision",
        "fecha_derivacion": "2025-01-01T00:00:00.000000Z"
    }
}
```

#### Revisión Técnica (Gerencia/Subgerencia)
```http
PATCH /api/expedientes/{id}/revision-tecnica
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Body:**
```json
{
    "requiere_informe_legal": true,
    "observaciones": "Requiere revisión legal por complejidad"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Revisión técnica completada exitosamente",
    "data": {
        "id": 1,
        "estado": "revision_legal",
        "fecha_revision_tecnica": "2025-01-01T00:00:00.000000Z"
    }
}
```

#### Revisión Legal (Secretaría General)
```http
PATCH /api/expedientes/{id}/revision-legal
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Body:**
```json
{
    "es_acto_administrativo_mayor": true,
    "observaciones": "Requiere firma del alcalde"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Revisión legal completada exitosamente",
    "data": {
        "id": 1,
        "estado": "resolucion_emitida",
        "fecha_revision_legal": "2025-01-01T00:00:00.000000Z"
    }
}
```

#### Emitir Resolución
```http
PATCH /api/expedientes/{id}/emitir-resolucion
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: multipart/form-data

**Body (Form Data):**
- `numero_resolucion`: string (requerido)
- `archivo_resolucion`: file (requerido, PDF, max 10MB)
- `observaciones`: string (opcional)

**Response:**
```json
{
    "success": true,
    "message": "Resolución emitida exitosamente",
    "data": {
        "id": 1,
        "estado": "resolucion_emitida",
        "numero_resolucion": "RES-2025-001"
    }
}
```

#### Firmar Resolución (Alcalde)
```http
PATCH /api/expedientes/{id}/firma-resolucion
```

**Headers:**
- Authorization: Bearer {token}

**Response:**
```json
{
    "success": true,
    "message": "Resolución firmada exitosamente",
    "data": {
        "id": 1,
        "estado": "firmado",
        "fecha_firma": "2025-01-01T00:00:00.000000Z"
    }
}
```

#### Notificar Ciudadano
```http
PATCH /api/expedientes/{id}/notificar
```

**Headers:**
- Authorization: Bearer {token}

**Response:**
```json
{
    "success": true,
    "message": "Ciudadano notificado exitosamente",
    "data": {
        "id": 1,
        "estado": "notificado",
        "notificado_ciudadano": true,
        "fecha_notificacion": "2025-01-01T00:00:00.000000Z"
    }
}
```

#### Rechazar Expediente
```http
PATCH /api/expedientes/{id}/rechazar
```

**Headers:**
- Authorization: Bearer {token}
- Content-Type: application/json

**Body:**
```json
{
    "motivo_rechazo": "Documentación incompleta o insuficiente"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Expediente rechazado exitosamente",
    "data": {
        "id": 1,
        "estado": "rechazado",
        "motivo_rechazo": "Documentación incompleta o insuficiente"
    }
}
```

### 2. Documentos

#### Descargar Documento
```http
GET /api/documentos/{id}/download
```

**Headers:**
- Authorization: Bearer {token}

**Response:** Archivo para descarga

#### Ver Documento
```http
GET /api/documentos/{id}/view
```

**Headers:**
- Authorization: Bearer {token}

**Response:** Archivo para visualización en navegador

### 3. Gerencias

#### Listar Gerencias
```http
GET /api/gerencias
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nombre": "Gerencia de Desarrollo Urbano",
            "codigo": "GDU",
            "descripcion": "Gestiona el desarrollo urbano y la planificación territorial",
            "subgerencias": [
                {
                    "id": 1,
                    "nombre": "Subgerencia de Obras Públicas",
                    "codigo": "SOP"
                }
            ]
        }
    ]
}
```

#### Obtener Subgerencias de una Gerencia
```http
GET /api/gerencias/{id}/subgerencias
```

### 4. Estadísticas

#### Obtener Estadísticas
```http
GET /api/estadisticas
```

**Response:**
```json
{
    "success": true,
    "data": {
        "total": 100,
        "pendientes": 25,
        "en_revision": 15,
        "revision_tecnica": 10,
        "revision_legal": 8,
        "resolucion_emitida": 20,
        "firmados": 12,
        "notificados": 8,
        "rechazados": 2
    }
}
```

## Estados del Expediente

El expediente pasa por los siguientes estados en orden:

1. **pendiente** - Recién creado por el ciudadano
2. **en_revision** - Derivado a gerencia para revisión
3. **revision_tecnica** - En revisión técnica
4. **revision_legal** - En revisión legal (si aplica)
5. **resolucion_emitida** - Resolución emitida
6. **firmado** - Resolución firmada por alcalde (si aplica)
7. **notificado** - Ciudadano notificado
8. **completado** - Proceso finalizado
9. **rechazado** - Expediente rechazado (estado final)

## Flujo de Trabajo

### 1. Ciudadano
- Registra solicitud con documentos
- Recibe número de expediente
- Puede consultar estado

### 2. Mesa de Partes
- Revisa requisitos mínimos
- Registra expediente en sistema
- Deriva a gerencia correspondiente
- Puede rechazar si no cumple requisitos

### 3. Gerencia/Subgerencia
- Recibe expediente derivado
- Realiza revisión técnica
- Determina si requiere informe legal
- Emite resolución (si no requiere legal)

### 4. Secretaría General
- Revisa expedientes que requieren informe legal
- Determina si es acto administrativo mayor
- Emite resolución

### 5. Alcalde
- Firma resoluciones de actos administrativos mayores

### 6. Sistema
- Actualiza estado del expediente
- Genera resolución PDF
- Notifica al ciudadano

## Códigos de Error

- `400` - Bad Request (datos inválidos)
- `401` - Unauthorized (token inválido o expirado)
- `403` - Forbidden (sin permisos)
- `404` - Not Found (recurso no encontrado)
- `422` - Validation Error (validación fallida)
- `500` - Internal Server Error (error del servidor)

## Ejemplos de Uso

### Crear Expediente Completo

```bash
curl -X POST http://localhost:8000/api/expedientes \
  -H "Authorization: Bearer {token}" \
  -F "solicitante_nombre=Juan Pérez" \
  -F "solicitante_dni=12345678" \
  -F "solicitante_email=juan@example.com" \
  -F "solicitante_telefono=999888777" \
  -F "tipo_tramite=licencia_construccion" \
  -F "asunto=Solicitud de licencia de construcción" \
  -F "descripcion=Construcción de vivienda unifamiliar" \
  -F "gerencia_id=1" \
  -F "subgerencia_id=1" \
  -F "documentos[0][nombre]=DNI del solicitante" \
  -F "documentos[0][tipo_documento]=dni" \
  -F "documentos[0][archivo]=@/path/to/dni.pdf"
```

### Derivar Expediente

```bash
curl -X PATCH http://localhost:8000/api/expedientes/1/derivar \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "gerencia_id": 1,
    "subgerencia_id": 1,
    "observaciones": "Derivado para revisión técnica"
  }'
```

## Notas Importantes

1. **Permisos**: Cada endpoint verifica los permisos del usuario según su rol
2. **Validación**: Todos los datos de entrada son validados antes de procesarse
3. **Historial**: Todas las acciones se registran en el historial del expediente
4. **Archivos**: Los documentos se almacenan de forma segura en disco privado
5. **Transacciones**: Las operaciones críticas usan transacciones de base de datos
6. **Auditoría**: Se registra IP y User-Agent de todas las acciones
