import { Injectable } from '@angular/core';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class PermissionService {

  constructor(private authService: AuthService) {}

  hasPermission(permission: string): boolean {
    return this.authService.hasPermission(permission);
  }

  hasAnyPermission(permissions: string[]): boolean {
    return this.authService.hasAnyPermission(permissions);
  }

  hasAllPermissions(permissions: string[]): boolean {
    return permissions.every(permission => this.authService.hasPermission(permission));
  }

  hasRole(role: string): boolean {
    return this.authService.hasRole(role);
  }

  hasAnyRole(roles: string[]): boolean {
    return this.authService.hasAnyRole(roles);
  }

  hasAllRoles(roles: string[]): boolean {
    return roles.every(role => this.authService.hasRole(role));
  }

  canAccessResource(permissions?: string[], roles?: string[], requireAll = false): boolean {
    if (!this.authService.isAuthenticated()) {
      return false;
    }

    let hasPermissionAccess = true;
    let hasRoleAccess = true;

    if (permissions?.length) {
      hasPermissionAccess = requireAll
        ? this.hasAllPermissions(permissions)
        : this.hasAnyPermission(permissions);
    }

    if (roles?.length) {
      hasRoleAccess = requireAll
        ? this.hasAllRoles(roles)
        : this.hasAnyRole(roles);
    }

    return hasPermissionAccess && hasRoleAccess;
  }
}