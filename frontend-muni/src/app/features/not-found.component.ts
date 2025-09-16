import { Component } from '@angular/core';

@Component({
  selector: 'app-not-found',
  standalone: true,
  imports: [],
  template: `
    <div class="not-found">
      <div class="content">
        <h1>404</h1>
        <h2>Página no encontrada</h2>
        <p>La página que buscas no existe o ha sido movida.</p>
        <button class="btn-home" onclick="window.history.back()">Volver</button>
      </div>
    </div>
  `,
  styles: [`
    .not-found {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8f9fa;
    }
    .content {
      text-align: center;
      max-width: 400px;
    }
    h1 {
      font-size: 6rem;
      color: #667eea;
      margin: 0;
      font-weight: bold;
    }
    h2 {
      color: #333;
      margin: 1rem 0;
    }
    p {
      color: #666;
      margin-bottom: 2rem;
    }
    .btn-home {
      background: #667eea;
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
    }
    .btn-home:hover {
      background: #5a6fd8;
    }
  `]
})
export class NotFoundComponent {}