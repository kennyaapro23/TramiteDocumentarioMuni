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
  completados?: number;
}

export interface EstadosExpediente {
  pendiente: string;
  en_revision: string;
  revision_tecnica: string;
  revision_legal: string;
  resolucion_emitida: string;
  firmado: string;
  notificado: string;
  rechazado: string;
  completado: string;
}

export interface TiposTramite {
  licencia_construccion: string;
  licencia_funcionamiento: string;
  certificado_habilitacion: string;
  autorizacion_especial: string;
  otro: string;
}