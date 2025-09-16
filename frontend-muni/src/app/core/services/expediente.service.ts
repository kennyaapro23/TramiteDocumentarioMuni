import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { 
  Expediente, 
  ExpedienteListResponse, 
  ExpedienteResponse,
  CreateExpedienteRequest,
  DerivarExpedienteRequest,
  RevisionTecnicaRequest,
  RevisionLegalRequest,
  EmitirResolucionRequest,
  RechazarExpedienteRequest,
  ExpedienteFilters,
  ApiResponse
} from '../../shared/models';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ExpedienteService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}/expedientes`;

  /**
   * Obtener lista de expedientes con filtros
   */
  getExpedientes(filters?: ExpedienteFilters): Observable<ExpedienteListResponse> {
    let params = new HttpParams();
    
    if (filters) {
      if (filters.estado) params = params.set('estado', filters.estado);
      if (filters.gerencia_id) params = params.set('gerencia_id', filters.gerencia_id.toString());
      if (filters.search) params = params.set('search', filters.search);
      if (filters.per_page) params = params.set('per_page', filters.per_page.toString());
      if (filters.page) params = params.set('page', filters.page.toString());
    }

    return this.http.get<ExpedienteListResponse>(this.baseUrl, { params });
  }

  /**
   * Obtener expediente por ID
   */
  getExpediente(id: number): Observable<ExpedienteResponse> {
    return this.http.get<ExpedienteResponse>(`${this.baseUrl}/${id}`);
  }

  /**
   * Crear nuevo expediente (ciudadano)
   */
  createExpediente(expediente: CreateExpedienteRequest): Observable<ExpedienteResponse> {
    const formData = new FormData();
    
    // Datos principales
    formData.append('solicitante_nombre', expediente.solicitante_nombre);
    formData.append('solicitante_dni', expediente.solicitante_dni);
    formData.append('solicitante_email', expediente.solicitante_email);
    formData.append('solicitante_telefono', expediente.solicitante_telefono);
    formData.append('tipo_tramite', expediente.tipo_tramite);
    formData.append('asunto', expediente.asunto);
    formData.append('descripcion', expediente.descripcion);
    formData.append('gerencia_id', expediente.gerencia_id.toString());
    
    if (expediente.subgerencia_id) {
      formData.append('subgerencia_id', expediente.subgerencia_id.toString());
    }

    // Documentos
    expediente.documentos.forEach((doc, index) => {
      formData.append(`documentos[${index}][nombre]`, doc.nombre);
      formData.append(`documentos[${index}][tipo_documento]`, doc.tipo_documento);
      formData.append(`documentos[${index}][archivo]`, doc.archivo);
    });

    return this.http.post<ExpedienteResponse>(this.baseUrl, formData);
  }

  /**
   * Derivar expediente (Mesa de Partes)
   */
  derivarExpediente(id: number, derivacion: DerivarExpedienteRequest): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/derivar`, derivacion);
  }

  /**
   * Revisión técnica (Gerencia/Subgerencia)
   */
  revisionTecnica(id: number, revision: RevisionTecnicaRequest): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/revision-tecnica`, revision);
  }

  /**
   * Revisión legal (Secretaría General)
   */
  revisionLegal(id: number, revision: RevisionLegalRequest): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/revision-legal`, revision);
  }

  /**
   * Emitir resolución
   */
  emitirResolucion(id: number, resolucion: EmitirResolucionRequest): Observable<ExpedienteResponse> {
    const formData = new FormData();
    formData.append('numero_resolucion', resolucion.numero_resolucion);
    formData.append('archivo_resolucion', resolucion.archivo_resolucion);
    
    if (resolucion.observaciones) {
      formData.append('observaciones', resolucion.observaciones);
    }

    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/emitir-resolucion`, formData);
  }

  /**
   * Firmar resolución (Alcalde)
   */
  firmarResolucion(id: number): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/firma-resolucion`, {});
  }

  /**
   * Notificar ciudadano
   */
  notificarCiudadano(id: number): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/notificar`, {});
  }

  /**
   * Rechazar expediente
   */
  rechazarExpediente(id: number, rechazo: RechazarExpedienteRequest): Observable<ExpedienteResponse> {
    return this.http.patch<ExpedienteResponse>(`${this.baseUrl}/${id}/rechazar`, rechazo);
  }

  /**
   * Obtener expedientes asignados al usuario actual
   */
  getMisExpedientes(filters?: ExpedienteFilters): Observable<ExpedienteListResponse> {
    let params = new HttpParams();
    
    if (filters) {
      if (filters.estado) params = params.set('estado', filters.estado);
      if (filters.search) params = params.set('search', filters.search);
      if (filters.per_page) params = params.set('per_page', filters.per_page.toString());
      if (filters.page) params = params.set('page', filters.page.toString());
    }

    return this.http.get<ExpedienteListResponse>(`${this.baseUrl}/mis-expedientes`, { params });
  }
}