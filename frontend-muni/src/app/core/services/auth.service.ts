import { Injectable, inject, signal } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Router } from '@angular/router';
import { Observable, BehaviorSubject, tap, catchError, of, throwError } from 'rxjs';
import { LoginRequest, LoginResponse, User } from '../models';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly http = inject(HttpClient);
  private readonly router = inject(Router);

  // Use environment config
  private readonly baseUrl = `${environment.apiConfig.baseUrl}${environment.apiConfig.apiPrefix}`;

  // Toggle mock mode (set to false to use backend)
  private readonly mockMode = false; // <<<<<< CAMBIA A true si quieres modo mock

  // Signals
  private readonly _currentUser = signal<User | null>(null);
  private readonly _isAuthenticated = signal<boolean>(false);
  readonly currentUser = this._currentUser.asReadonly();
  readonly isAuthenticated = this._isAuthenticated.asReadonly();

  private readonly userSubject = new BehaviorSubject<User | null>(null);
  readonly user$ = this.userSubject.asObservable();

  // Mock users (se queda igual por si reactivas mock)
  private mockUsers = [
    { id: 1, name: 'Administrador', email: 'admin@muni.gob.pe', password: '123456', permissions: [
      { id: 1, name: 'gestionar-usuarios', guard_name: 'web' },
      { id: 2, name: 'ver-reportes', guard_name: 'web' },
      { id: 3, name: 'crear-tramites', guard_name: 'web' },
      { id: 4, name: 'gestionar-tramites', guard_name: 'web' }
    ]},
    { id: 2, name: 'Mesa de Partes', email: 'mesa@muni.gob.pe', password: '123456', permissions: [
      { id: 5, name: 'acceso-mesa-partes', guard_name: 'web' },
      { id: 6, name: 'registrar-documentos', guard_name: 'web' },
      { id: 7, name: 'derivar-tramites', guard_name: 'web' }
    ]},
    { id: 3, name: 'Usuario Regular', email: 'user@muni.gob.pe', password: '123456', permissions: [
      { id: 8, name: 'crear-tramites', guard_name: 'web' }
    ]}
  ];

  constructor() { this.restoreSession(); }

  private restoreSession(): void {
    const token = localStorage.getItem(environment.storage.tokenKey);
    const userData = localStorage.getItem(environment.storage.userKey);
    if (token && userData) {
      try {
        const user = JSON.parse(userData) as User;
        this._currentUser.set(user);
        this._isAuthenticated.set(true);
        this.userSubject.next(user);
      } catch { this.logout().subscribe(); }
    }
  }

  login(credentials: LoginRequest): Observable<LoginResponse> {
    if (this.mockMode) return this.mockLogin(credentials);

    const url = `${this.baseUrl}${environment.apiConfig.authEndpoints.login}`;
    return this.http.post<LoginResponse>(url, credentials).pipe(
      tap(res => this.setAuthData(res)),
      catchError(err => this.handleHttpError(err))
    );
  }

  private mockLogin(credentials: LoginRequest): Observable<LoginResponse> {
    const user = this.mockUsers.find(u => u.email === credentials.email && u.password === credentials.password);
    if (!user) return throwError(() => new Error('Credenciales inválidas (mock)'));
    const { password, ...userWithoutPassword } = user as any;
    const response: LoginResponse = {
      access_token: 'mock_token_' + Date.now(),
      token_type: 'Bearer',
      expires_in: 3600,
      user: userWithoutPassword
    };
    this.setAuthData(response);
    return of(response);
  }

  private setAuthData(loginResponse: LoginResponse): void {
    localStorage.setItem(environment.storage.tokenKey, loginResponse.access_token);
    localStorage.setItem(environment.storage.userKey, JSON.stringify(loginResponse.user));
    this._currentUser.set(loginResponse.user);
    this._isAuthenticated.set(true);
    this.userSubject.next(loginResponse.user);
  }

  logout(): Observable<boolean> {
    localStorage.removeItem(environment.storage.tokenKey);
    localStorage.removeItem(environment.storage.userKey);
    this._currentUser.set(null);
    this._isAuthenticated.set(false);
    this.userSubject.next(null);
    return of(true);
  }

  hasPermission(permission: string): boolean {
    return (this._currentUser()?.permissions || []).some(p => p.name === permission);
  }

  getToken(): string | null { return localStorage.getItem(environment.storage.tokenKey); }

  private handleHttpError(error: HttpErrorResponse) {
    let message = 'Error de autenticación';
    if (error.status === 0) message = 'No se pudo conectar con el servidor';
    else if (error.status === 400) message = error.error?.message || 'Solicitud inválida';
    else if (error.status === 401) message = 'Credenciales incorrectas';
    else if (error.status >= 500) message = 'Error interno del servidor';
    return throwError(() => new Error(message));
  }
}