import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from '../services/auth.service';

export const permissionGuard: CanActivateFn = (route) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  // Verificar que el usuario esté autenticado
  if (!authService.isAuthenticated()) {
    router.navigate(['/auth/login']);
    return false;
  }

  // Obtener permisos requeridos de la metadata de la ruta
  const requiredPermissions = route.data?.['permissions'] as string[];
  const requiredRoles = route.data?.['roles'] as string[];
  const requireAll = route.data?.['requireAll'] === true; // Por defecto OR

  if (requiredPermissions?.length) {
    const hasPermissions = requireAll
      ? requiredPermissions.every(permission => authService.hasPermission(permission))
      : authService.hasAnyPermission(requiredPermissions);

    if (!hasPermissions) {
      router.navigate(['/unauthorized']);
      return false;
    }
  }

  if (requiredRoles?.length) {
    const hasRoles = requireAll
      ? requiredRoles.every(role => authService.hasRole(role))
      : authService.hasAnyRole(requiredRoles);

    if (!hasRoles) {
      router.navigate(['/unauthorized']);
      return false;
    }
  }

  return true;
};