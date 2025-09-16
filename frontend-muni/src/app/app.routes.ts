import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
  // Login
  { 
    path: 'login',
    loadComponent: () => import('./features/auth/components/login/login.component').then(m => m.LoginComponent)
  },

  // Layout wrapper (protected)
  {
    path: '',
    loadComponent: () => import('./layout/layout.component').then(m => m.LayoutComponent),
    canActivate: [authGuard],
    children: [
      { path: 'dashboard', loadComponent: () => import('./features/dashboard/dashboard.component').then(m => m.DashboardComponent) },
      { path: 'expedientes', loadComponent: () => import('./features/expedientes/expedientes-list/expedientes-list.component').then(m => m.ExpedientesListComponent) },
      { path: 'expedientes/nuevo', loadComponent: () => import('./features/expedientes/expediente-create/expediente-create.component').then(m => m.ExpedienteCreateComponent) },
      { path: 'expedientes/:id', loadComponent: () => import('./features/expedientes/expediente-detail/expediente-detail.component').then(m => m.ExpedienteDetailComponent) },
      { path: 'gerencias', loadComponent: () => import('./features/admin/admin-gerencias.component').then(m => m.AdminGerenciasComponent) },
      { path: 'usuarios', loadComponent: () => import('./features/admin/admin-usuarios.component').then(m => m.AdminUsuariosComponent) }
    ]
  },

  // Fallback
  { path: '**', redirectTo: '/login' }
];
