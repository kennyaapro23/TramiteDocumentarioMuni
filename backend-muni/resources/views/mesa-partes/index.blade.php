@extends('layouts.app')

@section('title', 'Mesa de Partes - Recepción de Documentos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-inbox me-2"></i>Mesa de Partes
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#documentoModal">
                            <i class="fas fa-plus me-2"></i>Registrar Documento
                        </button>
                        <button class="btn btn-outline-secondary" onclick="toggleStats()">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas
                        </button>
                        <button class="btn btn-outline-info" onclick="exportData()">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Estadísticas (ocultas por defecto) -->
                    <div id="statsPanel" class="row mb-4" style="display: none;">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0" id="totalHoy">0</h4>
                                            <p class="mb-0">Recibidos Hoy</p>
                                        </div>
                                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0" id="totalSemana">0</h4>
                                            <p class="mb-0">Esta Semana</p>
                                        </div>
                                        <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0" id="pendientesDerivacion">0</h4>
                                            <p class="mb-0">Pendientes Derivación</p>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0" id="totalMes">0</h4>
                                            <p class="mb-0">Este Mes</p>
                                        </div>
                                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="filtroFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="filtroFecha" onchange="filtrarDocumentos()">
                        </div>
                        <div class="col-md-2">
                            <label for="filtroTipoDoc" class="form-label">Tipo Documento</label>
                            <select class="form-select" id="filtroTipoDoc" onchange="filtrarDocumentos()">
                                <option value="">Todos</option>
                                @foreach($tipos_documento as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filtroEstado" class="form-label">Estado</label>
                            <select class="form-select" id="filtroEstado" onchange="filtrarDocumentos()">
                                <option value="">Todos</option>
                                <option value="recibido">Recibido</option>
                                <option value="en_revision">En Revisión</option>
                                <option value="derivado">Derivado</option>
                                <option value="observado">Observado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="filtroGerencia" class="form-label">Gerencia Destino</label>
                            <select class="form-select" id="filtroGerencia" onchange="filtrarDocumentos()">
                                <option value="">Todas</option>
                                @foreach($gerencias as $gerencia)
                                <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filtroBuscar" class="form-label">Buscar</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="filtroBuscar" 
                                       placeholder="Número, remitente, asunto..." onkeyup="filtrarDocumentos()">
                                <button class="btn btn-outline-secondary" type="button" onclick="limpiarFiltros()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-outline-primary d-block w-100" onclick="filtrarDocumentos()">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de Documentos -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th onclick="ordenarTabla('numero')" style="cursor: pointer;">
                                        Número <i class="fas fa-sort"></i>
                                    </th>
                                    <th onclick="ordenarTabla('fecha_recepcion')" style="cursor: pointer;">
                                        Fecha Recepción <i class="fas fa-sort"></i>
                                    </th>
                                    <th>Tipo Documento</th>
                                    <th>Remitente</th>
                                    <th>Asunto</th>
                                    <th>Gerencia Destino</th>
                                    <th onclick="ordenarTabla('estado')" style="cursor: pointer;">
                                        Estado <i class="fas fa-sort"></i>
                                    </th>
                                    <th>Urgencia</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="documentosTableBody">
                                @forelse($documentos as $documento)
                                <tr>
                                    <td>
                                        <strong>{{ $documento->numero_registro }}</strong>
                                        @if($documento->archivos_count > 0)
                                        <br><small class="text-muted">
                                            <i class="fas fa-paperclip"></i> {{ $documento->archivos_count }} archivo(s)
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $documento->fecha_recepcion->format('d/m/Y H:i') }}
                                        <br><small class="text-muted">{{ $documento->fecha_recepcion->diffForHumans() }}</small>
                                    </td>
                                    <td>{{ $documento->tipoDocumento->nombre ?? 'No especificado' }}</td>
                                    <td>
                                        <strong>{{ $documento->remitente_nombre }}</strong>
                                        @if($documento->remitente_documento)
                                        <br><small class="text-muted">{{ $documento->remitente_documento }}</small>
                                        @endif
                                        @if($documento->remitente_telefono)
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone"></i> {{ $documento->remitente_telefono }}
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span title="{{ $documento->asunto }}">
                                            {{ Str::limit($documento->asunto, 50) }}
                                        </span>
                                        @if($documento->observaciones)
                                        <br><small class="text-muted">
                                            <i class="fas fa-sticky-note"></i> Con observaciones
                                        </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($documento->gerencia_destino_id)
                                        <span class="badge bg-info">{{ $documento->gerenciaDestino->nombre }}</span>
                                        @else
                                        <span class="badge bg-secondary">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $documento->estado === 'recibido' ? 'primary' : 
                                            ($documento->estado === 'en_revision' ? 'warning' : 
                                            ($documento->estado === 'derivado' ? 'success' : 
                                            ($documento->estado === 'observado' ? 'info' : 'danger'))) 
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $documento->estado)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($documento->urgente)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle"></i> Urgente
                                        </span>
                                        @else
                                        <span class="badge bg-light text-dark">Normal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="verDocumento({{ $documento->id }})" title="Ver Detalles">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="derivarDocumento({{ $documento->id }})" title="Derivar">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editarDocumento({{ $documento->id }})" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" onclick="observarDocumento({{ $documento->id }})">
                                                        <i class="fas fa-comment me-2"></i>Observar
                                                    </a></li>
                                                    <li><a class="dropdown-item" onclick="duplicarDocumento({{ $documento->id }})">
                                                        <i class="fas fa-copy me-2"></i>Duplicar
                                                    </a></li>
                                                    <li><a class="dropdown-item" onclick="imprimirDocumento({{ $documento->id }})">
                                                        <i class="fas fa-print me-2"></i>Imprimir
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" onclick="eliminarDocumento({{ $documento->id }})">
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
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">No hay documentos registrados</h5>
                                        <p class="text-muted">Comienza registrando el primer documento</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <small class="text-muted">
                                Mostrando {{ $documentos->firstItem() ?? 0 }} a {{ $documentos->lastItem() ?? 0 }} 
                                de {{ $documentos->total() }} registros
                            </small>
                        </div>
                        <div>
                            {{ $documentos->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Registrar/Editar Documento -->
<div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="documentoForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentoModalLabel">Registrar Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="documentoId" name="id">
                    
                    <div class="row">
                        <!-- Información del Documento -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Información del Documento</h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tipoDocumento" class="form-label">Tipo de Documento *</label>
                                        <select class="form-select" id="tipoDocumento" name="tipo_documento_id" required>
                                            <option value="">Seleccionar tipo</option>
                                            @foreach($tipos_documento as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="numeroRegistro" class="form-label">Número de Registro</label>
                                        <input type="text" class="form-control" id="numeroRegistro" name="numero_registro" readonly>
                                        <div class="form-text">Se genera automáticamente</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fechaRecepcion" class="form-label">Fecha de Recepción *</label>
                                        <input type="datetime-local" class="form-control" id="fechaRecepcion" name="fecha_recepcion" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fechaDocumento" class="form-label">Fecha del Documento</label>
                                        <input type="date" class="form-control" id="fechaDocumento" name="fecha_documento">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="asunto" class="form-label">Asunto *</label>
                                <textarea class="form-control" id="asunto" name="asunto" rows="3" required 
                                          placeholder="Describa brevemente el asunto del documento..."></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gerenciaDestino" class="form-label">Gerencia Destino</label>
                                        <select class="form-select" id="gerenciaDestino" name="gerencia_destino_id">
                                            <option value="">Asignar después</option>
                                            @foreach($gerencias as $gerencia)
                                            <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Prioridad</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="urgente" name="urgente" value="1">
                                            <label class="form-check-label" for="urgente">
                                                <i class="fas fa-exclamation-triangle text-warning"></i> Documento Urgente
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Remitente -->
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2 mb-3">Información del Remitente</h6>
                            
                            <div class="mb-3">
                                <label for="remitenteNombre" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="remitenteNombre" name="remitente_nombre" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="remitenteDocumento" class="form-label">Documento de Identidad</label>
                                        <input type="text" class="form-control" id="remitenteDocumento" name="remitente_documento">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="remitenteTelefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" id="remitenteTelefono" name="remitente_telefono">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="remitenteEmail" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="remitenteEmail" name="remitente_email">
                            </div>

                            <div class="mb-3">
                                <label for="remitenteDireccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="remitenteDireccion" name="remitente_direccion" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="remitenteInstitucion" class="form-label">Institución/Empresa</label>
                                <input type="text" class="form-control" id="remitenteInstitucion" name="remitente_institucion">
                            </div>
                        </div>
                    </div>

                    <!-- Archivos Adjuntos -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Archivos Adjuntos</h6>
                            
                            <div class="mb-3">
                                <label for="archivos" class="form-label">Seleccionar Archivos</label>
                                <input type="file" class="form-control" id="archivos" name="archivos[]" multiple 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls">
                                <div class="form-text">
                                    Formatos permitidos: PDF, DOC, DOCX, JPG, PNG, XLSX, XLS. Máximo 10MB por archivo.
                                </div>
                            </div>

                            <div id="archivosPreview" class="row"></div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">Observaciones Adicionales</h6>
                            
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                          placeholder="Observaciones adicionales sobre el documento..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Registrar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Documento -->
<div class="modal fade" id="verDocumentoModal" tabindex="-1" aria-labelledby="verDocumentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verDocumentoModalLabel">Detalles del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="verDocumentoContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Derivar Documento -->
<div class="modal fade" id="derivarModal" tabindex="-1" aria-labelledby="derivarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="derivarForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="derivarModalLabel">Derivar Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="derivarDocumentoId" name="documento_id">
                    
                    <div class="mb-3">
                        <label for="derivarGerencia" class="form-label">Gerencia Destino *</label>
                        <select class="form-select" id="derivarGerencia" name="gerencia_destino_id" required>
                            <option value="">Seleccionar gerencia</option>
                            @foreach($gerencias as $gerencia)
                            <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="derivarMotivo" class="form-label">Motivo de Derivación *</label>
                        <textarea class="form-control" id="derivarMotivo" name="motivo" rows="3" required 
                                  placeholder="Explique el motivo de la derivación..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="derivarNotificar" name="notificar" value="1" checked>
                        <label class="form-check-label" for="derivarNotificar">
                            Notificar al responsable de la gerencia
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-share me-2"></i>Derivar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Observar Documento -->
<div class="modal fade" id="observarModal" tabindex="-1" aria-labelledby="observarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="observarForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="observarModalLabel">Observar Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="observarDocumentoId" name="documento_id">
                    
                    <div class="mb-3">
                        <label for="observarMotivo" class="form-label">Observaciones *</label>
                        <textarea class="form-control" id="observarMotivo" name="observaciones" rows="4" required 
                                  placeholder="Describa las observaciones al documento..."></textarea>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="observarNotificar" name="notificar" value="1" checked>
                        <label class="form-check-label" for="observarNotificar">
                            Notificar al remitente
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-comment me-2"></i>Observar Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-top: none;
    }

    .table th[onclick] {
        user-select: none;
    }

    .table th[onclick]:hover {
        background-color: #e9ecef;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-group .dropdown-menu {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .archivo-preview {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }

    .archivo-preview .archivo-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .archivo-preview .archivo-info {
        font-size: 0.875rem;
    }

    .archivo-preview .btn-remove {
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .stats-card {
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .urgente-indicator {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .documento-numero {
        font-family: 'Courier New', monospace;
        font-weight: bold;
        color: #0d6efd;
    }

    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }

    .modal-xl .modal-dialog {
        max-width: 1200px;
    }

    .border-bottom {
        border-bottom: 2px solid #e9ecef !important;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    let documentoModal, verDocumentoModal, derivarModal, observarModal;
    let documentosData = [];
    let filteredData = [];
    let currentSort = { column: 'fecha_recepcion', direction: 'desc' };
    let statsVisible = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar modales
        documentoModal = new bootstrap.Modal(document.getElementById('documentoModal'));
        verDocumentoModal = new bootstrap.Modal(document.getElementById('verDocumentoModal'));
        derivarModal = new bootstrap.Modal(document.getElementById('derivarModal'));
        observarModal = new bootstrap.Modal(document.getElementById('observarModal'));

        // Configurar fecha por defecto
        document.getElementById('fechaRecepcion').value = new Date().toISOString().slice(0, 16);

        // Configurar eventos
        setupFormHandlers();
        setupFileUpload();
        loadDocumentosData();
        loadEstadisticas();
    });

    function setupFormHandlers() {
        document.getElementById('documentoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            guardarDocumento();
        });

        document.getElementById('derivarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            procesarDerivacion();
        });

        document.getElementById('observarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            procesarObservacion();
        });
    }

    function setupFileUpload() {
        document.getElementById('archivos').addEventListener('change', function(e) {
            mostrarPreviewArchivos(e.target.files);
        });
    }

    function mostrarPreviewArchivos(files) {
        const preview = document.getElementById('archivosPreview');
        preview.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const iconClass = getFileIcon(file.type);
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            const previewItem = document.createElement('div');
            previewItem.className = 'col-md-4 mb-3';
            previewItem.innerHTML = `
                <div class="archivo-preview position-relative">
                    <button type="button" class="btn btn-sm btn-danger archivo-remove" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="text-center">
                        <i class="${iconClass} archivo-icon"></i>
                        <div class="archivo-info">
                            <div class="fw-bold">${file.name}</div>
                            <small class="text-muted">${fileSize} MB</small>
                        </div>
                    </div>
                </div>
            `;
            preview.appendChild(previewItem);
        });
    }

    function getFileIcon(fileType) {
        if (fileType.includes('pdf')) return 'fas fa-file-pdf text-danger';
        if (fileType.includes('word') || fileType.includes('document')) return 'fas fa-file-word text-primary';
        if (fileType.includes('excel') || fileType.includes('sheet')) return 'fas fa-file-excel text-success';
        if (fileType.includes('image')) return 'fas fa-file-image text-info';
        return 'fas fa-file text-secondary';
    }

    function removeFile(index) {
        const fileInput = document.getElementById('archivos');
        const dt = new DataTransfer();
        const files = fileInput.files;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== index) {
                dt.items.add(files[i]);
            }
        }
        
        fileInput.files = dt.files;
        mostrarPreviewArchivos(fileInput.files);
    }

    function loadDocumentosData() {
        // Cargar datos para funciones de filtrado y ordenamiento
        fetch('/api/mesa-partes/documentos')
            .then(response => response.json())
            .then(data => {
                documentosData = data;
                filteredData = data;
            })
            .catch(error => console.error('Error:', error));
    }

    function loadEstadisticas() {
        fetch('/api/mesa-partes/estadisticas')
            .then(response => response.json())
            .then(stats => {
                document.getElementById('totalHoy').textContent = stats.hoy || 0;
                document.getElementById('totalSemana').textContent = stats.semana || 0;
                document.getElementById('totalMes').textContent = stats.mes || 0;
                document.getElementById('pendientesDerivacion').textContent = stats.pendientes || 0;
            })
            .catch(error => console.error('Error:', error));
    }

    function toggleStats() {
        statsVisible = !statsVisible;
        const panel = document.getElementById('statsPanel');
        const button = event.target.closest('button');
        
        if (statsVisible) {
            panel.style.display = 'block';
            button.innerHTML = '<i class="fas fa-chart-bar me-2"></i>Ocultar Estadísticas';
        } else {
            panel.style.display = 'none';
            button.innerHTML = '<i class="fas fa-chart-bar me-2"></i>Estadísticas';
        }
    }

    function filtrarDocumentos() {
        const fecha = document.getElementById('filtroFecha').value;
        const tipoDoc = document.getElementById('filtroTipoDoc').value;
        const estado = document.getElementById('filtroEstado').value;
        const gerencia = document.getElementById('filtroGerencia').value;
        const buscar = document.getElementById('filtroBuscar').value.toLowerCase();

        const params = new URLSearchParams();
        if (fecha) params.append('fecha', fecha);
        if (tipoDoc) params.append('tipo_documento_id', tipoDoc);
        if (estado) params.append('estado', estado);
        if (gerencia) params.append('gerencia_destino_id', gerencia);
        if (buscar) params.append('buscar', buscar);

        // Recargar la página con los filtros
        window.location.search = params.toString();
    }

    function limpiarFiltros() {
        document.getElementById('filtroFecha').value = '';
        document.getElementById('filtroTipoDoc').value = '';
        document.getElementById('filtroEstado').value = '';
        document.getElementById('filtroGerencia').value = '';
        document.getElementById('filtroBuscar').value = '';
        
        // Recargar sin parámetros
        window.location.href = window.location.pathname;
    }

    function ordenarTabla(columna) {
        if (currentSort.column === columna) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = columna;
            currentSort.direction = 'asc';
        }

        const params = new URLSearchParams(window.location.search);
        params.set('sort', columna);
        params.set('direction', currentSort.direction);
        
        window.location.search = params.toString();
    }

    function verDocumento(documentoId) {
        fetch(`/api/mesa-partes/documentos/${documentoId}`)
            .then(response => response.json())
            .then(documento => {
                mostrarDetallesDocumento(documento);
                verDocumentoModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los detalles del documento', 'danger');
            });
    }

    function mostrarDetallesDocumento(documento) {
        const urgenteBadge = documento.urgente ? '<span class="badge bg-danger ms-2"><i class="fas fa-exclamation-triangle"></i> URGENTE</span>' : '';
        const estadoBadge = getEstadoBadge(documento.estado);
        
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2 mb-3">Información del Documento</h6>
                    <table class="table table-borderless table-sm">
                        <tr><th width="40%">Número:</th><td><span class="documento-numero">${documento.numero_registro}</span></td></tr>
                        <tr><th>Tipo:</th><td>${documento.tipo_documento?.nombre || 'No especificado'}</td></tr>
                        <tr><th>Estado:</th><td>${estadoBadge}</td></tr>
                        <tr><th>Fecha Recepción:</th><td>${new Date(documento.fecha_recepcion).toLocaleString('es-PE')}</td></tr>
                        <tr><th>Fecha Documento:</th><td>${documento.fecha_documento || 'No especificada'}</td></tr>
                        <tr><th>Urgencia:</th><td>${documento.urgente ? '<span class="badge bg-danger">URGENTE</span>' : '<span class="badge bg-light text-dark">Normal</span>'}</td></tr>
                        <tr><th>Gerencia Destino:</th><td>${documento.gerencia_destino?.nombre || 'Sin asignar'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2 mb-3">Información del Remitente</h6>
                    <table class="table table-borderless table-sm">
                        <tr><th width="40%">Nombre:</th><td>${documento.remitente_nombre}</td></tr>
                        <tr><th>Documento:</th><td>${documento.remitente_documento || 'No especificado'}</td></tr>
                        <tr><th>Teléfono:</th><td>${documento.remitente_telefono || 'No especificado'}</td></tr>
                        <tr><th>Email:</th><td>${documento.remitente_email || 'No especificado'}</td></tr>
                        <tr><th>Dirección:</th><td>${documento.remitente_direccion || 'No especificada'}</td></tr>
                        <tr><th>Institución:</th><td>${documento.remitente_institucion || 'No especificada'}</td></tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Asunto</h6>
                    <p>${documento.asunto}</p>
                </div>
            </div>

            ${documento.observaciones ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Observaciones</h6>
                    <p>${documento.observaciones}</p>
                </div>
            </div>
            ` : ''}

            ${documento.archivos && documento.archivos.length > 0 ? `
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="border-bottom pb-2 mb-3">Archivos Adjuntos (${documento.archivos.length})</h6>
                    <div class="row">
                        ${documento.archivos.map(archivo => `
                            <div class="col-md-4 mb-2">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <i class="${getFileIcon(archivo.tipo_mime)} fa-2x mb-2"></i>
                                        <div class="small">${archivo.nombre_original}</div>
                                        <div class="text-muted small">${(archivo.tamaño / 1024 / 1024).toFixed(2)} MB</div>
                                        <a href="/api/mesa-partes/documentos/${documento.id}/archivos/${archivo.id}" 
                                           class="btn btn-sm btn-outline-primary mt-2" target="_blank">
                                            <i class="fas fa-download"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
            ` : ''}
        `;
        
        document.getElementById('verDocumentoContent').innerHTML = content;
    }

    function getEstadoBadge(estado) {
        const badges = {
            'recibido': '<span class="badge bg-primary">Recibido</span>',
            'en_revision': '<span class="badge bg-warning">En Revisión</span>',
            'derivado': '<span class="badge bg-success">Derivado</span>',
            'observado': '<span class="badge bg-info">Observado</span>',
            'rechazado': '<span class="badge bg-danger">Rechazado</span>'
        };
        return badges[estado] || '<span class="badge bg-secondary">Desconocido</span>';
    }

    function editarDocumento(documentoId) {
        fetch(`/api/mesa-partes/documentos/${documentoId}`)
            .then(response => response.json())
            .then(documento => {
                cargarDatosFormulario(documento);
                document.getElementById('documentoModalLabel').textContent = 'Editar Documento';
                documentoModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del documento', 'danger');
            });
    }

    function cargarDatosFormulario(documento) {
        document.getElementById('documentoId').value = documento.id;
        document.getElementById('tipoDocumento').value = documento.tipo_documento_id || '';
        document.getElementById('numeroRegistro').value = documento.numero_registro;
        document.getElementById('fechaRecepcion').value = documento.fecha_recepcion ? new Date(documento.fecha_recepcion).toISOString().slice(0, 16) : '';
        document.getElementById('fechaDocumento').value = documento.fecha_documento || '';
        document.getElementById('asunto').value = documento.asunto || '';
        document.getElementById('gerenciaDestino').value = documento.gerencia_destino_id || '';
        document.getElementById('urgente').checked = documento.urgente || false;
        
        // Datos del remitente
        document.getElementById('remitenteNombre').value = documento.remitente_nombre || '';
        document.getElementById('remitenteDocumento').value = documento.remitente_documento || '';
        document.getElementById('remitenteTelefono').value = documento.remitente_telefono || '';
        document.getElementById('remitenteEmail').value = documento.remitente_email || '';
        document.getElementById('remitenteDireccion').value = documento.remitente_direccion || '';
        document.getElementById('remitenteInstitucion').value = documento.remitente_institucion || '';
        
        document.getElementById('observaciones').value = documento.observaciones || '';
    }

    function derivarDocumento(documentoId) {
        document.getElementById('derivarDocumentoId').value = documentoId;
        derivarModal.show();
    }

    function observarDocumento(documentoId) {
        document.getElementById('observarDocumentoId').value = documentoId;
        observarModal.show();
    }

    function duplicarDocumento(documentoId) {
        if (confirm('¿Crear una copia de este documento?')) {
            fetch(`/api/mesa-partes/documentos/${documentoId}/duplicar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Documento duplicado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al duplicar el documento', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function imprimirDocumento(documentoId) {
        window.open(`/api/mesa-partes/documentos/${documentoId}/imprimir`, '_blank');
    }

    function eliminarDocumento(documentoId) {
        if (confirm('¿Estás seguro de eliminar este documento? Esta acción no se puede deshacer.')) {
            fetch(`/api/mesa-partes/documentos/${documentoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Documento eliminado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar el documento', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function exportData() {
        const params = new URLSearchParams(window.location.search);
        params.append('export', 'excel');
        window.open(`/api/mesa-partes/documentos/export?${params.toString()}`, '_blank');
    }

    function guardarDocumento() {
        const formData = new FormData(document.getElementById('documentoForm'));
        const documentoId = document.getElementById('documentoId').value;
        const isEdit = documentoId !== '';

        const url = isEdit ? `/api/mesa-partes/documentos/${documentoId}` : '/api/mesa-partes/documentos';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: 'POST', // Siempre POST para FormData
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(`Documento ${isEdit ? 'actualizado' : 'registrado'} correctamente`, 'success');
                documentoModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el documento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function procesarDerivacion() {
        const formData = new FormData(document.getElementById('derivarForm'));

        fetch('/api/mesa-partes/documentos/derivar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Documento derivado correctamente', 'success');
                derivarModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al derivar el documento', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function procesarObservacion() {
        const formData = new FormData(document.getElementById('observarForm'));

        fetch('/api/mesa-partes/documentos/observar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Documento observado correctamente', 'success');
                observarModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al observar el documento', 'danger');
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

    // Limpiar formulario al abrir modal
    document.getElementById('documentoModal').addEventListener('show.bs.modal', function (event) {
        if (!event.relatedTarget || !event.relatedTarget.dataset.documentoId) {
            document.getElementById('documentoForm').reset();
            document.getElementById('documentoModalLabel').textContent = 'Registrar Documento';
            document.getElementById('documentoId').value = '';
            document.getElementById('archivosPreview').innerHTML = '';
            document.getElementById('fechaRecepcion').value = new Date().toISOString().slice(0, 16);
        }
    });
</script>
@endpush