<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Expediente;
use Illuminate\Support\Facades\DB;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['showLogin', 'login']);
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Si es una request AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        // Si es una request AJAX, devolver JSON con errores
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
                'errors' => [
                    'email' => ['Las credenciales proporcionadas no coinciden con nuestros registros.']
                ]
            ], 422);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_expedientes' => 0,
            'expedientes_pendientes' => 0,
            'expedientes_completados' => 0,
            'usuarios_activos' => User::count(),
        ];

        $recent_expedientes = collect();

        return view('dashboard', compact('stats', 'recent_expedientes'));
    }

    /**
     * Show users management
     */
    public function usuarios()
    {
        return view('usuarios.index');
    }

    /**
     * Show expedientes
     */
    public function expedientes()
    {
        return view('expedientes.index');
    }

    /**
     * Show roles management
     */
    public function roles()
    {
        return view('roles.index');
    }

    /**
     * Show create expediente
     */
    public function createExpediente()
    {
        return view('expedientes.create');
    }

    /**
     * Show permissions
     */
    public function permisos()
    {
        // Obtener todos los permisos agrupados por módulo
        $permisos = \Spatie\Permission\Models\Permission::all();
        
        // Agrupar permisos por módulo (primera parte antes del punto)
        $permisosPorModulo = $permisos->groupBy(function($permiso) {
            $parts = explode('.', $permiso->name);
            return $parts[0] ?? 'otros';
        });
        
        // Estadísticas
        $stats = [
            'total_permisos' => $permisos->count(),
            'permisos_activos' => $permisos->count(), // Todos activos por defecto
            'total_modulos' => $permisosPorModulo->count(),
            'permisos_criticos' => $permisos->filter(function($permiso) {
                return str_contains($permiso->name, 'delete') || str_contains($permiso->name, 'admin');
            })->count()
        ];
        
        return view('permisos.index', compact('permisosPorModulo', 'stats'));
    }

    /**
     * Show edit expediente
     */
    public function editExpediente($id)
    {
        try {
            $expediente = Expediente::findOrFail($id);
            return view('expedientes.edit', compact('expediente'));
        } catch (\Exception $e) {
            return redirect()->route('expedientes.index')->with('error', 'Expediente no encontrado.');
        }
    }

    /**
     * Show mesa de partes
     */
    public function mesaPartes()
    {
        return view('mesa-partes.index');
    }

    /**
     * Show administracion
     */
    public function administracion()
    {
        return view('administracion.index');
    }

    /**
     * Show reportes
     */
    public function reportes()
    {
        return view('reportes.index');
    }

    /**
     * Show configuration
     */
    public function configuracion()
    {
        return view('configuracion.index');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        return view('profile.show');
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado exitosamente.');
    }

    /**
     * Show welcome page (for testing routes)
     */
    public function welcome()
    {
        return view('welcome');
    }

    // Mesa de Partes methods
    public function mesaPartesDerivacion()
    {
        return view('mesa-partes.derivacion');
    }

    public function mesaPartesRegistro()
    {
        return view('mesa-partes.registro');
    }

    // Expedientes filtering methods
    public function expedientesPendientes()
    {
        return view('expedientes.pendientes');
    }

    public function expedientesProceso()
    {
        return view('expedientes.proceso');
    }

    public function expedientesFinalizados()
    {
        return view('expedientes.finalizados');
    }

    // Reportes methods
    public function reportesExpedientes()
    {
        return view('reportes.expedientes');
    }

    public function reportesTramites()
    {
        return view('reportes.tramites');
    }

    public function reportesTiempos()
    {
        return view('reportes.tiempos');
    }

    // Settings method
    public function settings()
    {
        return view('settings.index');
    }
}
