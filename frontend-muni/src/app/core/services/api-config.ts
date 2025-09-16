import { environment } from '../../../environments/environment';

export const ApiConfig = {
  baseUrl: `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}`,
  auth: {
    tokenKey: environment.storage.tokenKey,
    tokenPrefix: 'Bearer',
  },
  endpoints: {
    auth: {
      login: environment.apiConfig.authEndpoints.login,
      logout: environment.apiConfig.authEndpoints.logout,
      profile: environment.apiConfig.authEndpoints.user,
      register: environment.apiConfig.authEndpoints.register,
    },
    expedientes: {
      list: environment.apiConfig.expedientesEndpoints.base,
      create: environment.apiConfig.expedientesEndpoints.base,
      detail: environment.apiConfig.expedientesEndpoints.byId,
      changeStatus: environment.apiConfig.expedientesEndpoints.changeStatus,
      assign: environment.apiConfig.expedientesEndpoints.assign,
      download: environment.apiConfig.expedientesEndpoints.download,
      // Endpoints adicionales para el flujo completo
      derivar: (id: number) => `/expedientes/${id}/derivar`,
      revisionTecnica: (id: number) => `/expedientes/${id}/revision-tecnica`,
      revisionLegal: (id: number) => `/expedientes/${id}/revision-legal`,
      emitirResolucion: (id: number) => `/expedientes/${id}/emitir-resolucion`,
      firmarResolucion: (id: number) => `/expedientes/${id}/firma-resolucion`,
      notificar: (id: number) => `/expedientes/${id}/notificar`,
      rechazar: (id: number) => `/expedientes/${id}/rechazar`,
    },
    gerencias: {
      list: environment.apiConfig.gerenciasEndpoints.base,
      detail: environment.apiConfig.gerenciasEndpoints.byId,
      usuarios: environment.apiConfig.gerenciasEndpoints.usuarios,
      subgerencias: (id: number) => `/gerencias/${id}/subgerencias`,
    },
    admin: {
      gerencias: {
        list: '/admin/gerencias',
        create: '/admin/gerencias',
        update: (id: number) => `/admin/gerencias/${id}`,
        delete: (id: number) => `/admin/gerencias/${id}`,
        asignarUsuario: (id: number) => `/admin/gerencias/${id}/asignar-usuario`,
        removerUsuario: (id: number) => `/admin/gerencias/${id}/remover-usuario`,
      },
    },
    roles: {
      list: environment.apiConfig.rolesEndpoints.base,
      permissions: environment.apiConfig.rolesEndpoints.permissions,
    },
    estadisticas: '/estadisticas',
    documentos: {
      download: (id: number) => `/documentos/${id}/download`,
      view: (id: number) => `/documentos/${id}/view`,
    },
  },
  timeout: environment.apiConfig.timeout,
  retryAttempts: 3,
  upload: environment.upload,
  pagination: environment.pagination,
};