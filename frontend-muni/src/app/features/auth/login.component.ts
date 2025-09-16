import { Component } from '@angular/core';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [],
  template: `
    <div style="padding: 50px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; color: white;">
      <h1 style="font-size: 3rem; margin-bottom: 20px;">¡LOGIN FUNCIONA!</h1>
      <h2 style="font-size: 2rem; margin-bottom: 40px;">Sistema Municipal</h2>
      <div style="background: white; color: black; padding: 40px; border-radius: 10px; max-width: 400px; margin: 0 auto;">
        <h3>Formulario de Login</h3>
        <p>Email: <input type="email" style="width: 100%; padding: 10px; margin: 10px 0;"></p>
        <p>Password: <input type="password" style="width: 100%; padding: 10px; margin: 10px 0;"></p>
        <button style="background: #667eea; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">
          Iniciar Sesión
        </button>
      </div>
    </div>
  `
})
export class LoginComponent {}