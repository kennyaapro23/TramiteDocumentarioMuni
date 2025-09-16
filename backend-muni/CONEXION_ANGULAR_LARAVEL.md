# Conexión Angular - Laravel

## Resumen de la Integración

Este documento explica cómo está configurada la conexión entre el frontend de Angular y el backend de Laravel para el Sistema de Expedientes Municipales.

## Arquitectura de la Conexión

### 1. Backend (Laravel)
- **URL Base**: `http://127.0.0.1:8000/api`
- **Autenticación**: Laravel Sanctum (tokens)
- **CORS**: Configurado para permitir peticiones desde Angular
- **Rutas API**: Todas las rutas están bajo el prefijo `/api`

### 2. Frontend (Angular)
- **URL Base**: `http://localhost:4200`
- **Servicios**: Comunicación HTTP con Laravel
- **Interceptores**: Manejo automático de tokens de autenticación
- **Manejo de Errores**: Respuestas consistentes y mensajes de error

## Configuración de CORS en Laravel

El archivo `config/cors.php` está configurado para permitir peticiones desde Angular:

```php
'allowed_origins' => ['http://localhost:4200', 'http://127.0.0.1:4200'],
'supports_credentials' => true,
```

## Servicios de Angular

### 1. ApiService (`src/app/core/services/api.service.ts`)
Servicio base que maneja todas las comunicaciones HTTP:

```typescript
export class ApiService {
  private baseUrl = environment.apiUrl; // http://127.0.0.1:8000/api
  
  // Métodos disponibles:
  get<T>(endpoint: string, params?: any): Observable<ApiResponse<T>>
  post<T>(endpoint: string, data: any): Observable<ApiResponse<T>>
  put<T>(endpoint: string, data: any): Observable<ApiResponse<T>>
  delete<T>(endpoint: string): Observable<ApiResponse<T>>
  postWithFile<T>(endpoint: string, formData: FormData): Observable<ApiResponse<T>>
}
```

### 2. AuthService (`src/app/core/services/auth.service.ts`)
Maneja la autenticación y autorización:

```typescript
export class AuthService {
  // Métodos principales:
  login(credentials: LoginCredentials): Observable<AuthResponse>
  logout(): void
  isAuthenticated(): boolean
  hasPermission(permission: string): boolean
  hasRole(role: string): boolean
}
```

### 3. ExpedienteService (`src/app/core/services/expediente.service.ts`)
Maneja operaciones CRUD de expedientes:

```typescript
export class ExpedienteService {
  // Métodos principales:
  getExpedientes(filters?: ExpedienteFilters): Observable<PaginatedResponse>
  createExpediente(data: CreateExpedienteRequest): Observable<Expediente>
  updateExpediente(id: number, data: UpdateExpedienteRequest): Observable<Expediente>
  derivarExpediente(id: number, gerenciaId: number): Observable<Expediente>
}
```

### 4. GerenciaService (`src/app/core/services/gerencia.service.ts`)
Maneja operaciones CRUD de gerencias:

```typescript
export class GerenciaService {
  // Métodos principales:
  getGerencias(filters?: GerenciaFilters): Observable<PaginatedResponse>
  createGerencia(data: CreateGerenciaRequest): Observable<Gerencia>
  updateGerencia(id: number, data: UpdateGerenciaRequest): Observable<Gerencia>
  cambiarEstado(id: number, estado: string): Observable<Gerencia>
}
```

### 5. UsuarioService (`src/app/core/services/usuario.service.ts`)
Maneja operaciones CRUD de usuarios:

```typescript
export class UsuarioService {
  // Métodos principales:
  getUsuarios(filters?: UsuarioFilters): Observable<PaginatedResponse>
  createUsuario(data: CreateUsuarioRequest): Observable<Usuario>
  updateUsuario(id: number, data: UpdateUsuarioRequest): Observable<Usuario>
  asignarRol(userId: number, role: string): Observable<any>
}
```

## Interceptor de Autenticación

El `AuthInterceptor` (`src/app/core/interceptors/auth.interceptor.ts`) agrega automáticamente el token de autenticación a todas las peticiones:

```typescript
export class AuthInterceptor implements HttpInterceptor {
  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    const token = this.authService.getToken();
    
    if (token) {
      request = request.clone({
        setHeaders: {
          Authorization: `Bearer ${token}`
        }
      });
    }
    
    return next.handle(request);
  }
}
```

## Configuración de Environment

### Development (`src/environments/environment.ts`)
```typescript
export const environment = {
  production: false,
  apiUrl: 'http://127.0.0.1:8000/api',
  appName: 'Sistema de Expedientes Municipales',
  version: '1.0.0'
};
```

### Production (`src/environments/environment.prod.ts`)
```typescript
export const environment = {
  production: true,
  apiUrl: 'https://tu-dominio.com/api', // Cambiar por tu URL de producción
  appName: 'Sistema de Expedientes Municipales',
  version: '1.0.0'
};
```

## Rutas API de Laravel

### Autenticación
```
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/user
POST   /api/auth/register
POST   /api/auth/change-password
GET    /api/auth/check-email
POST   /api/auth/refresh
```

### Expedientes
```
GET    /api/expedientes
POST   /api/expedientes
GET    /api/expedientes/{id}
PUT    /api/expedientes/{id}
DELETE /api/expedientes/{id}
POST   /api/expedientes/{id}/derivar
POST   /api/expedientes/{id}/aprobar
POST   /api/expedientes/{id}/rechazar
POST   /api/expedientes/{id}/documentos
```

### Gerencias
```
GET    /api/gerencias
POST   /api/gerencias
GET    /api/gerencias/{id}
PUT    /api/gerencias/{id}
DELETE /api/gerencias/{id}
POST   /api/gerencias/{id}/estado
GET    /api/gerencias/{id}/usuarios
POST   /api/gerencias/{id}/usuarios
```

### Usuarios
```
GET    /api/usuarios
POST   /api/usuarios
GET    /api/usuarios/{id}
PUT    /api/usuarios/{id}
DELETE /api/usuarios/{id}
POST   /api/usuarios/{id}/estado
POST   /api/usuarios/{id}/roles
POST   /api/usuarios/{id}/permissions
```

## Flujo de Autenticación

### 1. Login
```typescript
// En Angular
this.authService.login({ email: 'admin@municipalidad.com', password: 'admin123' })
  .subscribe({
    next: (response) => {
      // Token almacenado automáticamente
      // Usuario redirigido al dashboard
    },
    error: (error) => {
      // Manejo de errores
    }
  });
```

### 2. Peticiones Autenticadas
```typescript
// El interceptor agrega automáticamente el token
this.expedienteService.getExpedientes()
  .subscribe({
    next: (expedientes) => {
      // Datos recibidos
    }
  });
```

### 3. Logout
```typescript
// En Angular
this.authService.logout();
// Token eliminado automáticamente
// Usuario redirigido al login
```

## Manejo de Errores

### Respuestas de Error Estándar
```typescript
export interface ApiResponse<T> {
  data: T;
  message?: string;
  success: boolean;
  errors?: any;
}
```

### Códigos de Estado HTTP
- **200**: Éxito
- **201**: Creado
- **400**: Error de validación
- **401**: No autorizado
- **403**: Acceso denegado
- **404**: No encontrado
- **422**: Error de validación de datos
- **500**: Error interno del servidor

### Ejemplo de Manejo de Errores
```typescript
this.apiService.get('/expedientes')
  .subscribe({
    next: (response) => {
      if (response.success) {
        // Procesar datos
      }
    },
    error: (error) => {
      // El ApiService ya maneja los errores comunes
      console.error('Error:', error.message);
    }
  });
```

## Modo Desarrollo vs Producción

### Desarrollo
- Usa `http://127.0.0.1:8000/api` como URL base
- Incluye fallback a datos mock si la API no está disponible
- Logs detallados de errores

### Producción
- Usa URL de producción configurada
- Sin fallbacks a datos mock
- Logs mínimos de errores

## Configuración de Laravel Sanctum

Para que la autenticación funcione correctamente, asegúrate de que Laravel Sanctum esté configurado:

### 1. Instalar Sanctum
```bash
composer require laravel/sanctum
```

### 2. Publicar configuración
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Ejecutar migraciones
```bash
php artisan migrate
```

### 4. Configurar middleware en `app/Http/Kernel.php`
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## Pruebas de Conexión

### 1. Verificar que Laravel esté ejecutándose
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### 2. Verificar que Angular esté ejecutándose
```bash
cd frontend-muni
ng serve
```

### 3. Probar endpoint de autenticación
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@municipalidad.com","password":"admin123"}'
```

### 4. Verificar CORS
- Abrir DevTools en el navegador
- Ir a la pestaña Network
- Verificar que las peticiones no tengan errores de CORS

## Solución de Problemas Comunes

### 1. Error de CORS
- Verificar configuración en `config/cors.php`
- Asegurar que las URLs de origen estén correctas
- Verificar que el middleware CORS esté habilitado

### 2. Error de Autenticación
- Verificar que Sanctum esté configurado correctamente
- Verificar que el token se esté enviando en el header Authorization
- Verificar que el usuario exista y esté activo

### 3. Error de Rutas
- Verificar que las rutas estén definidas en `routes/web.php`
- Verificar que los middlewares estén configurados correctamente
- Verificar que los controladores existan y estén importados

### 4. Error de Base de Datos
- Verificar que las migraciones se hayan ejecutado
- Verificar que los seeders se hayan ejecutado
- Verificar la conexión a la base de datos

## Próximos Pasos

1. **Implementar Refresh Token**: Para mayor seguridad
2. **Implementar Rate Limiting**: Para proteger la API
3. **Implementar Logging**: Para auditoría
4. **Implementar Cache**: Para mejorar el rendimiento
5. **Implementar Tests**: Para asegurar la calidad del código

## Recursos Adicionales

- [Documentación de Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Documentación de Angular HTTP](https://angular.io/guide/http)
- [Documentación de CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)
- [Guía de Seguridad de APIs](https://owasp.org/www-project-api-security/)


