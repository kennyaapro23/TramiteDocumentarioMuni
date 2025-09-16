import { User, Gerencia } from './user.model';

export type ExpedienteEstado = 
  | 'pendiente'
  | 'en_revision'
  | 'revision_tecnica'
  | 'revision_legal'
  | 'resolucion_emitida'
  | 'firmado'
  | 'notificado'
  | 'rechazado'
  | 'completado';

export type TipoTramite = 
  | 'licencia_construccion'
  | 'licencia_funcionamiento'
  | 'certificado_habilitacion'
  | 'autorizacion_especial'
  | 'otro';

export interface DocumentoExpediente {
  id: number;
  nombre: string;
  tipo_documento: string;
  extension: string;
  tamaño: number;
  requerido: boolean;
  aprobado: boolean;
  ruta_archivo?: string;
  created_at: string;
  updated_at: string;
}

export interface HistorialExpediente {
  id: number;
  accion: string;
  estado_anterior?: ExpedienteEstado;
  estado_nuevo: ExpedienteEstado;
  descripcion: string;
  observaciones?: string;
  usuario_id: number;
  usuario: User;
  created_at: string;
  ip_address?: string;
  user_agent?: string;
}

export interface Expediente {
  id: number;
  numero: string;
  solicitante_nombre: string;
  solicitante_dni: string;
  solicitante_email: string;
  solicitante_telefono: string;
  tipo_tramite: TipoTramite;
  asunto: string;
  descripcion: string;
  estado: ExpedienteEstado;
  fecha_registro: string;
  fecha_derivacion?: string;
  fecha_revision_tecnica?: string;
  fecha_revision_legal?: string;
  fecha_resolucion?: string;
  fecha_firma?: string;
  fecha_notificacion?: string;
  gerencia_id: number;
  subgerencia_id?: number;
  usuario_registro_id: number;
  numero_resolucion?: string;
  requiere_informe_legal: boolean;
  es_acto_administrativo_mayor: boolean;
  notificado_ciudadano: boolean;
  motivo_rechazo?: string;
  observaciones?: string;
  created_at: string;
  updated_at: string;
  
  // Relaciones
  gerencia: Gerencia;
  subgerencia?: Gerencia;
  usuario_registro: User;
  documentos: DocumentoExpediente[];
  historial: HistorialExpediente[];
}

export interface CreateExpedienteRequest {
  solicitante_nombre: string;
  solicitante_dni: string;
  solicitante_email: string;
  solicitante_telefono: string;
  tipo_tramite: TipoTramite;
  asunto: string;
  descripcion: string;
  gerencia_id: number;
  subgerencia_id?: number;
  documentos: Array<{
    nombre: string;
    tipo_documento: string;
    archivo: File;
  }>;
}

export interface DerivarExpedienteRequest {
  gerencia_id: number;
  subgerencia_id?: number;
  observaciones?: string;
}

export interface RevisionTecnicaRequest {
  requiere_informe_legal: boolean;
  observaciones?: string;
}

export interface RevisionLegalRequest {
  es_acto_administrativo_mayor: boolean;
  observaciones?: string;
}

export interface EmitirResolucionRequest {
  numero_resolucion: string;
  archivo_resolucion: File;
  observaciones?: string;
}

export interface RechazarExpedienteRequest {
  motivo_rechazo: string;
}