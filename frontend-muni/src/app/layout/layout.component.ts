import { Component, inject } from '@angular/core';
import { RouterOutlet, Router, NavigationEnd } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { filter } from 'rxjs/operators';

interface Notification {
  id: string;
  title: string;
  message: string;
  time: string;
  icon: string;
  read: boolean;
}

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [RouterOutlet, CommonModule, FormsModule],
  templateUrl: './layout.component.html',
  styleUrls: ['./layout.component.css']
})
export class LayoutComponent {
  private router = inject(Router);

  // Sidebar state
  public sidebarCollapsed = false;
  public mobileMenuOpen = false;

  // Search
  public searchQuery = '';

  // Notifications
  public notificationsOpen = false;
  public notificationCount = 3;
  public notifications: Notification[] = [
    {
      id: '1',
      title: 'Nuevo expediente',
      message: 'Se ha creado un nuevo expediente #EXP-2025-001',
      time: 'hace 5 minutos',
      icon: '📋',
      read: false
    },
    {
      id: '2',
      title: 'Documento aprobado',
      message: 'El documento EXP-2025-002 ha sido aprobado',
      time: 'hace 1 hora',
      icon: '✅',
      read: false
    },
    {
      id: '3',
      title: 'Recordatorio',
      message: 'Revisar expedientes pendientes',
      time: 'hace 2 horas',
      icon: '⏰',
      read: true
    }
  ];

  // User menu
  public userMenuOpen = false;

  constructor() {
    // Listen to route changes for breadcrumb
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      // Auto-close mobile menu on navigation
      this.mobileMenuOpen = false;
    });
  }

  /**
   * Toggle sidebar collapse state
   */
  public toggleSidebar(): void {
    this.sidebarCollapsed = !this.sidebarCollapsed;
  }

  /**
   * Toggle mobile menu
   */
  public toggleMobileMenu(): void {
    this.mobileMenuOpen = !this.mobileMenuOpen;
  }

  /**
   * Get current page name for breadcrumb
   */
  public getCurrentPage(): string {
    const url = this.router.url;
    
    if (url.includes('/dashboard')) return 'Dashboard';
    if (url.includes('/expedientes/nuevo')) return 'Nuevo Expediente';
    if (url.includes('/expedientes')) return 'Expedientes';
    if (url.includes('/gerencias')) return 'Gerencias';
    if (url.includes('/usuarios')) return 'Usuarios';
    
    return 'Inicio';
  }

  /**
   * Handle search functionality
   */
  public onSearch(): void {
    if (this.searchQuery.trim()) {
      console.log('Searching for:', this.searchQuery);
      // TODO: Implement search functionality
      // this.router.navigate(['/search'], { queryParams: { q: this.searchQuery } });
    }
  }

  /**
   * Toggle notifications panel
   */
  public toggleNotifications(): void {
    this.notificationsOpen = !this.notificationsOpen;
    this.userMenuOpen = false; // Close user menu if open
  }

  /**
   * Toggle user menu
   */
  public toggleUserMenu(): void {
    this.userMenuOpen = !this.userMenuOpen;
    this.notificationsOpen = false; // Close notifications if open
  }

  /**
   * Open settings
   */
  public openSettings(): void {
    console.log('Opening settings...');
    // TODO: Implement settings functionality
    // this.router.navigate(['/settings']);
  }

  /**
   * Handle logout
   */
  public logout(): void {
    if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
      console.log('Logging out...');
      // TODO: Implement logout functionality
      // this.authService.logout();
      this.router.navigate(['/login']);
    }
  }
}