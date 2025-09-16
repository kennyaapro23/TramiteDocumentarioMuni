import { Routes } from '@angular/router';
import { authGuard } from './core/guards/auth.guard';

export const routes: Routes = [
  // Ruta de login
  { 
    path: 'login', 
    loadComponent: () => import('./features/auth/login.component').then(m => m.LoginComponent)
  },
  
  // Ruta protegida para el dashboard (temporal - crearemos después)
  { 
    path: 'dashboard', 
    canActivate: [authGuard],
    loadComponent: () => import('./features/auth/login.component').then(m => m.LoginComponent) // temporal
  },
  
  // Redirección por defecto
  { path: '', redirectTo: '/login', pathMatch: 'full' },
  
  // Ruta wildcard para páginas no encontradas
  { path: '**', redirectTo: '/login' }
];
