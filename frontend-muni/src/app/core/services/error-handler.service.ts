import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { ApiError } from '../models';

export interface ErrorMessage {
  title: string;
  message: string;
  type: 'error' | 'warning' | 'info' | 'success';
  duration?: number;
}

@Injectable({
  providedIn: 'root'
})
export class ErrorHandlerService {
  
  constructor(private router: Router) {}

  /**
   * Maneja errores HTTP y los convierte en un formato estándar
   */
  handleHttpError(error: HttpErrorResponse): Observable<never> {
    let apiError: ApiError;

    if (error.error instanceof ErrorEvent) {
      // Error del lado del cliente
      apiError = {
        message: 'Error de conexión. Verifique su internet.',
        status: 0,
        error: error.error.message,
        errors: {}
      };
    } else {
      // Error del servidor
      apiError = {
        message: this.getErrorMessage(error),
        status: error.status,
        error: error.error?.error || error.statusText,
        errors: error.error?.errors || {}
      };

      // Manejar errores específicos
      this.handleSpecificErrors(error);
    }

    console.error('HTTP Error:', apiError);
    return throwError(() => apiError);
  }

  /**
   * Obtiene el mensaje de error apropiado según el status
   */
  private getErrorMessage(error: HttpErrorResponse): string {
    const serverMessage = error.error?.message;
    
    switch (error.status) {
      case 0:
        return 'Error de conexión. Verifique su internet.';
      case 400:
        return serverMessage || 'Solicitud incorrecta.';
      case 401:
        return 'Sesión expirada. Por favor, inicie sesión nuevamente.';
      case 403:
        return 'No tiene permisos para realizar esta acción.';
      case 404:
        return 'El recurso solicitado no fue encontrado.';
      case 422:
        return serverMessage || 'Datos de entrada inválidos.';
      case 429:
        return 'Demasiadas solicitudes. Intente más tarde.';
      case 500:
        return 'Error interno del servidor. Intente más tarde.';
      case 502:
        return 'Error del servidor. Intente más tarde.';
      case 503:
        return 'Servicio temporalmente no disponible.';
      default:
        return serverMessage || 'Ha ocurrido un error inesperado.';
    }
  }

  /**
   * Maneja errores específicos que requieren acciones especiales
   */
  private handleSpecificErrors(error: HttpErrorResponse): void {
    switch (error.status) {
      case 401:
        // Token expirado o inválido
        this.handleUnauthorized();
        break;
      case 403:
        // Sin permisos
        this.handleForbidden();
        break;
      case 404:
        // Recurso no encontrado
        break;
      case 500:
        // Error del servidor
        break;
    }
  }

  /**
   * Maneja errores de autenticación
   */
  private handleUnauthorized(): void {
    // Limpiar token y datos de usuario
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user_data');
    localStorage.removeItem('user_permissions');
    
    // Redirigir al login
    this.router.navigate(['/auth/login']);
  }

  /**
   * Maneja errores de permisos
   */
  private handleForbidden(): void {
    // Mostrar mensaje de permisos insuficientes
    this.showError({
      title: 'Acceso Denegado',
      message: 'No tiene permisos para realizar esta acción.',
      type: 'error'
    });
  }

  /**
   * Muestra un mensaje de error al usuario
   */
  showError(error: ErrorMessage): void {
    // TODO: Integrar con un servicio de notificaciones/toast
    console.error('Error Message:', error);
    
    // Por ahora usar alert, pero debería reemplazarse con un componente de notificaciones
    if (error.type === 'error') {
      alert(`${error.title}: ${error.message}`);
    }
  }

  /**
   * Muestra un mensaje de éxito
   */
  showSuccess(message: string): void {
    this.showError({
      title: 'Éxito',
      message,
      type: 'success'
    });
  }

  /**
   * Muestra un mensaje de advertencia
   */
  showWarning(message: string): void {
    this.showError({
      title: 'Advertencia',
      message,
      type: 'warning'
    });
  }

  /**
   * Muestra un mensaje informativo
   */
  showInfo(message: string): void {
    this.showError({
      title: 'Información',
      message,
      type: 'info'
    });
  }

  /**
   * Formatea errores de validación para mostrar al usuario
   */
  formatValidationErrors(errors: Record<string, string[]>): string {
    const messages = Object.keys(errors).map(field => {
      const fieldErrors = errors[field];
      return `${field}: ${fieldErrors.join(', ')}`;
    });
    
    return messages.join('\n');
  }

  /**
   * Verifica si un error es de validación
   */
  isValidationError(error: ApiError): boolean {
    return error.status === 422 && !!error.errors && Object.keys(error.errors).length > 0;
  }

  /**
   * Verifica si un error es de autenticación
   */
  isAuthError(error: ApiError): boolean {
    return error.status === 401;
  }

  /**
   * Verifica si un error es de permisos
   */
  isPermissionError(error: ApiError): boolean {
    return error.status === 403;
  }
}