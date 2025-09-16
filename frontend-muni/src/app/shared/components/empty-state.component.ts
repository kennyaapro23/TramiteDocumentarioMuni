import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-empty-state',
  standalone: true,
  imports: [],
  template: `
    <div class="empty-state">
      <div class="icon">📋</div>
      <h3>{{ title }}</h3>
      <p>{{ message }}</p>
      @if (actionText && actionClick) {
        <button class="btn-action" (click)="actionClick()">
          {{ actionText }}
        </button>
      }
    </div>
  `,
  styles: [`
    .empty-state {
      text-align: center;
      padding: 3rem 2rem;
      color: #666;
    }
    .icon {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }
    h3 {
      margin: 0 0 0.5rem 0;
      color: #333;
    }
    p {
      margin: 0 0 1.5rem 0;
      font-size: 0.95rem;
    }
    .btn-action {
      background: #667eea;
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1rem;
    }
    .btn-action:hover {
      background: #5a6fd8;
    }
  `]
})
export class EmptyStateComponent {
  @Input() title = 'No hay datos';
  @Input() message = 'No se encontraron elementos para mostrar';
  @Input() actionText?: string;
  @Input() actionClick?: () => void;
}