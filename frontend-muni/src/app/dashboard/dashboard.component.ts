import { Component, inject, signal, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../core/services/auth.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  private authService = inject(AuthService);
  private router = inject(Router);
  
  currentUser = this.authService.currentUser;
  
  // Mock stats data
  statsCards = signal([
    {
      title: 'Trámites Pendientes',
      value: 24,
      subtitle: 'Esperando respuesta',
      icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
      colorClass: 'bg-gradient-to-r from-yellow-500 to-yellow-600'
    },
    {
      title: 'Trámites Completados',
      value: 156,
      subtitle: 'Este mes',
      icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
      colorClass: 'bg-gradient-to-r from-green-500 to-green-600'
    },
    {
      title: 'Tiempo Promedio',
      value: '5.2 días',
      subtitle: 'Resolución de trámites',
      icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
      colorClass: 'bg-gradient-to-r from-blue-500 to-blue-600'
    },
    {
      title: 'Usuarios Activos',
      value: 89,
      subtitle: 'En el sistema',
      icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
      colorClass: 'bg-gradient-to-r from-purple-500 to-purple-600'
    }
  ]);

  // Mock recent activities
  recentActivities = signal([
    {
      id: 1,
      title: 'Nuevo trámite de licencia comercial',
      description: 'Solicitud #2024-0156 creada por Juan Pérez',
      time: '2 minutos',
      icon: 'M12 4v16m8-8H4',
      colorClass: 'bg-green-100 text-green-600'
    },
    {
      id: 2,
      title: 'Trámite derivado a Mesa de Partes',
      description: 'Solicitud #2024-0155 derivada para revisión',
      time: '15 minutos',
      icon: 'M7 16l-4-4m0 0l4-4m-4 4h18',
      colorClass: 'bg-blue-100 text-blue-600'
    },
    {
      id: 3,
      title: 'Trámite completado',
      description: 'Certificado de residencia #2024-0142 aprobado',
      time: '1 hora',
      icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
      colorClass: 'bg-green-100 text-green-600'
    }
  ]);

  ngOnInit(): void {
    // Component initialization
  }

  getUserWelcomeMessage(): string {
    const hour = new Date().getHours();
    const name = this.currentUser()?.name?.split(' ')[0] || 'Usuario';
    
    if (hour < 12) {
      return `Buenos días, ${name}`;
    } else if (hour < 18) {
      return `Buenas tardes, ${name}`;
    } else {
      return `Buenas noches, ${name}`;
    }
  }

  hasPermission(permission: string): boolean {
    return this.authService.hasPermission(permission);
  }

  logout(): void {
    this.authService.logout().subscribe({
      next: () => {
        this.router.navigate(['/login']);
      }
    });
  }
}