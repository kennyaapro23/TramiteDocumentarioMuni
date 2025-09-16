export interface LoginRequest {
  email: string;
  password: string;
}

export interface LoginResponse {
  token: string;
  user: import('./user.model').User;
}

export interface AuthTokens {
  access_token: string;
  token_type: string;
  expires_in?: number;
}