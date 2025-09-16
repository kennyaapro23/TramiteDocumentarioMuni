import { Injectable, signal, computed } from '@angular/core';
import { Observable, BehaviorSubject, of } from 'rxjs';
import { tap, catchError, map } from 'rxjs/operators';
import { HttpBaseService } from './http-base.service';
import { ApiConfig } from './api-config';
import { LoginRequest, LoginResponse, User } from '../models';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private currentUserSubject = new BehaviorSubject<User | null>(null);
  private isAuthenticatedSubject = new BehaviorSubject<boolean>(false);

  // Signals para estado reactivo
  private _currentUser = signal<User | null>(null);
  private _isAuthenticated = signal<boolean>(false);
  private _isLoading = signal<boolean>(false);

  // Computed signals
  public currentUser = computed(() => this._currentUser());
  public isAuthenticated = computed(() => this._isAuthenticated());
  public isLoading = computed(() => this._isLoading());

  // Observables para compatibilidad
  public currentUser$ = this.currentUserSubject.asObservable();
  public isAuthenticated$ = this.isAuthenticatedSubject.asObservable();

  constructor(private httpBase: HttpBaseService) {
    this.initializeAuth();
  }

  private initializeAuth(): void {
    const token = localStorage.getItem(ApiConfig.auth.tokenKey);
    if (token) {
      this.loadUserProfile();
    }
  }

  login(credentials: LoginRequest): Observable<User> {
    this._isLoading.set(true);
    
    return this.httpBase.post<LoginResponse>(ApiConfig.endpoints.auth.login, credentials).pipe(
      tap(response => {
        if (response.token && response.user) {
          this.setAuthData(response.token, response.user);
        }
        this._isLoading.set(false);
      }),
      map(response => response.user),
      catchError(error => {
        this._isLoading.set(false);
        throw error;
      })
    );
  }

  logout(): Observable<any> {
    return this.httpBase.post(ApiConfig.endpoints.auth.logout).pipe(
      tap(() => {
        this.clearAuthData();
      }),
      catchError(() => {
        // Incluso si el logout en el servidor falla, limpiamos localmente
        this.clearAuthData();
        return of(null);
      })
    );
  }

  loadUserProfile(): Observable<User> {
    return this.httpBase.get<User>(ApiConfig.endpoints.auth.profile).pipe(
      tap(user => {
        this.setUser(user);
      }),
      catchError(error => {
        // Si el token es inválido, limpiamos la sesión
        if (error.status === 401) {
          this.clearAuthData();
        }
        throw error;
      })
    );
  }

  private setAuthData(token: string, user: User): void {
    localStorage.setItem(ApiConfig.auth.tokenKey, token);
    this.setUser(user);
  }

  private setUser(user: User): void {
    this._currentUser.set(user);
    this._isAuthenticated.set(true);
    this.currentUserSubject.next(user);
    this.isAuthenticatedSubject.next(true);
  }

  private clearAuthData(): void {
    localStorage.removeItem(ApiConfig.auth.tokenKey);
    this._currentUser.set(null);
    this._isAuthenticated.set(false);
    this.currentUserSubject.next(null);
    this.isAuthenticatedSubject.next(false);
  }

  getToken(): string | null {
    return localStorage.getItem(ApiConfig.auth.tokenKey);
  }

  hasPermission(permission: string): boolean {
    const user = this._currentUser();
    if (!user || !user.permissions) return false;
    
    return user.permissions.some(p => p.name === permission);
  }

  hasRole(role: string): boolean {
    const user = this._currentUser();
    if (!user || !user.roles) return false;
    
    return user.roles.some(r => r.name === role);
  }

  hasAnyPermission(permissions: string[]): boolean {
    return permissions.some(permission => this.hasPermission(permission));
  }

  hasAllPermissions(permissions: string[]): boolean {
    return permissions.every(permission => this.hasPermission(permission));
  }

  hasAnyRole(roles: string[]): boolean {
    return roles.some(role => this.hasRole(role));
  }
}