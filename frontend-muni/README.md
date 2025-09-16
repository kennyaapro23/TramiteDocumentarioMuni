# Frontend Municipal - Sistema de Trámite Documentario

Frontend desarrollado en Angular 20.2.0 para el sistema de trámite documentario municipal.

## 🚀 Características

- **Angular 20.2.0** con standalone components
- **Arquitectura modular** (core/shared/features)
- **Sistema de autenticación** con JWT
- **Gestión de permisos** basada en Spatie Laravel Permission
- **Interceptors** para manejo de tokens y errores
- **Guards** para protección de rutas
- **Manejo centralizado de errores**
- **Configuración por environments**

## 📁 Estructura del Proyecto

```
src/
├── app/
│   ├── core/                    # Funcionalidades principales
│   │   ├── guards/              # Guards de autenticación y permisos
│   │   ├── interceptors/        # Interceptors HTTP
│   │   ├── models/              # Interfaces y tipos TypeScript
│   │   └── services/            # Servicios principales
│   ├── shared/                  # Componentes compartidos
│   │   ├── components/          # Layout, Spinner, StatusBadge, etc.
│   │   └── directives/          # Directivas como hasPermission
│   ├── features/                # Módulos por características
│   │   ├── auth/                # Login y autenticación
│   │   ├── dashboard/           # Panel principal
│   │   ├── expedientes/         # Gestión de expedientes
│   │   └── admin/               # Administración
│   └── environments/            # Configuración por ambiente
└── assets/                      # Recursos estáticos
```

## ⚙️ Configuración

### 1. Variables de Entorno

Actualiza `src/environments/environment.ts` y `environment.prod.ts` con la URL de tu backend:

```typescript
export const environment = {
  production: false,
  apiConfig: {
    baseUrl: 'http://localhost:8000',  // URL de tu backend Laravel
    apiPrefix: '/api',
    // ... más configuraciones
  }
};
```

### 2. Backend API

Asegúrate de que el backend Laravel esté ejecutándose en la URL configurada en `environment.ts`.

## 🔧 Instalación y Ejecución

To start a local development server, run:

```bash
ng serve
```

Once the server is running, open your browser and navigate to `http://localhost:4200/`. The application will automatically reload whenever you modify any of the source files.

## Code scaffolding

Angular CLI includes powerful code scaffolding tools. To generate a new component, run:

```bash
ng generate component component-name
```

For a complete list of available schematics (such as `components`, `directives`, or `pipes`), run:

```bash
ng generate --help
```

## Building

To build the project run:

```bash
ng build
```

This will compile your project and store the build artifacts in the `dist/` directory. By default, the production build optimizes your application for performance and speed.

## Running unit tests

To execute unit tests with the [Karma](https://karma-runner.github.io) test runner, use the following command:

```bash
ng test
```

## Running end-to-end tests

For end-to-end (e2e) testing, run:

```bash
ng e2e
```

Angular CLI does not come with an end-to-end testing framework by default. You can choose one that suits your needs.

## Additional Resources

For more information on using the Angular CLI, including detailed command references, visit the [Angular CLI Overview and Command Reference](https://angular.dev/tools/cli) page.
