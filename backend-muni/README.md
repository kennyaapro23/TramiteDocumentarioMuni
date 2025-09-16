# 🏛️ Sistema de Trámite Documentario Municipal

## Descripción del Proyecto

Sistema integral de gestión de expedientes municipales desarrollado en **Laravel 11** que permite a ciudadanos registrar solicitudes de trámites y a funcionarios municipales procesarlas según sus roles y permisos específicos.

## ✨ Características Principales

- **🔐 Autenticación y Autorización**: Sistema robusto con Sanctum y Spatie Permissions
- **📋 Gestión de Expedientes**: Flujo completo desde registro hasta resolución
- **🏢 Arquitectura de Gerencias**: Modelo unificado de gerencias y subgerencias
- **📄 Gestión Documental**: Carga y gestión de documentos por expediente
- **📊 Historial y Auditoría**: Trazabilidad completa de todas las acciones
- **🔄 Workflow Dinámico**: Flujos configurables según tipo de trámite
- **📱 API RESTful**: Endpoints documentados para integración con frontend

## 🚀 Tecnologías Utilizadas

- **Backend**: Laravel 11
- **Base de Datos**: SQLite (configurable a MySQL/PostgreSQL)
- **Autenticación**: Laravel Sanctum
- **Permisos**: Spatie Laravel Permission
- **Documentación**: Markdown documentado
- **Testing**: PHPUnit

## 📦 Instalación

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
