import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { 
  Documento,
  ApiResponse,
  FileUploadResponse 
} from '../../shared/models';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class DocumentoService {
  private readonly http = inject(HttpClient);
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}/documentos`;

  /**
   * Descargar documento
   */
  downloadDocumento(id: number): Observable<Blob> {
    return this.http.get(`${this.baseUrl}/${id}/download`, { 
      responseType: 'blob',
      headers: { 'Accept': 'application/octet-stream' }
    });
  }

  /**
   * Ver documento (para visualización en navegador)
   */
  viewDocumento(id: number): Observable<Blob> {
    return this.http.get(`${this.baseUrl}/${id}/view`, { 
      responseType: 'blob',
      headers: { 'Accept': 'application/pdf, image/*' }
    });
  }

  /**
   * Obtener información del documento
   */
  getDocumento(id: number): Observable<ApiResponse<Documento>> {
    return this.http.get<ApiResponse<Documento>>(`${this.baseUrl}/${id}`);
  }

  /**
   * Subir documento
   */
  uploadDocumento(file: File, expedienteId: number, metadata: {
    nombre: string;
    tipo_documento: string;
  }): Observable<FileUploadResponse> {
    const formData = new FormData();
    formData.append('archivo', file);
    formData.append('expediente_id', expedienteId.toString());
    formData.append('nombre', metadata.nombre);
    formData.append('tipo_documento', metadata.tipo_documento);

    return this.http.post<FileUploadResponse>(`${this.baseUrl}`, formData);
  }

  /**
   * Actualizar documento
   */
  updateDocumento(id: number, metadata: Partial<Documento>): Observable<ApiResponse<Documento>> {
    return this.http.put<ApiResponse<Documento>>(`${this.baseUrl}/${id}`, metadata);
  }

  /**
   * Eliminar documento
   */
  deleteDocumento(id: number): Observable<ApiResponse<void>> {
    return this.http.delete<ApiResponse<void>>(`${this.baseUrl}/${id}`);
  }

  /**
   * Aprobar documento
   */
  aprobarDocumento(id: number): Observable<ApiResponse<Documento>> {
    return this.http.patch<ApiResponse<Documento>>(`${this.baseUrl}/${id}/aprobar`, {});
  }

  /**
   * Rechazar documento
   */
  rechazarDocumento(id: number, motivo: string): Observable<ApiResponse<Documento>> {
    return this.http.patch<ApiResponse<Documento>>(`${this.baseUrl}/${id}/rechazar`, { motivo });
  }

  /**
   * Obtener URL para vista previa (sin descarga)
   */
  getPreviewUrl(id: number): string {
    return `${this.baseUrl}/${id}/view`;
  }

  /**
   * Obtener URL para descarga directa
   */
  getDownloadUrl(id: number): string {
    return `${this.baseUrl}/${id}/download`;
  }

  /**
   * Validar archivo antes de subida
   */
  validateFile(file: File): { valid: boolean; errors: string[] } {
    const errors: string[] = [];
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = [
      'application/pdf',
      'image/jpeg',
      'image/png',
      'image/jpg',
      'application/msword',
      'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    if (file.size > maxSize) {
      errors.push('El archivo excede el tamaño máximo de 10MB');
    }

    if (!allowedTypes.includes(file.type)) {
      errors.push('Tipo de archivo no permitido. Solo se aceptan PDF, imágenes y documentos de Word');
    }

    return {
      valid: errors.length === 0,
      errors
    };
  }
}