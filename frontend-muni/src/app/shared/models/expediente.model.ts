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
  estado: EstadoExpediente;
  fecha_registro: string;
  fecha_derivacion?: string;
  fecha_revision_tecnica?: string;
  fecha_revision_legal?: string;
  fecha_firma?: string;
  fecha_notificacion?: string;
  numero_resolucion?: string;
  motivo_rechazo?: string;
  observaciones?: string;
  notificado_ciudadano: boolean;
  gerencia: Gerencia;
  subgerencia?: Subgerencia;
  documentos: Documento[];
  historial: HistorialAccion[];
  created_at: string;
  updated_at: string;
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
  documentos: CreateDocumentoRequest[];
}

export interface CreateDocumentoRequest {
  nombre: string;
  tipo_documento: string;
  archivo: File;
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

export interface Gerencia {
  id: number;
  nombre: string;
  codigo: string;
  descripcion?: string;
  subgerencias?: Subgerencia[];
}

export interface Subgerencia {
  id: number;
  nombre: string;
  codigo: string;
  gerencia_id: number;
}

export interface Documento {
  id: number;
  nombre: string;
  tipo_documento: string;
  extension: string;
  tamaño: number;
  requerido: boolean;
  aprobado: boolean;
  ruta?: string;
  expediente_id: number;
  created_at: string;
}

export interface HistorialAccion {
  id: number;
  accion: string;
  estado_anterior?: EstadoExpediente;
  estado_nuevo: EstadoExpediente;
  descripcion: string;
  observaciones?: string;
  usuario: Usuario;
  expediente_id: number;
  created_at: string;
}

export interface Usuario {
  id: number;
  name: string;
  email: string;
  roles?: Rol[];
  permissions?: Permission[];
}

export interface Rol {
  id: number;
  name: string;
  guard_name: string;
}

export interface Permission {
  id: number;
  name: string;
  guard_name: string;
}

export type EstadoExpediente = 
  | 'pendiente'
  | 'en_revision'
  | 'revision_tecnica'
  | 'revision_legal'
  | 'resolucion_emitida'
  | 'firmado'
  | 'notificado'
  | 'completado'
  | 'rechazado';

export type TipoTramite = 
  | 'licencia_construccion'
  | 'licencia_funcionamiento'
  | 'certificado_habilitacion'
  | 'autorizacion_especial'
  | 'otro';

export const ESTADOS_EXPEDIENTE: Record<EstadoExpediente, string> = {
  pendiente: 'Pendiente',
  en_revision: 'En Revisión',
  revision_tecnica: 'Revisión Técnica',
  revision_legal: 'Revisión Legal',
  resolucion_emitida: 'Resolución Emitida',
  firmado: 'Firmado',
  notificado: 'Notificado',
  completado: 'Completado',
  rechazado: 'Rechazado'
};

export const TIPOS_TRAMITE: Record<TipoTramite, string> = {
  licencia_construccion: 'Licencia de Construcción',
  licencia_funcionamiento: 'Licencia de Funcionamiento',
  certificado_habilitacion: 'Certificado de Habilitación',
  autorizacion_especial: 'Autorización Especial',
  otro: 'Otro'
};

export interface ExpedienteListResponse {
  success: boolean;
  data: {
    current_page: number;
    data: Expediente[];
    total: number;
    per_page: number;
  };
  estados: Record<EstadoExpediente, string>;
  tipos_tramite: Record<TipoTramite, string>;
}

export interface ExpedienteResponse {
  success: boolean;
  data: Expediente;
  message?: string;
}

export interface ExpedienteFilters {
  estado?: EstadoExpediente;
  gerencia_id?: number;
  search?: string;
  per_page?: number;
  page?: number;
}