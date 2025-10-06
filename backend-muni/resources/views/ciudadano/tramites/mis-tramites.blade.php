@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Mis Trámites</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item active">Mis Trámites</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5>Total de solicitudes: <span class="badge bg-primary">{{ $expedientes->total() }}</span></h5>
                </div>
                <div>
                    <a href="{{ route('ciudadano.tramites.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nueva Solicitud
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @forelse($expedientes as $expediente)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2">
                                            <i class="fas fa-file-alt"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">
                                        <a href="{{ route('ciudadano.tramites.show', $expediente->id) }}" class="text-dark">
                                            {{ $expediente->asunto }}
                                        </a>
                                    </h5>
                                    <p class="text-muted mb-2">
                                        <span class="badge bg-secondary">{{ $expediente->numero_expediente }}</span>
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-file-alt me-1"></i>
                                        {{ $expediente->tipoTramite->nombre ?? 'N/A' }}
                                    </p>
                                    <p class="text-muted mb-0">
                                        <small>{{ Str::limit($expediente->descripcion, 150) }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-md-end mt-3 mt-md-0">
                                @php
                                    $estadoColors = [
                                        'ingresado' => 'primary',
                                        'en_proceso' => 'info',
                                        'observado' => 'warning',
                                        'aprobado' => 'success',
                                        'rechazado' => 'danger',
                                        'finalizado' => 'secondary'
                                    ];
                                    $color = $estadoColors[$expediente->estado] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} mb-2">
                                    {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                                </span>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $expediente->fecha_ingreso->format('d/m/Y') }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-building me-1"></i>
                                    {{ $expediente->gerencia->nombre ?? 'Sin asignar' }}
                                </small>
                                <br>
                                @if($expediente->documentos->count() > 0)
                                <small class="text-muted">
                                    <i class="fas fa-paperclip me-1"></i>
                                    {{ $expediente->documentos->count() }} documento(s)
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($expediente->requiere_pago && !$expediente->pagado)
                            <span class="badge bg-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Pago pendiente: S/. {{ number_format($expediente->monto, 2) }}
                            </span>
                            @elseif($expediente->pagado)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Pagado
                            </span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('ciudadano.tramites.show', $expediente->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i>Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No tiene solicitudes registradas</h5>
                    <p class="text-muted mb-3">Comience solicitando un nuevo trámite</p>
                    <a href="{{ route('ciudadano.tramites.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Solicitar Trámite
                    </a>
                </div>
            </div>
            @endforelse

            @if($expedientes->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $expedientes->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15)!important;
}
</style>
@endsection
