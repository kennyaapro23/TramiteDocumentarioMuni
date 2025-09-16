export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  success: boolean;
  data: {
    token: string;
    user: User;
  };
  message?: string;
}

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  roles: UserRole[];
  permissions: UserPermission[];
  created_at: string;
  updated_at: string;
}

export interface UserRole {
  id: number;
  name: string;
  guard_name: string;
  pivot?: {
    model_id: number;
    role_id: number;
    model_type: string;
  };
}

export interface UserPermission {
  id: number;
  name: string;
  guard_name: string;
  pivot?: {
    model_id: number;
    permission_id: number;
    model_type: string;
  };
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface RegisterResponse {
  success: boolean;
  data: {
    token: string;
    user: User;
  };
  message?: string;
}

export interface LogoutResponse {
  success: boolean;
  message: string;
}

export interface AuthResponse {
  success: boolean;
  data?: any;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface UserProfile {
  id: number;
  name: string;
  email: string;
  roles: string[];
  permissions: string[];
  avatar?: string;
  last_login?: string;
}