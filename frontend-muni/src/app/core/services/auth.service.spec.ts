import { TestBed } from '@angular/core/testing';
import { HttpClientTestingModule, HttpTestingController } from '@angular/common/http/testing';
import { AuthService } from './auth.service';
import { Router } from '@angular/router';
import { LoginRequest } from '../models';

describe('AuthService', () => {
  let service: AuthService;
  let httpMock: HttpTestingController;
  let router: jasmine.SpyObj<Router>;

  beforeEach(() => {
    const routerSpy = jasmine.createSpyObj('Router', ['navigate']);

    TestBed.configureTestingModule({
      imports: [HttpClientTestingModule],
      providers: [
        AuthService,
        { provide: Router, useValue: routerSpy }
      ]
    });

    service = TestBed.inject(AuthService);
    httpMock = TestBed.inject(HttpTestingController);
    router = TestBed.inject(Router) as jasmine.SpyObj<Router>;
  });

  afterEach(() => {
    httpMock.verify();
    localStorage.clear();
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });

  it('should login successfully', () => {
    const loginRequest: LoginRequest = {
      email: 'test@test.com',
      password: 'password'
    };

    const mockLoginResponse = {
      user: { 
        id: 1, 
        email: 'test@test.com', 
        name: 'Test User',
        created_at: '2024-01-01',
        updated_at: '2024-01-01',
        permissions: [{ id: 1, name: 'read-expedientes', guard_name: 'web' }],
        roles: [{ id: 1, name: 'user', guard_name: 'web' }]
      },
      token: 'test-token'
    };

    service.login(loginRequest).subscribe(response => {
      expect(response.id).toBe(1);
      expect(response.email).toBe('test@test.com');
      expect(service.isAuthenticated()).toBe(true);
      expect(service.currentUser()?.id).toBe(1);
    });

    const req = httpMock.expectOne(request => 
      request.url.includes('/login') && request.method === 'POST'
    );
    req.flush(mockLoginResponse);
  });

  it('should logout successfully', () => {
    // Setup authenticated state
    localStorage.setItem('auth_token', 'test-token');

    service.logout().subscribe(() => {
      expect(localStorage.getItem('auth_token')).toBeNull();
      expect(service.isAuthenticated()).toBe(false);
    });

    const req = httpMock.expectOne(request => 
      request.url.includes('/logout') && request.method === 'POST'
    );
    req.flush({});
  });

  it('should check permissions correctly', () => {
    // Mock user with permissions
    const mockUser = {
      id: 1,
      name: 'Test User',
      email: 'test@test.com',
      created_at: '2024-01-01',
      updated_at: '2024-01-01',
      permissions: [
        { id: 1, name: 'read-expedientes', guard_name: 'web' },
        { id: 2, name: 'create-expedientes', guard_name: 'web' }
      ],
      roles: [{ id: 1, name: 'user', guard_name: 'web' }]
    };

    // Set user in service
    (service as any).setUser(mockUser);

    expect(service.hasPermission('read-expedientes')).toBe(true);
    expect(service.hasPermission('delete-expedientes')).toBe(false);
    expect(service.hasAnyPermission(['read-expedientes', 'create-expedientes'])).toBe(true);
    expect(service.hasAllPermissions(['read-expedientes', 'create-expedientes'])).toBe(true);
    expect(service.hasAllPermissions(['read-expedientes', 'delete-expedientes'])).toBe(false);
  });
});