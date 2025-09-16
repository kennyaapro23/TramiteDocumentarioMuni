# 🚀 Instrucciones para Probar el Sistema Laravel

## ✅ **Estado Actual del Sistema**

El sistema Laravel está **completamente funcional** con:
- ✅ Base de datos configurada y migrada
- ✅ Usuario admin creado con todos los permisos
- ✅ API de autenticación funcionando
- ✅ Rutas API configuradas
- ✅ CORS configurado para Angular
- ✅ Sanctum configurado para autenticación

## 🔑 **Credenciales de Acceso**

- **Email**: `admin@municipalidad.com`
- **Password**: `admin123`
- **Rol**: `admin`
- **Permisos**: Todos los permisos del sistema

## 🌐 **URLs del Sistema**

- **Backend Laravel**: `http://127.0.0.1:8000`
- **API Base**: `http://127.0.0.1:8000/api`
- **Test HTML**: `test_api.html` (archivo local)

## 🧪 **Cómo Probar el Sistema**

### **Opción 1: Usar el Archivo de Test HTML**

1. **Abrir el archivo** `test_api.html` en tu navegador
2. **Hacer clic en "Probar Login"** para autenticarte
3. **Hacer clic en "Obtener Usuario"** para ver tu información
4. **Hacer clic en "Obtener Expedientes"** para ver expedientes

### **Opción 2: Usar Postman o Similar**

#### **Login:**
```
POST http://127.0.0.1:8000/api/auth/login
Content-Type: application/json

{
    "email": "admin@municipalidad.com",
    "password": "admin123"
}
```

#### **Obtener Usuario (con token):**
```
GET http://127.0.0.1:8000/api/auth/user
Authorization: Bearer {TOKEN_OBTENIDO}
```

#### **Obtener Expedientes (con token):**
```
GET http://127.0.0.1:8000/api/expedientes
Authorization: Bearer {TOKEN_OBTENIDO}
```

### **Opción 3: Usar cURL (Linux/Mac)**

```bash
# Login
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@municipalidad.com","password":"admin123"}'

# Obtener usuario (reemplazar TOKEN con el token obtenido)
curl -X GET http://127.0.0.1:8000/api/auth/user \
  -H "Authorization: Bearer TOKEN"
```

## 📋 **Endpoints Disponibles para Probar**

### **Autenticación:**
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout
- `GET /api/auth/user` - Obtener usuario actual
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/change-password` - Cambiar contraseña

### **Expedientes:**
- `GET /api/expedientes` - Listar expedientes
- `POST /api/expedientes` - Crear expediente
- `GET /api/expedientes/{id}` - Ver expediente
- `PUT /api/expedientes/{id}` - Actualizar expediente
- `DELETE /api/expedientes/{id}` - Eliminar expediente

### **Gerencias:**
- `GET /api/gerencias` - Listar gerencias
- `POST /api/gerencias` - Crear gerencia
- `GET /api/gerencias/{id}` - Ver gerencia
- `PUT /api/gerencias/{id}` - Actualizar gerencia

### **Usuarios:**
- `GET /api/usuarios` - Listar usuarios
- `POST /api/usuarios` - Crear usuario
- `GET /api/usuarios/{id}` - Ver usuario
- `PUT /api/usuarios/{id}` - Actualizar usuario

## 🔍 **Verificar que Todo Funcione**

### **1. Verificar Base de Datos:**
```bash
php artisan tinker --execute="echo 'Usuarios: ' . App\Models\User::count() . PHP_EOL; echo 'Gerencias: ' . App\Models\Gerencia::count() . PHP_EOL;"
```

### **2. Verificar Rutas:**
```bash
php artisan route:list --path=api
```

### **3. Verificar Usuario Admin:**
```bash
php artisan tinker --execute="echo 'Admin: ' . App\Models\User::where('email', 'admin@municipalidad.com')->first()->name . PHP_EOL;"
```

## 🚨 **Solución de Problemas Comunes**

### **Error 419 (CSRF):**
- El sistema está configurado para API, no debería dar este error
- Verificar que estés usando el endpoint correcto

### **Error de CORS:**
- CORS está configurado para `localhost:4200` y `127.0.0.1:4200`
- Verificar que Angular esté corriendo en esos puertos

### **Error de Autenticación:**
- Verificar que las credenciales sean correctas
- Verificar que el usuario esté activo en la base de datos

### **Error de Base de Datos:**
- Ejecutar `php artisan migrate:fresh --seed` para recrear todo
- Verificar conexión a la base de datos en `.env`

## 🎯 **Próximos Pasos**

1. **Probar la API** usando el archivo `test_api.html`
2. **Verificar que Angular** se conecte correctamente
3. **Probar todas las funcionalidades** del sistema
4. **Implementar mejoras** según sea necesario

## 📞 **Soporte**

Si encuentras algún problema:
1. Verificar logs en `storage/logs/laravel.log`
2. Verificar que el servidor esté corriendo en puerto 8000
3. Verificar que la base de datos esté accesible
4. Verificar que todas las migraciones se hayan ejecutado

---

## 🎉 **¡El Sistema Está Listo para Usar!**

Puedes empezar a probar inmediatamente usando el archivo `test_api.html` o cualquier cliente HTTP como Postman.
