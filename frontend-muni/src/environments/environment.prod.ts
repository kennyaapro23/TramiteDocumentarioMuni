export const environment = {
  production: true,
  apiConfig: {
    baseUrl: 'https://your-production-domain.com',
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