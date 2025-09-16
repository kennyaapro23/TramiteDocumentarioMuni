@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Expedientes</h4>
                    @can('registrar_expediente')
                        <a href="{{ route('expedientes.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Expediente
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Solicitante</th>
                                    <th>Asunto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expedientes as $expediente)
                                    <tr>
                                        <td>{{ $expediente->numero }}</td>
                                        <td>{{ $expediente->solicitante }}</td>
                                        <td>{{ $expediente->asunto }}</td>
                                        <td>
                                            <span class="badge bg-{{ $expediente->estado_color }}">
                                                {{ $expediente->estado }}
                                            </span>
                                        </td>
                                        <td>{{ $expediente->fecha->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('ver_expedientes')
                                                    <a href="{{ route('expedientes.show', $expediente) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan

                                                @can('editar_expedientes')
                                                    <a href="{{ route('expedientes.edit', $expediente) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('derivar_expediente')
                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                            onclick="derivarExpediente({{ $expediente->id }})">
                                                        <i class="fas fa-share"></i> Derivar
                                                    </button>
                                                @endcan

                                                @can('revision_tecnica')
                                                    <button type="button" class="btn btn-sm btn-info"
                                                            onclick="revisionTecnica({{ $expediente->id }})">
                                                        <i class="fas fa-tools"></i> Revisión Técnica
                                                    </button>
                                                @endcan

                                                @can('revision_legal')
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="revisionLegal({{ $expediente->id }})">
                                                        <i class="fas fa-gavel"></i> Revisión Legal
                                                    </button>
                                                @endcan

                                                @can('emitir_resolucion')
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            onclick="emitirResolucion({{ $expediente->id }})">
                                                        <i class="fas fa-file-signature"></i> Emitir Resolución
                                                    </button>
                                                @endcan

                                                @can('firma_resolucion_mayor')
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            onclick="firmaResolucion({{ $expediente->id }})">
                                                        <i class="fas fa-signature"></i> Firmar Resolución
                                                    </button>
                                                @endcan

                                                @can('eliminar_expedientes')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="eliminarExpediente({{ $expediente->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No hay expedientes registrados</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Funciones para las acciones de expedientes
    function derivarExpediente(id) {
        if (confirm('¿Está seguro de derivar este expediente?')) {
            fetch(`/expedientes/${id}/derivar`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function revisionTecnica(id) {
        if (confirm('¿Confirmar revisión técnica?')) {
            fetch(`/expedientes/${id}/revision-tecnica`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function revisionLegal(id) {
        if (confirm('¿Confirmar revisión legal?')) {
            fetch(`/expedientes/${id}/revision-legal`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function emitirResolucion(id) {
        if (confirm('¿Confirmar emisión de resolución?')) {
            fetch(`/expedientes/${id}/emitir-resolucion`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function firmaResolucion(id) {
        if (confirm('¿Confirmar firma de resolución?')) {
            fetch(`/expedientes/${id}/firma-resolucion`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function eliminarExpediente(id) {
        if (confirm('¿Está seguro de eliminar este expediente? Esta acción no se puede deshacer.')) {
            fetch(`/expedientes/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush
@endsection
