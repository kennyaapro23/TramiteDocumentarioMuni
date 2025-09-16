# Arquitectura de Gerencias - Sistema de Expedientes Municipales

## 🔹 **Diferencia Importante: Roles vs Gerencias**

### **Roles y Permisos (Spatie Laravel Permission)**
- **Propósito**: Definen qué puede hacer cada usuario
- **Ejemplo**: `mesa_partes` puede derivar, `gerente` puede emitir resolución
- **Característica**: Son **funcionales** y se definen en el código

### **Gerencias/Subgerencias (Entidad del Sistema)**
- **Propósito**: Son áreas organizacionales a las que se asignan usuarios y expedientes
- **Ejemplo**: "Gerencia de Desarrollo Urbano", "Subgerencia de Obras Públicas"
- **Característica**: Son **estructurales** y se crean dinámicamente desde la aplicación

## 🏗️ **Modelo Recomendado en Laravel**

### **1. Entidades Principales**

#### **Gerencia (Modelo Unificado)**
```php
class Gerencia extends Model
{
    protected $fillable = [
        'nombre',           // "Desarrollo Urbano"
        'codigo',           // "GDU"
        'tipo',             // 'gerencia' o 'subgerencia'
        'gerencia_padre_id', // null para gerencias, ID para subgerencias
        'flujos_permitidos', // JSON con tipos de trámite permitidos
        'activo',           // true/false
        'orden'             // Orden de visualización
    ];
}
```

#### **User (Con Relación a Gerencia)**
```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'gerencia_id'
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }
}
```

#### **Expediente (Con Relación a Gerencia)**
```php
class Expediente extends Model
{
    protected $fillable = [
        'gerencia_id',        // Gerencia principal
        'gerencia_padre_id',  // Subgerencia (opcional)
        // ... otros campos
    ];

    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class);
    }

    public function gerenciaPadre()
    {
        return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
    }
}
```

### **2. Relaciones Eloquent**

```php
// User.php
public function gerencia() {
    return $this->belongsTo(Gerencia::class);
}

// Gerencia.php
public function users() {
    return $this->hasMany(User::class);
}

public function expedientes() {
    return $this->hasMany(Expediente::class);
}

public function subgerencias() {
    return $this->hasMany(Gerencia::class, 'gerencia_padre_id');
}

public function gerenciaPadre() {
    return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
}

// Expediente.php
public function gerencia() {
    return $this->belongsTo(Gerencia::class);
}

public function gerenciaPadre() {
    return $this->belongsTo(Gerencia::class, 'gerencia_padre_id');
}
```

## 🎯 **Flujos de Trámite con Restricciones**

### **Ejemplo: Licencia de Construcción**

```php
// En el modelo Expediente
public function getFlujoPermitidoAttribute(): array
{
    $flujos = [
        'licencia_construccion' => [
            'gerencias' => ['Desarrollo Urbano'],
            'subgerencias' => ['Obras Públicas', 'Catastro'],
            'flujo' => ['pendiente', 'en_revision', 'revision_tecnica', 'resolucion_emitida']
        ],
        'licencia_funcionamiento' => [
            'gerencias' => ['Desarrollo Económico'],
            'subgerencias' => ['Comercio'],
            'flujo' => ['pendiente', 'en_revision', 'revision_tecnica', 'resolucion_emitida']
        ],
    ];

    return $flujos[$this->tipo_tramite] ?? [];
}
```

### **Validación al Derivar**

```php
public function puedeSerDerivadoAGerencia(Gerencia $gerencia): bool
{
    // Verificar que la gerencia puede procesar este tipo de trámite
    if (!$gerencia->puedeProcesarTramite($this->tipo_tramite)) {
        return false;
    }

    // Verificar que el estado actual permite derivación
    return in_array($this->estado, [
        self::ESTADO_PENDIENTE,
        self::ESTADO_EN_REVISION
    ]);
}
```

## 🔐 **Sistema de Seguridad Implementado**

### **1. Restricción por Gerencia**
- Cada expediente solo puede ser derivado a gerencias que pueden procesar su tipo de trámite
- Los usuarios solo ven expedientes de su gerencia asignada

### **2. Validación de Flujos**
- No se puede saltar pasos del flujo
- No se puede enviar a gerencias equivocadas
- Cada gerencia tiene tipos de trámite específicos permitidos

### **3. Auditoría Completa**
- Todas las derivaciones se registran en el historial
- Se registra IP, User-Agent y usuario que realizó la acción

## 📱 **Administración Web Dinámica**

### **Módulo: Gestión de Gerencias**

#### **Endpoints Disponibles**
```http
# CRUD de Gerencias
GET    /admin/gerencias                    # Listar gerencias
POST   /admin/gerencias                    # Crear gerencia
GET    /admin/gerencias/{id}               # Ver gerencia
PUT    /admin/gerencias/{id}               # Actualizar gerencia
DELETE /admin/gerencias/{id}               # Eliminar gerencia

# Gestión de Usuarios
PATCH  /admin/gerencias/{id}/asignar-usuario    # Asignar usuario
PATCH  /admin/gerencias/{id}/remover-usuario    # Remover usuario

# Consultas Especializadas
GET    /admin/gerencias-por-tramite/{tipo}      # Gerencias por tipo de trámite
GET    /admin/estadisticas-gerencias            # Estadísticas de gerencias
```

#### **Funcionalidades del Panel**
- ✅ Crear nueva gerencia
- ✅ Editar nombre, estado y flujos permitidos
- ✅ Asociar usuarios a gerencia
- ✅ Definir jerarquía (Gerencia > Subgerencia)
- ✅ Configurar tipos de trámite permitidos
- ✅ Activar/desactivar gerencias

## 🚀 **Ejemplo de Flujo Completo**

### **1. Admin Crea Gerencia**
```json
POST /admin/gerencias
{
    "nombre": "Gerencia de Turismo",
    "codigo": "GTU",
    "tipo": "gerencia",
    "flujos_permitidos": ["autorizacion_especial"],
    "activo": true
}
```

### **2. Admin Asigna Usuario**
```json
PATCH /admin/gerencias/6/asignar-usuario
{
    "user_id": 5
}
```

### **3. Usuario Crea Expediente**
```json
POST /api/expedientes
{
    "tipo_tramite": "autorizacion_especial",
    "gerencia_id": 6,  // Gerencia de Turismo
    "solicitante_nombre": "Hotel Plaza"
}
```

### **4. Sistema Valida Flujo**
- ✅ Tipo de trámite permitido en la gerencia
- ✅ Usuario tiene permisos para crear expedientes
- ✅ Expediente se crea con estado "pendiente"

### **5. Mesa de Partes Deriva**
```json
PATCH /api/expedientes/1/derivar
{
    "gerencia_id": 6,
    "observaciones": "Derivado para revisión"
}
```

## 📊 **Beneficios de esta Arquitectura**

### **1. Escalabilidad**
- ✅ Crear nuevas gerencias sin modificar código
- ✅ Agregar tipos de trámite dinámicamente
- ✅ Modificar flujos desde el panel administrativo

### **2. Flexibilidad**
- ✅ Gerencias se administran desde la web
- ✅ Usuarios se asignan dinámicamente
- ✅ Flujos se configuran según necesidades

### **3. Control y Seguridad**
- ✅ Cada usuario solo ve expedientes de su gerencia
- ✅ Flujos restringidos por tipo de trámite
- ✅ Auditoría completa de todas las acciones

### **4. Mantenibilidad**
- ✅ Código limpio y separado
- ✅ Lógica de negocio en modelos
- ✅ Validaciones centralizadas

## 🔧 **Configuración del Sistema**

### **1. Base de Datos**
```sql
-- Tabla gerencias
CREATE TABLE gerencias (
    id BIGINT PRIMARY KEY,
    nombre VARCHAR(255),
    codigo VARCHAR(10) UNIQUE,
    tipo ENUM('gerencia', 'subgerencia'),
    gerencia_padre_id BIGINT NULL,
    flujos_permitidos JSON NULL,
    activo BOOLEAN DEFAULT true,
    orden INT DEFAULT 0
);

-- Tabla users con gerencia_id
ALTER TABLE users ADD COLUMN gerencia_id BIGINT NULL;
ALTER TABLE users ADD FOREIGN KEY (gerencia_id) REFERENCES gerencias(id);

-- Tabla expedientes con gerencia_padre_id
ALTER TABLE expedientes ADD COLUMN gerencia_padre_id BIGINT NULL;
ALTER TABLE expedientes ADD FOREIGN KEY (gerencia_padre_id) REFERENCES gerencias(id);
```

### **2. Permisos Requeridos**
```php
// En RolePermissionSeeder
'gestionar_gerencias' => 'Gestionar Gerencias y Subgerencias'
```

### **3. Middleware**
```php
// En routes/web.php
Route::middleware(['auth:sanctum', 'permission:gestionar_gerencias'])
     ->prefix('admin')
     ->group(function () {
    // Rutas de administración
});
```

## 📈 **Próximos Pasos Recomendados**

### **1. Frontend**
- Panel de administración para gerencias
- Formularios de creación/edición
- Gestión de usuarios por gerencia

### **2. Validaciones Avanzadas**
- Reglas de negocio más complejas
- Flujos condicionales
- Validaciones de documentos por tipo de trámite

### **3. Reportes**
- Estadísticas por gerencia
- Flujos de trámite
- Tiempos de procesamiento

### **4. Notificaciones**
- Alertas automáticas por gerencia
- Recordatorios de trámites pendientes
- Notificaciones de cambios de estado

---

**Esta arquitectura separa claramente la estructura organizacional (Gerencias) de la funcionalidad (Roles/Permisos), permitiendo un sistema flexible y escalable que se administra completamente desde la web.**
