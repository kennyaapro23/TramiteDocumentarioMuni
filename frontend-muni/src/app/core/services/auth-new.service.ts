import { Injectable, inject, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, BehaviorSubject, tap, map, catchError, of } from 'rxjs';
import { 
  LoginRequest, 
  LoginResponse, 
  RegisterRequest, 
  RegisterResponse,
  LogoutResponse,
  User,
  UserProfile,
  ApiResponse
} from '../../shared/models';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private readonly http = inject(HttpClient);
  private readonly router = inject(Router);
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}`;

  // Signals for reactive state management
  private readonly _currentUser = signal<User | null>(null);
  private readonly _isAuthenticated = signal<boolean>(false);
  private readonly _isLoading = signal<boolean>(false);

  // Public readonly signals
  public readonly currentUser = this._currentUser.asReadonly();
  public readonly isAuthenticated = this._isAuthenticated.asReadonly();
  public readonly isLoading = this._isLoading.asReadonly();

  // BehaviorSubjects for backwards compatibility
  private readonly userSubject = new BehaviorSubject<User | null>(null);
  public readonly user$ = this.userSubject.asObservable();

  constructor() {
    this.initializeAuth();
  }

  /**
   * Initialize authentication state from localStorage
   */
  private initializeAuth(): void {
    const token = this.getToken();
    if (token) {
      this.loadUserProfile().subscribe({
        next: (user) => {
          this.setUser(user);
        },
        error: () => {
          this.clearAuth();
        }
      });
    }
  }

  /**
   * Login user
   */
  login(credentials: LoginRequest): Observable<LoginResponse> {
    this._isLoading.set(true);
    
    return this.http.post<LoginResponse>(`${this.baseUrl}/login`, credentials).pipe(
      tap(response => {
        if (response.success && response.data) {
          this.setToken(response.data.token);
          this.setUser(response.data.user);
        }
      }),
      catchError(error => {
        this.clearAuth();
        throw error;
      }),
      tap(() => this._isLoading.set(false))
    );
  }

  /**
   * Register new user
   */
  register(userData: RegisterRequest): Observable<RegisterResponse> {
    this._isLoading.set(true);
    
    return this.http.post<RegisterResponse>(`${this.baseUrl}/register`, userData).pipe(
      tap(response => {
        if (response.success && response.data) {
          this.setToken(response.data.token);
          this.setUser(response.data.user);
        }
      }),
      catchError(error => {
        this.clearAuth();
        throw error;
      }),
      tap(() => this._isLoading.set(false))
    );
  }

  /**
   * Logout user
   */
  logout(): Observable<LogoutResponse> {
    return this.http.post<LogoutResponse>(`${this.baseUrl}/logout`, {}).pipe(
      tap(() => {
        this.clearAuth();
        this.router.navigate(['/login']);
      }),
      catchError(() => {
        // Even if logout fails on server, clear local auth
        this.clearAuth();
        this.router.navigate(['/login']);
        return of({ success: true, message: 'Sesión cerrada localmente' });
      })
    );
  }

  /**
   * Get current user profile
   */
  getCurrentUser(): Observable<User | null> {
    if (!this.getToken()) {
      return of(null);
    }

    return this.http.get<ApiResponse<User>>(`${this.baseUrl}/user`).pipe(
      map(response => response.success ? response.data || null : null),
      tap(user => {
        if (user) {
          this.setUser(user);
        } else {
          this.clearAuth();
        }
      }),
      catchError(() => {
        this.clearAuth();
        return of(null);
      })
    );
  }

  /**
   * Load user profile from server
   */
  private loadUserProfile(): Observable<User> {
    return this.http.get<ApiResponse<User>>(`${this.baseUrl}/user`).pipe(
      map(response => {
        if (!response.success || !response.data) {
          throw new Error('Failed to load user profile');
        }
        return response.data;
      })
    );
  }

  /**
   * Update user profile
   */
  updateProfile(profileData: Partial<UserProfile>): Observable<ApiResponse<User>> {
    return this.http.put<ApiResponse<User>>(`${this.baseUrl}/profile`, profileData).pipe(
      tap(response => {
        if (response.success && response.data) {
          this.setUser(response.data);
        }
      })
    );
  }

  /**
   * Change password
   */
  changePassword(passwordData: {
    current_password: string;
    password: string;
    password_confirmation: string;
  }): Observable<ApiResponse<void>> {
    return this.http.post<ApiResponse<void>>(`${this.baseUrl}/change-password`, passwordData);
  }

  /**
   * Check if user has specific permission
   */
  hasPermission(permission: string): boolean {
    const user = this._currentUser();
    if (!user || !user.permissions) return false;
    
    return user.permissions.some(p => p.name === permission);
  }

  /**
   * Check if user has specific role
   */
  hasRole(role: string): boolean {
    const user = this._currentUser();
    if (!user || !user.roles) return false;
    
    return user.roles.some(r => r.name === role);
  }

  /**
   * Check if user has any of the specified roles
   */
  hasAnyRole(roles: string[]): boolean {
    return roles.some(role => this.hasRole(role));
  }

  /**
   * Check if user has all specified permissions
   */
  hasAllPermissions(permissions: string[]): boolean {
    return permissions.every(permission => this.hasPermission(permission));
  }

  /**
   * Get authentication token
   */
  getToken(): string | null {
    return localStorage.getItem('auth_token');
  }

  /**
   * Set authentication token
   */
  private setToken(token: string): void {
    localStorage.setItem('auth_token', token);
  }

  /**
   * Set current user
   */
  private setUser(user: User): void {
    this._currentUser.set(user);
    this._isAuthenticated.set(true);
    this.userSubject.next(user);
  }

  /**
   * Clear authentication data
   */
  private clearAuth(): void {
    localStorage.removeItem('auth_token');
    this._currentUser.set(null);
    this._isAuthenticated.set(false);
    this.userSubject.next(null);
  }

  /**
   * Check if token exists (for guards)
   */
  isTokenValid(): boolean {
    const token = this.getToken();
    if (!token) return false;

    try {
      // Basic token validation - you might want to add JWT validation
      const payload = JSON.parse(atob(token.split('.')[1]));
      const exp = payload.exp * 1000;
      return Date.now() < exp;
    } catch {
      return false;
    }
  }

  /**
   * Refresh authentication state
   */
  refreshAuth(): Observable<boolean> {
    return this.getCurrentUser().pipe(
      map(user => !!user)
    );
  }
}