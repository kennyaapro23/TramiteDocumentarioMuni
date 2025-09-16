export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  total: number;
  per_page: number;
  last_page: number;
  from: number;
  to: number;
}

export interface ApiError {
  message: string;
  status: number;
  error?: string;
  errors?: Record<string, string[]>;
}