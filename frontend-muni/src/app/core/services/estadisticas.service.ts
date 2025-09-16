import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { 
  EstadisticasResponse,
  DashboardStats,
  ApiResponse
} from '../../shared/models';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class EstadisticasService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}`;

  /**
   * Obtener estadísticas generales
   */
  getEstadisticasGenerales(): Observable<EstadisticasResponse> {
    return this.http.get<EstadisticasResponse>(`${this.baseUrl}/estadisticas`);
  }

  /**
   * Obtener estadísticas para el dashboard
   */
  getDashboardStats(): Observable<ApiResponse<DashboardStats>> {
    return this.http.get<ApiResponse<DashboardStats>>(`${this.baseUrl}/dashboard/stats`);
  }

  /**
   * Obtener estadísticas por gerencia
   */
  getEstadisticasPorGerencia(gerenciaId?: number): Observable<ApiResponse<any>> {
    const url = gerenciaId 
      ? `${this.baseUrl}/estadisticas/gerencia/${gerenciaId}`
      : `${this.baseUrl}/estadisticas/gerencias`;
    
    return this.http.get<ApiResponse<any>>(url);
  }

  /**
   * Obtener estadísticas por usuario
   */
  getEstadisticasPorUsuario(usuarioId?: number): Observable<ApiResponse<any>> {
    const url = usuarioId 
      ? `${this.baseUrl}/estadisticas/usuario/${usuarioId}`
      : `${this.baseUrl}/estadisticas/mis-estadisticas`;
    
    return this.http.get<ApiResponse<any>>(url);
  }

  /**
   * Obtener estadísticas por período de tiempo
   */
  getEstadisticasPorTiempo(params: {
    desde: string;
    hasta: string;
    granularidad?: 'dia' | 'semana' | 'mes' | 'año';
  }): Observable<ApiResponse<any>> {
    return this.http.get<ApiResponse<any>>(`${this.baseUrl}/estadisticas/tiempo`, { params });
  }

  /**
   * Obtener reporte de rendimiento
   */
  getReporteRendimiento(params: {
    desde: string;
    hasta: string;
    gerencia_id?: number;
    usuario_id?: number;
  }): Observable<ApiResponse<any>> {
    return this.http.get<ApiResponse<any>>(`${this.baseUrl}/reportes/rendimiento`, { params });
  }

  /**
   * Exportar estadísticas a Excel
   */
  exportarEstadisticas(params: {
    tipo: 'general' | 'gerencia' | 'usuario' | 'tiempo';
    formato: 'excel' | 'pdf';
    desde?: string;
    hasta?: string;
    gerencia_id?: number;
    usuario_id?: number;
  }): Observable<Blob> {
    return this.http.get(`${this.baseUrl}/estadisticas/export`, {
      params,
      responseType: 'blob'
    });
  }
}