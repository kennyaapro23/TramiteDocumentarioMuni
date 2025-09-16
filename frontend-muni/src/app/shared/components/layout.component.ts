import { Component } from '@angular/core';
import { RouterOutlet, RouterLink, Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';
import { HasPermissionDirective } from '../directives/has-permission.directive';

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [RouterOutlet, RouterLink, HasPermissionDirective],
  template: `
    <div class="layout">
      <header class="header">
        <div class="header-left">
          <h1>Sistema de Expedientes</h1>
        </div>
        <div class="header-right">
          <span class="user-name">{{ authService.currentUser()?.name }}</span>
          <button class="btn-logout" (click)="logout()">Cerrar Sesión</button>
        </div>
      </header>

      <div class="content">
        <nav class="sidebar">
          <ul class="nav-menu">
            <li>
              <a routerLink="/dashboard" routerLinkActive="active">
                📊 Dashboard
              </a>
            </li>
            <li *hasPermission="'ver_expedientes'">
              <a routerLink="/expedientes" routerLinkActive="active">
                📋 Expedientes
              </a>
            </li>
            <li *hasPermission="'gestionar_gerencias'">
              <a routerLink="/admin/gerencias" routerLinkActive="active">
                🏢 Gerencias
              </a>
            </li>
          </ul>
        </nav>

        <main class="main-content">
          <router-outlet />
        </main>
      </div>
    </div>
  `,
  styles: [`
    .layout {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .header {
      background: #667eea;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .header h1 {
      margin: 0;
      font-size: 1.5rem;
    }
    .header-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .user-name {
      font-weight: 500;
    }
    .btn-logout {
      background: rgba(255,255,255,0.2);
      color: white;
      border: 1px solid rgba(255,255,255,0.3);
      padding: 0.5rem 1rem;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .btn-logout:hover {
      background: rgba(255,255,255,0.3);
    }
    .content {
      flex: 1;
      display: flex;
    }
    .sidebar {
      width: 250px;
      background: #f8f9fa;
      border-right: 1px solid #dee2e6;
      padding: 2rem 0;
    }
    .nav-menu {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .nav-menu li {
      margin-bottom: 0.5rem;
    }
    .nav-menu a {
      display: block;
      padding: 0.75rem 2rem;
      color: #495057;
      text-decoration: none;
      transition: background 0.3s;
    }
    .nav-menu a:hover {
      background: #e9ecef;
    }
    .nav-menu a.active {
      background: #667eea;
      color: white;
    }
    .main-content {
      flex: 1;
      background: #f8f9fa;
      overflow-y: auto;
    }
  `]
})
export class LayoutComponent {
  constructor(
    public authService: AuthService,
    private router: Router
  ) {}

  logout(): void {
    this.authService.logout().subscribe(() => {
      this.router.navigate(['/auth/login']);
    });
  }
}