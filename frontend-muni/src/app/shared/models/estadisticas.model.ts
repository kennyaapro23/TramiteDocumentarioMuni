export interface Estadisticas {
  total: number;
  pendientes: number;
  en_revision: number;
  revision_tecnica: number;
  revision_legal: number;
  resolucion_emitida: number;
  firmados: number;
  notificados: number;
  rechazados: number;
}

export interface EstadisticasResponse {
  success: boolean;
  data: Estadisticas;
}

export interface EstadisticasGerencia {
  gerencia_id: number;
  gerencia_nombre: string;
  expedientes_total: number;
  expedientes_pendientes: number;
  expedientes_completados: number;
  tiempo_promedio_dias: number;
}

export interface EstadisticasUsuario {
  usuario_id: number;
  usuario_nombre: string;
  expedientes_procesados: number;
  acciones_realizadas: number;
  ultima_actividad: string;
}

export interface EstadisticasTiempo {
  periodo: string;
  expedientes_creados: number;
  expedientes_completados: number;
  tiempo_promedio_dias: number;
}

export interface DashboardStats {
  estadisticas_generales: Estadisticas;
  estadisticas_por_gerencia: EstadisticasGerencia[];
  estadisticas_usuario?: EstadisticasUsuario;
  estadisticas_tiempo: EstadisticasTiempo[];
  expedientes_recientes: any[]; // Se definirá según necesidad
}