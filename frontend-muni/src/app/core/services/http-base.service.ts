import { Injectable } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { ApiConfig } from './api-config';
import { ApiResponse, ApiError, PaginatedResponse } from '../models';

@Injectable({
  providedIn: 'root'
})
export class HttpBaseService {

  constructor(private http: HttpClient) {}

  private getUrl(endpoint: string): string {
    return `${ApiConfig.baseUrl}${endpoint}`;
  }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem(ApiConfig.auth.tokenKey);
    let headers = new HttpHeaders({
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    });
    
    if (token) {
      headers = headers.set('Authorization', `${ApiConfig.auth.tokenPrefix} ${token}`);
    }
    
    return headers;
  }

  get<T>(endpoint: string, params?: Record<string, any>): Observable<T> {
    let httpParams = new HttpParams();
    if (params) {
      Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined) {
          httpParams = httpParams.append(key, params[key].toString());
        }
      });
    }

    return this.http.get<ApiResponse<T>>(this.getUrl(endpoint), {
      headers: this.getHeaders(),
      params: httpParams
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  post<T>(endpoint: string, data?: any): Observable<T> {
    return this.http.post<ApiResponse<T>>(this.getUrl(endpoint), data, {
      headers: this.getHeaders()
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  put<T>(endpoint: string, data?: any): Observable<T> {
    return this.http.put<ApiResponse<T>>(this.getUrl(endpoint), data, {
      headers: this.getHeaders()
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  patch<T>(endpoint: string, data?: any): Observable<T> {
    return this.http.patch<ApiResponse<T>>(this.getUrl(endpoint), data, {
      headers: this.getHeaders()
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  delete<T>(endpoint: string): Observable<T> {
    return this.http.delete<ApiResponse<T>>(this.getUrl(endpoint), {
      headers: this.getHeaders()
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  uploadFile<T>(endpoint: string, formData: FormData): Observable<T> {
    const token = localStorage.getItem(ApiConfig.auth.tokenKey);
    let headers = new HttpHeaders();
    
    if (token) {
      headers = headers.set('Authorization', `${ApiConfig.auth.tokenPrefix} ${token}`);
    }

    return this.http.post<ApiResponse<T>>(this.getUrl(endpoint), formData, {
      headers
    }).pipe(
      map(response => response.data as T),
      catchError(this.handleError)
    );
  }

  downloadFile(endpoint: string): Observable<Blob> {
    return this.http.get(this.getUrl(endpoint), {
      headers: this.getHeaders(),
      responseType: 'blob'
    }).pipe(
      catchError(this.handleError)
    );
  }

  private handleError = (error: any): Observable<never> => {
    const apiError: ApiError = {
      message: 'Ha ocurrido un error inesperado',
      status: error.status || 500,
      error: error.error?.error || 'Error desconocido',
      errors: error.error?.errors || {}
    };

    if (error.error?.message) {
      apiError.message = error.error.message;
    }

    return throwError(() => apiError);
  };
}