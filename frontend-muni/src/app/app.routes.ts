import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
  // Login
  {
    path: 'login',
    loadComponent: () => import('./modules/auth/login.component').then(m => m.LoginComponent)
  },
  
  // Dashboard (protected)
  {
    path: 'dashboard',
    loadComponent: () => import('./dashboard/dashboard.component').then(m => m.DashboardComponent),
    canActivate: [authGuard]
  },
  
  // Default redirect
  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  },
  
  // Wildcard route - must be last
  {
    path: '**',
    redirectTo: '/login'
  }
];
