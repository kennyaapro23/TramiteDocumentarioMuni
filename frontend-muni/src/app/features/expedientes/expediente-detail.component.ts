import { Component } from '@angular/core';

@Component({
  selector: 'app-expediente-detail',
  standalone: true,
  imports: [],
  template: `
    <div class="expediente-detail">
      <div class="header">
        <button class="btn-back">← Volver</button>
        <h1>Expediente EXP-2025-000001</h1>
        <span class="badge badge-pendiente">Pendiente</span>
      </div>

      <div class="content-grid">
        <div class="main-content">
          <div class="card">
            <h2>Información del Solicitante</h2>
            <div class="info-grid">
              <div><strong>Nombre:</strong> --</div>
              <div><strong>DNI:</strong> --</div>
              <div><strong>Email:</strong> --</div>
              <div><strong>Teléfono:</strong> --</div>
            </div>
          </div>

          <div class="card">
            <h2>Detalles del Trámite</h2>
            <div class="info-grid">
              <div><strong>Tipo:</strong> --</div>
              <div><strong>Asunto:</strong> --</div>
              <div class="full-width"><strong>Descripción:</strong> --</div>
            </div>
          </div>

          <div class="card">
            <h2>Documentos</h2>
            <div class="documents-list">
              <p class="no-data">Cargando documentos...</p>
            </div>
          </div>
        </div>

        <div class="sidebar">
          <div class="card">
            <h2>Acciones</h2>
            <div class="actions">
              <button class="btn-action">Derivar</button>
              <button class="btn-action">Revisión Técnica</button>
              <button class="btn-action">Rechazar</button>
            </div>
          </div>

          <div class="card">
            <h2>Historial</h2>
            <div class="timeline">
              <p class="no-data">Cargando historial...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .expediente-detail {
      padding: 2rem;
    }
    .header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .btn-back {
      background: #6c757d;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
    }
    .badge {
      padding: 0.25rem 0.75rem;
      border-radius: 12px;
      font-size: 0.875rem;
      font-weight: 500;
    }
    .badge-pendiente {
      background: #fff3cd;
      color: #856404;
    }
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 2rem;
    }
    .card {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin-bottom: 1.5rem;
    }
    .card h2 {
      margin: 0 0 1rem 0;
      color: #333;
      font-size: 1.25rem;
    }
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
    }
    .full-width {
      grid-column: 1 / -1;
    }
    .actions {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    .btn-action {
      background: #667eea;
      color: white;
      border: none;
      padding: 0.75rem;
      border-radius: 4px;
      cursor: pointer;
      text-align: left;
    }
    .btn-action:hover {
      background: #5a6fd8;
    }
    .no-data {
      color: #666;
      font-style: italic;
      text-align: center;
    }
  `]
})
export class ExpedienteDetailComponent {}