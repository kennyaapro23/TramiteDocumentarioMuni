// This file can be replaced during build by using the `fileReplacements` array.
// `ng build` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  apiConfig: {
    baseUrl: 'http://localhost:8000',
    apiPrefix: '/api',
    timeout: 30000,
    authEndpoints: {
      login: '/login',
      logout: '/logout',
      user: '/user',
      register: '/register'
    },
    expedientesEndpoints: {
      base: '/expedientes',
      byId: (id: number) => `/expedientes/${id}`,
      changeStatus: (id: number) => `/expedientes/${id}/estado`,
      assign: (id: number) => `/expedientes/${id}/asignar`,
      download: (id: number) => `/expedientes/${id}/documento`
    },
    gerenciasEndpoints: {
      base: '/gerencias',
      byId: (id: number) => `/gerencias/${id}`,
      usuarios: (id: number) => `/gerencias/${id}/usuarios`
    },
    rolesEndpoints: {
      base: '/roles',
      permissions: '/permissions'
    }
  },
  storage: {
    tokenKey: 'auth_token',
    userKey: 'user_data',
    permissionsKey: 'user_permissions'
  },
  pagination: {
    defaultPageSize: 10,
    pageSizeOptions: [5, 10, 25, 50, 100]
  },
  upload: {
    maxFileSize: 10 * 1024 * 1024, // 10MB
    allowedTypes: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png']
  }
};