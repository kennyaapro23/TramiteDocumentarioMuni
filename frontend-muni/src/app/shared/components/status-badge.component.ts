import { Component, Input } from '@angular/core';
import { ExpedienteEstado } from '../../core/models';

@Component({
  selector: 'app-status-badge',
  standalone: true,
  imports: [],
  template: `
    <span class="badge" [class]="getBadgeClass()">
      {{ getStatusText() }}
    </span>
  `,
  styles: [`
    .badge {
      padding: 0.25rem 0.75rem;
      border-radius: 12px;
      font-size: 0.875rem;
      font-weight: 500;
      text-transform: capitalize;
    }
    .badge-pendiente {
      background: #fff3cd;
      color: #856404;
    }
    .badge-en-revision {
      background: #cce5ff;
      color: #004085;
    }
    .badge-revision-tecnica {
      background: #d4edda;
      color: #155724;
    }
    .badge-revision-legal {
      background: #e2e3e5;
      color: #383d41;
    }
    .badge-resolucion-emitida {
      background: #d1ecf1;
      color: #0c5460;
    }
    .badge-firmado {
      background: #cff4fc;
      color: #055160;
    }
    .badge-notificado {
      background: #d1e7dd;
      color: #0f5132;
    }
    .badge-completado {
      background: #d1e7dd;
      color: #0f5132;
    }
    .badge-rechazado {
      background: #f8d7da;
      color: #721c24;
    }
  `]
})
export class StatusBadgeComponent {
  @Input() status!: ExpedienteEstado;

  getBadgeClass(): string {
    return `badge-${this.status}`;
  }

  getStatusText(): string {
    const statusMap: Record<ExpedienteEstado, string> = {
      pendiente: 'Pendiente',
      en_revision: 'En Revisión',
      revision_tecnica: 'Revisión Técnica',
      revision_legal: 'Revisión Legal',
      resolucion_emitida: 'Resolución Emitida',
      firmado: 'Firmado',
      notificado: 'Notificado',
      completado: 'Completado',
      rechazado: 'Rechazado'
    };
    return statusMap[this.status] || this.status;
  }
}