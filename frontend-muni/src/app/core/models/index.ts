// Core Models - Sistema de Trámite Documentario
export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  permissions: Permission[];
}

export interface Permission {
  id: number;
  name: string;
  guard_name: string;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  access_token: string;
  token_type: string;
  expires_in: number;
  user: User;
}

export interface ApiResponse<T = any> {
  success: boolean;
  data: T;
  message: string;
}

export interface PaginatedResponse<T = any> {
  data: T[];
  current_page: number;
  per_page: number;
  total: number;
  last_page: number;
}

export interface Tramite {
  id: number;
  numero_tramite: string;
  asunto: string;
  descripcion: string;
  estado: 'pendiente' | 'en_proceso' | 'completado' | 'rechazado';
  fecha_creacion: string;
  fecha_actualizacion: string;
  usuario_id: number;
  usuario?: User;
}

export interface Documento {
  id: number;
  nombre: string;
  tipo: string;
  ruta: string;
  size: number;
  tramite_id: number;
}