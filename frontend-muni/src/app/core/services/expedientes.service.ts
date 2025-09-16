import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpBaseService } from './http-base.service';
import { ApiConfig } from './api-config';
import { 
  Expediente, 
  PaginatedResponse, 
  CreateExpedienteRequest,
  DerivarExpedienteRequest,
  RevisionTecnicaRequest,
  RevisionLegalRequest,
  EmitirResolucionRequest,
  RechazarExpedienteRequest,
  EstadosExpediente,
  TiposTramite
} from '../models';

@Injectable({
  providedIn: 'root'
})
export class ExpedientesService {

  constructor(private httpBase: HttpBaseService) {}

  getExpedientes(params?: {
    estado?: string;
    gerencia_id?: number;
    search?: string;
    per_page?: number;
    page?: number;
  }): Observable<PaginatedResponse<Expediente> & { estados: EstadosExpediente; tipos_tramite: TiposTramite }> {
    return this.httpBase.get(ApiConfig.endpoints.expedientes.list, params);
  }

  getExpediente(id: number): Observable<Expediente> {
    return this.httpBase.get(ApiConfig.endpoints.expedientes.detail(id));
  }

  createExpediente(data: CreateExpedienteRequest): Observable<{ numero: string; id: number; estado: string }> {
    const formData = new FormData();
    
    // Agregar campos de texto
    formData.append('solicitante_nombre', data.solicitante_nombre);
    formData.append('solicitante_dni', data.solicitante_dni);
    formData.append('solicitante_email', data.solicitante_email);
    formData.append('solicitante_telefono', data.solicitante_telefono);
    formData.append('tipo_tramite', data.tipo_tramite);
    formData.append('asunto', data.asunto);
    formData.append('descripcion', data.descripcion);
    formData.append('gerencia_id', data.gerencia_id.toString());
    
    if (data.subgerencia_id) {
      formData.append('subgerencia_id', data.subgerencia_id.toString());
    }

    // Agregar documentos
    data.documentos.forEach((doc, index) => {
      formData.append(`documentos[${index}][nombre]`, doc.nombre);
      formData.append(`documentos[${index}][tipo_documento]`, doc.tipo_documento);
      formData.append(`documentos[${index}][archivo]`, doc.archivo);
    });

    return this.httpBase.uploadFile(ApiConfig.endpoints.expedientes.create, formData);
  }

  derivarExpediente(id: number, data: DerivarExpedienteRequest): Observable<{ id: number; estado: string; fecha_derivacion: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.derivar(id), data);
  }

  revisionTecnica(id: number, data: RevisionTecnicaRequest): Observable<{ id: number; estado: string; fecha_revision_tecnica: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.revisionTecnica(id), data);
  }

  revisionLegal(id: number, data: RevisionLegalRequest): Observable<{ id: number; estado: string; fecha_revision_legal: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.revisionLegal(id), data);
  }

  emitirResolucion(id: number, data: EmitirResolucionRequest): Observable<{ id: number; estado: string; numero_resolucion: string }> {
    const formData = new FormData();
    formData.append('numero_resolucion', data.numero_resolucion);
    formData.append('archivo_resolucion', data.archivo_resolucion);
    
    if (data.observaciones) {
      formData.append('observaciones', data.observaciones);
    }

    return this.httpBase.uploadFile(ApiConfig.endpoints.expedientes.emitirResolucion(id), formData);
  }

  firmarResolucion(id: number): Observable<{ id: number; estado: string; fecha_firma: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.firmarResolucion(id));
  }

  notificarCiudadano(id: number): Observable<{ id: number; estado: string; notificado_ciudadano: boolean; fecha_notificacion: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.notificar(id));
  }

  rechazarExpediente(id: number, data: RechazarExpedienteRequest): Observable<{ id: number; estado: string; motivo_rechazo: string }> {
    return this.httpBase.patch(ApiConfig.endpoints.expedientes.rechazar(id), data);
  }

  downloadDocumento(id: number): Observable<Blob> {
    return this.httpBase.downloadFile(ApiConfig.endpoints.documentos.download(id));
  }

  viewDocumento(id: number): Observable<Blob> {
    return this.httpBase.downloadFile(ApiConfig.endpoints.documentos.view(id));
  }
}