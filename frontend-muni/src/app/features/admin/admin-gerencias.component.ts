import { Component } from '@angular/core';

@Component({
  selector: 'app-admin-gerencias',
  standalone: true,
  imports: [],
  template: `
    <div class="admin-gerencias">
      <div class="header">
        <h1>Administración de Gerencias</h1>
        <button class="btn-primary">Nueva Gerencia</button>
      </div>

      <div class="filters">
        <select>
          <option value="">Todas</option>
          <option value="gerencia">Solo Gerencias</option>
          <option value="subgerencia">Solo Subgerencias</option>
        </select>
        <input type="text" placeholder="Buscar gerencia..." />
      </div>

      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Código</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Usuarios</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="6" class="no-data">Cargando gerencias...</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `,
  styles: [`
    .admin-gerencias {
      padding: 2rem;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 2rem;
    }
    .filters select, .filters input {
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .table-container {
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid #eee;
    }
    th {
      background: #f8f9fa;
      font-weight: 600;
    }
    .no-data {
      text-align: center;
      color: #666;
      font-style: italic;
    }
    .btn-primary {
      background: #667eea;
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
    }
    .btn-primary:hover {
      background: #5a6fd8;
    }
  `]
})
export class AdminGerenciasComponent {}