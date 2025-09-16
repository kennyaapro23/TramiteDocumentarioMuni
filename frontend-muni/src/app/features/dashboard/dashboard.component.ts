import { Component } from '@angular/core';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [],
  template: `
    <div class="dashboard">
      <h1>Dashboard</h1>
      <div class="stats-grid">
        <div class="stat-card">
          <h3>Total Expedientes</h3>
          <div class="stat-value">--</div>
        </div>
        <div class="stat-card">
          <h3>Pendientes</h3>
          <div class="stat-value">--</div>
        </div>
        <div class="stat-card">
          <h3>En Revisión</h3>
          <div class="stat-value">--</div>
        </div>
        <div class="stat-card">
          <h3>Completados</h3>
          <div class="stat-value">--</div>
        </div>
      </div>
      <div class="quick-actions">
        <h2>Acciones Rápidas</h2>
        <div class="actions-grid">
          <button class="action-btn">Nuevo Expediente</button>
          <button class="action-btn">Ver Expedientes</button>
          <button class="action-btn">Reportes</button>
          <button class="action-btn">Administración</button>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .dashboard {
      padding: 2rem;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      text-align: center;
    }
    .stat-card h3 {
      margin: 0 0 1rem 0;
      color: #666;
      font-size: 0.9rem;
      text-transform: uppercase;
    }
    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      color: #333;
    }
    .quick-actions h2 {
      margin-bottom: 1rem;
      color: #333;
    }
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }
    .action-btn {
      background: #667eea;
      color: white;
      border: none;
      padding: 1rem;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1rem;
      transition: background 0.3s;
    }
    .action-btn:hover {
      background: #5a6fd8;
    }
  `]
})
export class DashboardComponent {}