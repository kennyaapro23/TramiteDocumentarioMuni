import { Component, inject, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators, FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../../../core/services/auth.service';
import { LoginRequest } from '../../../../shared/models';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  private readonly fb = inject(FormBuilder);
  private readonly authService = inject(AuthService);
  private readonly router = inject(Router);

  // Signals for reactive state
  public readonly isLoading = this.authService.isLoading;
  
  // Component state
  public showPassword = false;
  public rememberMe = false;
  public errorMessage = '';
  public appVersion = '1.0.0';

  // Reactive form
  public loginForm: FormGroup;

  constructor() {
    this.loginForm = this.createLoginForm();
    
    // Auto-redirect if already authenticated
    if (this.authService.isAuthenticated()) {
      this.router.navigate(['/dashboard']);
    }
  }

  /**
   * Create and configure the login form
   */
  private createLoginForm(): FormGroup {
    return this.fb.group({
      email: [
        '', 
        [
          Validators.required, 
          Validators.email,
          Validators.maxLength(255)
        ]
      ],
      password: [
        '', 
        [
          Validators.required, 
          Validators.minLength(6),
          Validators.maxLength(255)
        ]
      ]
    });
  }

  /**
   * Handle form submission
   */
  public onSubmit(): void {
    if (this.loginForm.invalid || this.isLoading()) {
      this.markFormGroupTouched();
      return;
    }

    this.clearErrorMessage();
    const credentials: LoginRequest = this.loginForm.value;

    this.authService.login(credentials).subscribe({
      next: (user) => {
        if (user) {
          this.handleSuccessfulLogin();
        } else {
          this.handleLoginError('Error en el inicio de sesión');
        }
      },
      error: (error) => {
        this.handleLoginError(this.getErrorMessage(error));
      }
    });
  }

  /**
   * Handle successful login
   */
  private handleSuccessfulLogin(): void {
    this.clearErrorMessage();
    this.router.navigate(['/dashboard'], { 
      replaceUrl: true 
    });
  }

  /**
   * Handle login error
   */
  private handleLoginError(message: string): void {
    this.errorMessage = message;
    this.loginForm.patchValue({ password: '' });
    
    // Auto-clear error after 5 seconds
    setTimeout(() => {
      this.clearErrorMessage();
    }, 5000);
  }

  /**
   * Extract error message from API response
   */
  private getErrorMessage(error: any): string {
    if (error?.error?.message) {
      return error.error.message;
    }
    
    if (error?.error?.errors) {
      const firstError = Object.values(error.error.errors)[0];
      return Array.isArray(firstError) ? firstError[0] : 'Error de validación';
    }

    switch (error?.status) {
      case 401:
        return 'Credenciales incorrectas. Por favor, verifica tu email y contraseña.';
      case 422:
        return 'Datos de acceso inválidos. Revisa la información ingresada.';
      case 429:
        return 'Demasiados intentos de acceso. Intenta nuevamente en unos minutos.';
      case 500:
        return 'Error del servidor. Intenta nuevamente más tarde.';
      case 0:
        return 'No se pudo conectar con el servidor. Verifica tu conexión a internet.';
      default:
        return 'Error inesperado. Intenta nuevamente.';
    }
  }

  /**
   * Toggle password visibility
   */
  public togglePassword(): void {
    this.showPassword = !this.showPassword;
  }

  /**
   * Set demo credentials for testing
   */
  public setCredentials(email: string, password: string): void {
    this.loginForm.patchValue({ email, password });
    this.clearErrorMessage();
  }

  /**
   * Clear error message
   */
  private clearErrorMessage(): void {
    this.errorMessage = '';
  }

  /**
   * Mark all form controls as touched for validation display
   */
  private markFormGroupTouched(): void {
    Object.keys(this.loginForm.controls).forEach(key => {
      const control = this.loginForm.get(key);
      control?.markAsTouched();
    });
  }

  /**
   * Getter for email form control
   */
  get email() {
    return this.loginForm.get('email');
  }

  /**
   * Getter for password form control
   */
  get password() {
    return this.loginForm.get('password');
  }

  /**
   * Check if form has specific error
   */
  public hasError(controlName: string, errorType: string): boolean {
    const control = this.loginForm.get(controlName);
    return !!(control?.hasError(errorType) && control?.touched);
  }

  /**
   * Get specific error message for a control
   */
  public getControlError(controlName: string): string {
    const control = this.loginForm.get(controlName);
    
    if (!control?.errors || !control.touched) {
      return '';
    }

    const errors = control.errors;

    if (errors['required']) {
      return `${controlName === 'email' ? 'El correo' : 'La contraseña'} es requerido`;
    }

    if (errors['email']) {
      return 'Ingrese un correo electrónico válido';
    }

    if (errors['minlength']) {
      const minLength = errors['minlength'].requiredLength;
      return `Mínimo ${minLength} caracteres`;
    }

    if (errors['maxlength']) {
      const maxLength = errors['maxlength'].requiredLength;
      return `Máximo ${maxLength} caracteres`;
    }

    return 'Campo inválido';
  }

  /**
   * Handle keyboard shortcuts
   */
  public onKeydown(event: KeyboardEvent): void {
    // Submit form on Ctrl+Enter
    if (event.ctrlKey && event.key === 'Enter') {
      event.preventDefault();
      this.onSubmit();
    }
  }

  /**
   * Handle forgot password link
   */
  public onForgotPassword(event: Event): void {
    event.preventDefault();
    // TODO: Implement forgot password functionality
    alert('Funcionalidad de recuperación de contraseña próximamente disponible.\n\nPor ahora, contacte al administrador del sistema.');
  }
}