@extends('layouts.app')

@section('title', 'Gestión de Expedientes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-folder-open me-2"></i>Gestión de Expedientes
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#expedienteModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Expediente
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchExpedientes" placeholder="Buscar expedientes...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterEstado">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="observado">Observado</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterGerencia">
                                <option value="">Todas las gerencias</option>
                                @foreach($gerencias as $gerencia)
                                <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterTipoTramite">
                                <option value="">Todos los trámites</option>
                                @foreach($tipos_tramite as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="filterFecha" placeholder="Fecha">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de Expedientes -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="expedientesTable">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Asunto</th>
                                    <th>Solicitante</th>
                                    <th>Tipo Trámite</th>
                                    <th>Gerencia</th>
                                    <th>Estado</th>
                                    <th>Prioridad</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expedientes as $expediente)
                                <tr data-expediente-id="{{ $expediente->id }}" 
                                    data-estado="{{ $expediente->estado }}" 
                                    data-gerencia="{{ $expediente->gerencia_id }}"
                                    data-tipo="{{ $expediente->tipo_tramite_id }}">
                                    <td>
                                        <strong>{{ $expediente->numero_expediente }}</strong>
                                        @if($expediente->es_urgente)
                                        <i class="fas fa-exclamation-triangle text-danger ms-1" title="Urgente"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($expediente->asunto, 40) }}</strong>
                                        @if($expediente->descripcion)
                                        <br><small class="text-muted">{{ Str::limit($expediente->descripcion, 60) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $expediente->solicitante_nombre }}</strong>
                                            <br><small class="text-muted">{{ $expediente->solicitante_email }}</small>
                                            @if($expediente->solicitante_telefono)
                                            <br><small class="text-muted">{{ $expediente->solicitante_telefono }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $expediente->tipoTramite->nombre ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $expediente->gerencia->nombre ?? 'Sin asignar' }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $expediente->estado == 'pendiente' ? 'warning' : 
                                            ($expediente->estado == 'en_proceso' ? 'primary' : 
                                            ($expediente->estado == 'observado' ? 'danger' : 
                                            ($expediente->estado == 'aprobado' ? 'success' : 
                                            ($expediente->estado == 'rechazado' ? 'dark' : 'secondary')))) }}">
                                            {{ ucfirst(str_replace('_', ' ', $expediente->estado)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $expediente->prioridad == 'alta' ? 'danger' : 
                                            ($expediente->prioridad == 'media' ? 'warning' : 'success') }}">
                                            {{ ucfirst($expediente->prioridad) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $expediente->created_at->format('d/m/Y') }}
                                        <br><small class="text-muted">{{ $expediente->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewExpediente({{ $expediente->id }})" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="editExpediente({{ $expediente->id }})" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="trackExpediente({{ $expediente->id }})" title="Seguimiento">
                                                <i class="fas fa-route"></i>
                                            </button>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" onclick="changeStatus({{ $expediente->id }})">
                                                        <i class="fas fa-exchange-alt me-2"></i>Cambiar Estado
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="assignUser({{ $expediente->id }})">
                                                        <i class="fas fa-user-plus me-2"></i>Asignar Usuario
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="addDocument({{ $expediente->id }})">
                                                        <i class="fas fa-file-plus me-2"></i>Agregar Documento
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteExpediente({{ $expediente->id }})">
                                                        <i class="fas fa-trash me-2"></i>Eliminar
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No hay expedientes registrados</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($expedientes->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $expedientes->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Expediente -->
<div class="modal fade" id="expedienteModal" tabindex="-1" aria-labelledby="expedienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="expedienteForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="expedienteModalLabel">Nuevo Expediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="expedienteId" name="id">
                    
                    <!-- Información del Expediente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expedienteAsunto" class="form-label">Asunto *</label>
                                <input type="text" class="form-control" id="expedienteAsunto" name="asunto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expedienteTipoTramite" class="form-label">Tipo de Trámite *</label>
                                <select class="form-select" id="expedienteTipoTramite" name="tipo_tramite_id" required>
                                    <option value="">Seleccionar tipo de trámite</option>
                                    @foreach($tipos_tramite as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="expedienteDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="expedienteDescripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <!-- Información del Solicitante -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-user me-2"></i>Información del Solicitante</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="solicitanteNombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="solicitanteNombre" name="solicitante_nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="solicitanteEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="solicitanteEmail" name="solicitante_email" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="solicitanteTelefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="solicitanteTelefono" name="solicitante_telefono">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="solicitanteDni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="solicitanteDni" name="solicitante_dni" maxlength="8">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="solicitanteDireccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="solicitanteDireccion" name="solicitante_direccion">
                            </div>
                        </div>
                    </div>

                    <!-- Configuración del Expediente -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-cogs me-2"></i>Configuración</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expedienteGerencia" class="form-label">Gerencia Asignada</label>
                                <select class="form-select" id="expedienteGerencia" name="gerencia_id">
                                    <option value="">Seleccionar gerencia</option>
                                    @foreach($gerencias as $gerencia)
                                    <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expedientePrioridad" class="form-label">Prioridad</label>
                                <select class="form-select" id="expedientePrioridad" name="prioridad">
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="expedienteWorkflow" class="form-label">Workflow</label>
                                <select class="form-select" id="expedienteWorkflow" name="workflow_id">
                                    <option value="">Sin workflow específico</option>
                                    @foreach($workflows as $workflow)
                                    <option value="{{ $workflow->id }}">{{ $workflow->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="expedienteUrgente" name="es_urgente" value="1">
                                <label class="form-check-label" for="expedienteUrgente">
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>Marcar como urgente
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="expedienteConfidencial" name="es_confidencial" value="1">
                                <label class="form-check-label" for="expedienteConfidencial">
                                    <i class="fas fa-lock text-danger me-1"></i>Marcar como confidencial
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos -->
                    <hr>
                    <h6 class="mb-3"><i class="fas fa-paperclip me-2"></i>Documentos Adjuntos</h6>
                    
                    <div class="mb-3">
                        <label for="expedienteDocumentos" class="form-label">Seleccionar Archivos</label>
                        <input type="file" class="form-control" id="expedienteDocumentos" name="documentos[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Formatos permitidos: PDF, DOC, DOCX, JPG, PNG. Máximo 10MB por archivo.</small>
                    </div>

                    <div id="documentosList" class="mb-3" style="display: none;">
                        <label class="form-label">Archivos Seleccionados:</label>
                        <div id="documentosPreview"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Expediente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles del Expediente -->
<div class="modal fade" id="expedienteViewModal" tabindex="-1" aria-labelledby="expedienteViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expedienteViewModalLabel">Detalles del Expediente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="expedienteViewContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Seguimiento del Expediente -->
<div class="modal fade" id="trackingModal" tabindex="-1" aria-labelledby="trackingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackingModalLabel">Seguimiento del Expediente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="trackingContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Cambiar Estado -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Cambiar Estado del Expediente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="statusExpedienteId" name="expediente_id">
                    
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado *</label>
                        <select class="form-select" id="nuevoEstado" name="estado" required>
                            <option value="">Seleccionar estado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="observado">Observado</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="finalizado">Finalizado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comentarioEstado" class="form-label">Comentario *</label>
                        <textarea class="form-control" id="comentarioEstado" name="comentario" rows="3" required 
                                  placeholder="Describa el motivo del cambio de estado..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Cambiar Estado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let expedienteModal, expedienteViewModal, trackingModal, statusModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar modales
        expedienteModal = new bootstrap.Modal(document.getElementById('expedienteModal'));
        expedienteViewModal = new bootstrap.Modal(document.getElementById('expedienteViewModal'));
        trackingModal = new bootstrap.Modal(document.getElementById('trackingModal'));
        statusModal = new bootstrap.Modal(document.getElementById('statusModal'));

        setupFilters();
        setupForms();
        setupFileHandling();
    });

    function setupFilters() {
        const searchInput = document.getElementById('searchExpedientes');
        const estadoFilter = document.getElementById('filterEstado');
        const gerenciaFilter = document.getElementById('filterGerencia');
        const tipoFilter = document.getElementById('filterTipoTramite');
        const fechaFilter = document.getElementById('filterFecha');

        [searchInput, estadoFilter, gerenciaFilter, tipoFilter, fechaFilter].forEach(element => {
            element.addEventListener('input', filterExpedientes);
        });
    }

    function filterExpedientes() {
        const searchTerm = document.getElementById('searchExpedientes').value.toLowerCase();
        const estadoFilter = document.getElementById('filterEstado').value;
        const gerenciaFilter = document.getElementById('filterGerencia').value;
        const tipoFilter = document.getElementById('filterTipoTramite').value;
        const fechaFilter = document.getElementById('filterFecha').value;

        const rows = document.querySelectorAll('#expedientesTable tbody tr[data-expediente-id]');

        rows.forEach(row => {
            const numero = row.cells[0].textContent.toLowerCase();
            const asunto = row.cells[1].textContent.toLowerCase();
            const solicitante = row.cells[2].textContent.toLowerCase();
            const estado = row.dataset.estado;
            const gerencia = row.dataset.gerencia;
            const tipo = row.dataset.tipo;
            const fecha = row.cells[7].textContent;

            const matchesSearch = numero.includes(searchTerm) || asunto.includes(searchTerm) || solicitante.includes(searchTerm);
            const matchesEstado = !estadoFilter || estado === estadoFilter;
            const matchesGerencia = !gerenciaFilter || gerencia === gerenciaFilter;
            const matchesTipo = !tipoFilter || tipo === tipoFilter;
            const matchesFecha = !fechaFilter || fecha.includes(fechaFilter.split('-').reverse().join('/'));

            row.style.display = (matchesSearch && matchesEstado && matchesGerencia && matchesTipo && matchesFecha) ? '' : 'none';
        });
    }

    function clearFilters() {
        document.getElementById('searchExpedientes').value = '';
        document.getElementById('filterEstado').value = '';
        document.getElementById('filterGerencia').value = '';
        document.getElementById('filterTipoTramite').value = '';
        document.getElementById('filterFecha').value = '';
        filterExpedientes();
    }

    function setupForms() {
        document.getElementById('expedienteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveExpediente();
        });

        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveStatusChange();
        });
    }

    function setupFileHandling() {
        document.getElementById('expedienteDocumentos').addEventListener('change', function(e) {
            const files = e.target.files;
            const preview = document.getElementById('documentosPreview');
            const list = document.getElementById('documentosList');

            if (files.length > 0) {
                list.style.display = 'block';
                preview.innerHTML = '';

                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'alert alert-info d-flex justify-content-between align-items-center';
                    fileItem.innerHTML = `
                        <div>
                            <i class="fas fa-file me-2"></i>
                            <strong>${file.name}</strong>
                            <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(fileItem);
                });
            } else {
                list.style.display = 'none';
            }
        });
    }

    function removeFile(index) {
        const input = document.getElementById('expedienteDocumentos');
        const dt = new DataTransfer();
        const files = input.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }

        input.files = dt.files;
        input.dispatchEvent(new Event('change'));
    }

    function editExpediente(expedienteId) {
        fetch(`/api/expedientes/${expedienteId}`)
            .then(response => response.json())
            .then(expediente => {
                document.getElementById('expedienteModalLabel').textContent = 'Editar Expediente';
                document.getElementById('expedienteId').value = expediente.id;
                document.getElementById('expedienteAsunto').value = expediente.asunto;
                document.getElementById('expedienteDescripcion').value = expediente.descripcion || '';
                document.getElementById('expedienteTipoTramite').value = expediente.tipo_tramite_id || '';
                document.getElementById('solicitanteNombre').value = expediente.solicitante_nombre;
                document.getElementById('solicitanteEmail').value = expediente.solicitante_email;
                document.getElementById('solicitanteTelefono').value = expediente.solicitante_telefono || '';
                document.getElementById('solicitanteDni').value = expediente.solicitante_dni || '';
                document.getElementById('solicitanteDireccion').value = expediente.solicitante_direccion || '';
                document.getElementById('expedienteGerencia').value = expediente.gerencia_id || '';
                document.getElementById('expedientePrioridad').value = expediente.prioridad;
                document.getElementById('expedienteWorkflow').value = expediente.workflow_id || '';
                document.getElementById('expedienteUrgente').checked = expediente.es_urgente;
                document.getElementById('expedienteConfidencial').checked = expediente.es_confidencial;

                expedienteModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del expediente', 'danger');
            });
    }

    function viewExpediente(expedienteId) {
        fetch(`/api/expedientes/${expedienteId}/detalles`)
            .then(response => response.json())
            .then(expediente => {
                const content = `
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-folder-open me-2"></i>
                                        Expediente ${expediente.numero_expediente}
                                        ${expediente.es_urgente ? '<i class="fas fa-exclamation-triangle text-danger ms-2" title="Urgente"></i>' : ''}
                                        ${expediente.es_confidencial ? '<i class="fas fa-lock text-warning ms-2" title="Confidencial"></i>' : ''}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <h5>${expediente.asunto}</h5>
                                    ${expediente.descripcion ? '<p class="text-muted">' + expediente.descripcion + '</p>' : ''}
                                    
                                    <table class="table table-borderless">
                                        <tr><th width="30%">Tipo de Trámite:</th><td>${expediente.tipo_tramite ? expediente.tipo_tramite.nombre : 'N/A'}</td></tr>
                                        <tr><th>Estado:</th><td><span class="badge bg-primary">${expediente.estado}</span></td></tr>
                                        <tr><th>Prioridad:</th><td><span class="badge bg-warning">${expediente.prioridad}</span></td></tr>
                                        <tr><th>Gerencia:</th><td>${expediente.gerencia ? expediente.gerencia.nombre : 'Sin asignar'}</td></tr>
                                        <tr><th>Workflow:</th><td>${expediente.workflow ? expediente.workflow.nombre : 'Sin workflow'}</td></tr>
                                        <tr><th>Creado:</th><td>${expediente.created_at}</td></tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0"><i class="fas fa-user me-2"></i>Información del Solicitante</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><th width="30%">Nombre:</th><td>${expediente.solicitante_nombre}</td></tr>
                                        <tr><th>Email:</th><td>${expediente.solicitante_email}</td></tr>
                                        <tr><th>Teléfono:</th><td>${expediente.solicitante_telefono || 'No especificado'}</td></tr>
                                        <tr><th>DNI:</th><td>${expediente.solicitante_dni || 'No especificado'}</td></tr>
                                        <tr><th>Dirección:</th><td>${expediente.solicitante_direccion || 'No especificada'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0"><i class="fas fa-chart-pie me-2"></i>Estadísticas</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-primary">${expediente.documentos_count || 0}</h4>
                                            <small class="text-muted">Documentos</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success">${expediente.historial_count || 0}</h4>
                                            <small class="text-muted">Movimientos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            ${expediente.documentos && expediente.documentos.length > 0 ? `
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0"><i class="fas fa-paperclip me-2"></i>Documentos Adjuntos</h6>
                                </div>
                                <div class="card-body">
                                    ${expediente.documentos.map(doc => `
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <i class="fas fa-file me-2"></i>
                                                <small>${doc.nombre_original}</small>
                                            </div>
                                            <a href="/api/expedientes/${expediente.id}/documentos/${doc.id}/download" 
                                               class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                document.getElementById('expedienteViewContent').innerHTML = content;
                expedienteViewModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los detalles del expediente', 'danger');
            });
    }

    function trackExpediente(expedienteId) {
        fetch(`/api/expedientes/${expedienteId}/seguimiento`)
            .then(response => response.json())
            .then(data => {
                const content = `
                    <div class="timeline">
                        ${data.historial.map(item => `
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">${item.accion}</h6>
                                    <p class="timeline-description">${item.descripcion}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>${item.usuario ? item.usuario.name : 'Sistema'}
                                        <i class="fas fa-clock ms-3 me-1"></i>${item.created_at}
                                    </small>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                document.getElementById('trackingContent').innerHTML = content;
                trackingModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar el seguimiento del expediente', 'danger');
            });
    }

    function changeStatus(expedienteId) {
        document.getElementById('statusExpedienteId').value = expedienteId;
        document.getElementById('statusForm').reset();
        statusModal.show();
    }

    function assignUser(expedienteId) {
        // Implementar asignación de usuario
        showAlert('Funcionalidad en desarrollo', 'info');
    }

    function addDocument(expedienteId) {
        // Implementar agregar documento
        showAlert('Funcionalidad en desarrollo', 'info');
    }

    function deleteExpediente(expedienteId) {
        if (confirm('¿Estás seguro de eliminar este expediente? Esta acción no se puede deshacer.')) {
            fetch(`/api/expedientes/${expedienteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Expediente eliminado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar el expediente', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function saveExpediente() {
        const formData = new FormData(document.getElementById('expedienteForm'));
        const expedienteId = document.getElementById('expedienteId').value;
        const isEdit = expedienteId !== '';

        const url = isEdit ? `/api/expedientes/${expedienteId}` : '/api/expedientes';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(`Expediente ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                expedienteModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el expediente', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function saveStatusChange() {
        const formData = new FormData(document.getElementById('statusForm'));
        const expedienteId = document.getElementById('statusExpedienteId').value;

        fetch(`/api/expedientes/${expedienteId}/estado`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Estado del expediente actualizado correctamente', 'success');
                statusModal.hide();
                location.reload();
            } else {
                showAlert('Error al cambiar el estado del expediente', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
    }

    // Limpiar formulario al abrir modal para nuevo expediente
    document.getElementById('expedienteModal').addEventListener('show.bs.modal', function (event) {
        if (!event.relatedTarget || !event.relatedTarget.dataset.expedienteId) {
            document.getElementById('expedienteForm').reset();
            document.getElementById('expedienteModalLabel').textContent = 'Nuevo Expediente';
            document.getElementById('expedienteId').value = '';
            document.getElementById('documentosList').style.display = 'none';
        }
    });
</script>
@endpush

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px #dee2e6;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #0d6efd;
    }

    .timeline-title {
        margin-bottom: 5px;
        font-weight: 600;
    }

    .timeline-description {
        margin-bottom: 5px;
        color: #6c757d;
    }
</style>
@endpush
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
