export interface Permission {
  id: number;
  name: string;
  guard_name: string;
}

export interface Role {
  id: number;
  name: string;
  guard_name: string;
  permissions?: Permission[];
}

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  created_at: string;
  updated_at: string;
  roles?: Role[];
  permissions?: Permission[];
  gerencia_id?: number;
  gerencia?: Gerencia;
}

export interface Gerencia {
  id: number;
  nombre: string;
  codigo: string;
  tipo: 'gerencia' | 'subgerencia';
  gerencia_padre_id?: number;
  flujos_permitidos?: string[];
  activo: boolean;
  orden: number;
  created_at: string;
  updated_at: string;
  subgerencias?: Gerencia[];
  gerencia_padre?: Gerencia;
}

// Alias para mayor claridad en el código
export type Subgerencia = Gerencia;