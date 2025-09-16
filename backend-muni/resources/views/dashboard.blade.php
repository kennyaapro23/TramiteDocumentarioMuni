@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Total Expedientes</div>
                            <div class="h2 text-white">{{ $stats['expedientes_total'] }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-folder-open fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Pendientes</div>
                            <div class="h2 text-white">{{ $stats['expedientes_pendientes'] }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Usuarios Activos</div>
                            <div class="h2 text-white">{{ $stats['usuarios_activos'] }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-white-50 small">Workflows Activos</div>
                            <div class="h2 text-white">{{ $stats['workflows_activos'] }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-project-diagram fa-2x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Expedientes Recientes -->
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Expedientes Recientes
                    </h5>
                    <a href="{{ route('expedientes.index') }}" class="btn btn-sm btn-outline-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Gerencia</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expedientes_recientes as $expediente)
                                <tr>
                                    <td>
                                        <strong>{{ $expediente->numero_expediente }}</strong>
                                    </td>
                                    <td>{{ Str::limit($expediente->asunto, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $expediente->estado == 'pendiente' ? 'warning' : ($expediente->estado == 'aprobado' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($expediente->estado) }}
                                        </span>
                                    </td>
                                    <td>{{ $expediente->gerencia->nombre ?? 'Sin asignar' }}</td>
                                    <td>{{ $expediente->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        No hay expedientes recientes
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de Accesos Rápidos -->
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Accesos Rápidos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('expedientes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Nuevo Expediente
                        </a>
                        <a href="{{ route('workflows.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-sitemap me-2"></i>Crear Workflow
                        </a>
                        <a href="{{ route('usuarios.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                        </a>
                        <a href="{{ route('gerencias.create') }}" class="btn btn-outline-info">
                            <i class="fas fa-building me-2"></i>Nueva Gerencia
                        </a>
                        <a href="{{ route('reportes.index') }}" class="btn btn-outline-warning">
                            <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resumen del Sistema -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Resumen del Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $stats['gerencias_total'] }}</h4>
                                <small class="text-muted">Gerencias</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success">{{ $stats['workflows_total'] }}</h4>
                            <small class="text-muted">Workflows</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-info">{{ $stats['usuarios_total'] }}</h4>
                                <small class="text-muted">Usuarios</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning">{{ $stats['mesa_partes_hoy'] }}</h4>
                            <small class="text-muted">Docs. Hoy</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Estadísticas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Estadísticas Mensuales
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statsChart" width="100" height="30"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Configurar gráfico de estadísticas
    const ctx = document.getElementById('statsChart').getContext('2d');
    const statsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Expedientes Creados',
                data: [12, 19, 8, 15, 25, 22],
                borderColor: 'rgb(52, 152, 219)',
                backgroundColor: 'rgba(52, 152, 219, 0.1)',
                tension: 0.4
            }, {
                label: 'Expedientes Resueltos',
                data: [8, 15, 12, 18, 20, 25],
                borderColor: 'rgb(76, 175, 80)',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush