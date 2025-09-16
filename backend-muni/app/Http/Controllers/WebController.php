<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Expediente;
use App\Models\Gerencia;
use App\Models\CustomWorkflow;
use App\Models\MesaParte;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class WebController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['login', 'showLogin']);
    }

    // Página de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Dashboard principal
    public function dashboard()
    {
        $stats = [
            'expedientes_total' => Expediente::count(),
            'expedientes_pendientes' => Expediente::where('estado', 'pendiente')->count(),
            'usuarios_total' => User::count(),
            'usuarios_activos' => User::where('activo', true)->count(),
            'gerencias_total' => Gerencia::count(),
            'workflows_total' => CustomWorkflow::count(),
            'workflows_activos' => CustomWorkflow::where('activo', true)->count(),
            'mesa_partes_hoy' => MesaParte::whereDate('created_at', today())->count(),
        ];

        $expedientes_recientes = Expediente::with(['user', 'gerencia'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'expedientes_recientes'));
    }

    // Gestión de usuarios
    public function usuarios()
    {
        $usuarios = User::with('roles', 'gerencias')->paginate(20);
        $roles = Role::all();
        $gerencias = Gerencia::all();
        return view('usuarios.index', compact('usuarios', 'roles', 'gerencias'));
    }

    // Gestión de roles
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permisos = Permission::all();
        return view('roles.index', compact('roles', 'permisos'));
    }

    // Gestión de gerencias
    public function gerencias()
    {
        $gerencias = Gerencia::with('parent', 'children', 'users')->get();
        return view('gerencias.index', compact('gerencias'));
    }

    // Gestión de expedientes
    public function expedientes()
    {
        $expedientes = Expediente::with(['user', 'gerencia', 'customWorkflow'])
            ->paginate(20);
        $gerencias = Gerencia::all();
        $workflows = CustomWorkflow::where('activo', true)->get();
        return view('expedientes.index', compact('expedientes', 'gerencias', 'workflows'));
    }

    // Gestión de workflows
    public function workflows()
    {
        $workflows = CustomWorkflow::with(['steps', 'creator'])->get();
        return view('workflows.index', compact('workflows'));
    }

    // Editor de workflow
    public function workflowEditor($id = null)
    {
        $workflow = $id ? CustomWorkflow::with(['steps.transitionsFrom', 'steps.transitionsTo'])->findOrFail($id) : null;
        return view('workflows.editor', compact('workflow'));
    }

    // Mesa de partes
    public function mesaPartes()
    {
        $documentos = MesaParte::with(['tipoDocumento', 'tipoTramite', 'asignado'])
            ->paginate(20);
        return view('mesa-partes.index', compact('documentos'));
    }

    // Reportes
    public function reportes()
    {
        return view('reportes.index');
    }
}
