import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpBaseService } from './http-base.service';
import { ApiConfig } from './api-config';
import { Gerencia } from '../models';

@Injectable({
  providedIn: 'root'
})
export class GerenciasService {

  constructor(private httpBase: HttpBaseService) {}

  getGerencias(): Observable<Gerencia[]> {
    return this.httpBase.get<Gerencia[]>(ApiConfig.endpoints.gerencias.list);
  }

  getSubgerencias(gerenciaId: number): Observable<Gerencia[]> {
    return this.httpBase.get<Gerencia[]>(ApiConfig.endpoints.gerencias.subgerencias(gerenciaId));
  }

  // Métodos de administración (requieren permisos)
  createGerencia(data: {
    nombre: string;
    codigo: string;
    tipo: 'gerencia' | 'subgerencia';
    gerencia_padre_id?: number;
    flujos_permitidos?: string[];
    activo: boolean;
    orden: number;
  }): Observable<Gerencia> {
    return this.httpBase.post<Gerencia>(ApiConfig.endpoints.admin.gerencias.create, data);
  }

  updateGerencia(id: number, data: Partial<Gerencia>): Observable<Gerencia> {
    return this.httpBase.put<Gerencia>(ApiConfig.endpoints.admin.gerencias.update(id), data);
  }

  deleteGerencia(id: number): Observable<void> {
    return this.httpBase.delete<void>(ApiConfig.endpoints.admin.gerencias.delete(id));
  }

  asignarUsuario(gerenciaId: number, usuarioId: number): Observable<void> {
    return this.httpBase.patch<void>(
      ApiConfig.endpoints.admin.gerencias.asignarUsuario(gerenciaId), 
      { usuario_id: usuarioId }
    );
  }

  removerUsuario(gerenciaId: number, usuarioId: number): Observable<void> {
    return this.httpBase.patch<void>(
      ApiConfig.endpoints.admin.gerencias.removerUsuario(gerenciaId), 
      { usuario_id: usuarioId }
    );
  }

  getGerenciasAdmin(): Observable<Gerencia[]> {
    return this.httpBase.get<Gerencia[]>(ApiConfig.endpoints.admin.gerencias.list);
  }
}