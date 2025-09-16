export interface ApiResponse<T = any> {
  success: boolean;
  data?: T;
  message?: string;
  errors?: Record<string, string[]>;
}

export interface PaginatedResponse<T> {
  success: boolean;
  data: {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url?: string;
    path: string;
    per_page: number;
    prev_page_url?: string;
    to: number;
    total: number;
  };
}

export interface PaginationLink {
  url?: string;
  label: string;
  active: boolean;
}

export interface ErrorResponse {
  success: false;
  message: string;
  errors?: Record<string, string[]>;
  status_code?: number;
}

export interface ValidationError {
  field: string;
  messages: string[];
}

export interface FileUploadResponse {
  success: boolean;
  data: {
    path: string;
    url: string;
    size: number;
    mime_type: string;
  };
  message?: string;
}