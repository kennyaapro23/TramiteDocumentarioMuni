import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { 
  Gerencia, 
  Subgerencia,
  ApiResponse
} from '../../shared/models';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class GerenciaService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}`;

  /**
   * Obtener todas las gerencias con sus subgerencias
   */
  getGerencias(): Observable<ApiResponse<Gerencia[]>> {
    return this.http.get<ApiResponse<Gerencia[]>>(`${this.baseUrl}/gerencias`);
  }

  /**
   * Obtener gerencia por ID
   */
  getGerencia(id: number): Observable<ApiResponse<Gerencia>> {
    return this.http.get<ApiResponse<Gerencia>>(`${this.baseUrl}/gerencias/${id}`);
  }

  /**
   * Obtener subgerencias de una gerencia específica
   */
  getSubgerencias(gerenciaId: number): Observable<ApiResponse<Subgerencia[]>> {
    return this.http.get<ApiResponse<Subgerencia[]>>(`${this.baseUrl}/gerencias/${gerenciaId}/subgerencias`);
  }

  /**
   * Crear nueva gerencia (solo administradores)
   */
  createGerencia(gerencia: Partial<Gerencia>): Observable<ApiResponse<Gerencia>> {
    return this.http.post<ApiResponse<Gerencia>>(`${this.baseUrl}/gerencias`, gerencia);
  }

  /**
   * Actualizar gerencia (solo administradores)
   */
  updateGerencia(id: number, gerencia: Partial<Gerencia>): Observable<ApiResponse<Gerencia>> {
    return this.http.put<ApiResponse<Gerencia>>(`${this.baseUrl}/gerencias/${id}`, gerencia);
  }

  /**
   * Eliminar gerencia (solo administradores)
   */
  deleteGerencia(id: number): Observable<ApiResponse<void>> {
    return this.http.delete<ApiResponse<void>>(`${this.baseUrl}/gerencias/${id}`);
  }

  /**
   * Crear nueva subgerencia
   */
  createSubgerencia(subgerencia: Partial<Subgerencia>): Observable<ApiResponse<Subgerencia>> {
    return this.http.post<ApiResponse<Subgerencia>>(`${this.baseUrl}/subgerencias`, subgerencia);
  }

  /**
   * Actualizar subgerencia
   */
  updateSubgerencia(id: number, subgerencia: Partial<Subgerencia>): Observable<ApiResponse<Subgerencia>> {
    return this.http.put<ApiResponse<Subgerencia>>(`${this.baseUrl}/subgerencias/${id}`, subgerencia);
  }

  /**
   * Eliminar subgerencia
   */
  deleteSubgerencia(id: number): Observable<ApiResponse<void>> {
    return this.http.delete<ApiResponse<void>>(`${this.baseUrl}/subgerencias/${id}`);
  }
}