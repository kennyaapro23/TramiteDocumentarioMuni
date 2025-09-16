@extends('layouts.app')

@section('title', 'Editor de Workflows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-project-diagram me-2"></i>Editor de Workflows
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#workflowModal">
                            <i class="fas fa-plus me-2"></i>Nuevo Workflow
                        </button>
                        <button class="btn btn-outline-secondary" onclick="toggleView()">
                            <i class="fas fa-list me-2"></i>Vista Lista
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Lista de Workflows -->
                    <div id="workflowsList" class="row mb-4">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-sitemap me-2"></i>Workflows Disponibles
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        @forelse($workflows as $workflow)
                                        <a href="#" class="list-group-item list-group-item-action workflow-item" 
                                           data-workflow-id="{{ $workflow->id }}" onclick="loadWorkflow({{ $workflow->id }})">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $workflow->nombre }}</h6>
                                                    <small class="text-muted">{{ $workflow->descripcion ?? 'Sin descripción' }}</small>
                                                    <br><small class="badge bg-{{ $workflow->activo ? 'success' : 'secondary' }}">
                                                        {{ $workflow->activo ? 'Activo' : 'Inactivo' }}
                                                    </small>
                                                </div>
                                                <div class="btn-group-vertical">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editWorkflow({{ $workflow->id }})" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteWorkflow({{ $workflow->id }})" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </a>
                                        @empty
                                        <div class="list-group-item text-center">
                                            <i class="fas fa-project-diagram fa-2x text-muted mb-2 d-block"></i>
                                            <small class="text-muted">No hay workflows creados</small>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Editor Visual -->
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title mb-0" id="workflowTitle">Editor Visual</h6>
                                        <small class="text-muted" id="workflowDescription">Selecciona un workflow para editar</small>
                                    </div>
                                    <div class="btn-group" id="editorControls" style="display: none;">
                                        <button class="btn btn-sm btn-outline-primary" onclick="addStep()">
                                            <i class="fas fa-plus me-1"></i>Agregar Paso
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="saveWorkflow()">
                                            <i class="fas fa-save me-1"></i>Guardar
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="previewWorkflow()">
                                            <i class="fas fa-eye me-1"></i>Vista Previa
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="workflowCanvas" class="workflow-canvas">
                                        <div class="canvas-placeholder">
                                            <i class="fas fa-mouse-pointer fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Selecciona un workflow para comenzar a editar</h5>
                                            <p class="text-muted">O crea un nuevo workflow desde el botón "Nuevo Workflow"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vista de Tabla (oculta por defecto) -->
                    <div id="workflowsTable" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Pasos</th>
                                        <th>Tipo Trámite</th>
                                        <th>Estado</th>
                                        <th>Fecha Creación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workflows as $workflow)
                                    <tr>
                                        <td><strong>{{ $workflow->nombre }}</strong></td>
                                        <td>{{ Str::limit($workflow->descripcion ?? 'Sin descripción', 50) }}</td>
                                        <td><span class="badge bg-info">{{ $workflow->steps_count ?? 0 }}</span></td>
                                        <td>{{ $workflow->tipoTramite->nombre ?? 'General' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $workflow->activo ? 'success' : 'secondary' }}">
                                                {{ $workflow->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                        <td>{{ $workflow->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="loadWorkflow({{ $workflow->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" onclick="duplicateWorkflow({{ $workflow->id }})">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteWorkflow({{ $workflow->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Workflow -->
<div class="modal fade" id="workflowModal" tabindex="-1" aria-labelledby="workflowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="workflowForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="workflowModalLabel">Nuevo Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="workflowId" name="id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="workflowNombre" class="form-label">Nombre del Workflow *</label>
                                <input type="text" class="form-control" id="workflowNombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="workflowActivo" class="form-label">Estado</label>
                                <select class="form-select" id="workflowActivo" name="activo">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="workflowDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="workflowDescripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowTipoTramite" class="form-label">Tipo de Trámite</label>
                                <select class="form-select" id="workflowTipoTramite" name="tipo_tramite_id">
                                    <option value="">General (Todos los tipos)</option>
                                    @foreach($tipos_tramite as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowVersion" class="form-label">Versión</label>
                                <input type="text" class="form-control" id="workflowVersion" name="version" value="1.0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Configuración Avanzada</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="workflowAutoInicio" name="auto_inicio" value="1">
                                    <label class="form-check-label" for="workflowAutoInicio">
                                        Inicio automático
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="workflowPermiteRegreso" name="permite_regreso" value="1">
                                    <label class="form-check-label" for="workflowPermiteRegreso">
                                        Permite regreso
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="workflowNotificaciones" name="notificaciones" value="1" checked>
                                    <label class="form-check-label" for="workflowNotificaciones">
                                        Enviar notificaciones
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Workflow
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Paso -->
<div class="modal fade" id="stepModal" tabindex="-1" aria-labelledby="stepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="stepForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="stepModalLabel">Nuevo Paso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="stepId" name="id">
                    <input type="hidden" id="stepWorkflowId" name="workflow_id">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="stepNombre" class="form-label">Nombre del Paso *</label>
                                <input type="text" class="form-control" id="stepNombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stepOrden" class="form-label">Orden *</label>
                                <input type="number" class="form-control" id="stepOrden" name="orden" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stepDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="stepDescripcion" name="descripcion" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stepTipo" class="form-label">Tipo de Paso *</label>
                                <select class="form-select" id="stepTipo" name="tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="inicio">Inicio</option>
                                    <option value="revision">Revisión</option>
                                    <option value="aprobacion">Aprobación</option>
                                    <option value="derivacion">Derivación</option>
                                    <option value="notificacion">Notificación</option>
                                    <option value="finalizacion">Finalización</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stepResponsable" class="form-label">Responsable</label>
                                <select class="form-select" id="stepResponsable" name="responsable_tipo">
                                    <option value="usuario">Usuario específico</option>
                                    <option value="rol">Rol</option>
                                    <option value="gerencia">Gerencia</option>
                                    <option value="automatico">Automático</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stepTiempoLimite" class="form-label">Tiempo Límite (días)</label>
                                <input type="number" class="form-control" id="stepTiempoLimite" name="tiempo_limite" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stepActivo" class="form-label">Estado</label>
                                <select class="form-select" id="stepActivo" name="activo">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Configuración del Paso</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stepRequiereAprobacion" name="requiere_aprobacion" value="1">
                                    <label class="form-check-label" for="stepRequiereAprobacion">
                                        Requiere aprobación
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stepPermiteRechazo" name="permite_rechazo" value="1">
                                    <label class="form-check-label" for="stepPermiteRechazo">
                                        Permite rechazo
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="stepEnviaNotificacion" name="envia_notificacion" value="1" checked>
                                    <label class="form-check-label" for="stepEnviaNotificacion">
                                        Envía notificación
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stepInstrucciones" class="form-label">Instrucciones para el Usuario</label>
                        <textarea class="form-control" id="stepInstrucciones" name="instrucciones" rows="3" 
                                  placeholder="Instrucciones que verá el usuario al realizar este paso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Paso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Vista Previa del Workflow -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Vista Previa del Workflow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .workflow-canvas {
        min-height: 500px;
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        position: relative;
        overflow: auto;
    }

    .canvas-placeholder {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .workflow-step {
        position: absolute;
        background: white;
        border: 2px solid #0d6efd;
        border-radius: 8px;
        padding: 15px;
        min-width: 150px;
        cursor: move;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .workflow-step:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .workflow-step.selected {
        border-color: #dc3545;
        box-shadow: 0 0 0 3px rgba(220,53,69,0.25);
    }

    .workflow-step .step-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 8px;
    }

    .workflow-step .step-title {
        font-weight: bold;
        font-size: 14px;
        margin: 0;
    }

    .workflow-step .step-type {
        font-size: 12px;
        padding: 2px 6px;
        border-radius: 4px;
        background: #e9ecef;
        color: #495057;
    }

    .workflow-step .step-controls {
        display: none;
        position: absolute;
        top: -10px;
        right: -10px;
    }

    .workflow-step:hover .step-controls {
        display: block;
    }

    .workflow-connection {
        position: absolute;
        height: 2px;
        background: #6c757d;
        z-index: 1;
    }

    .workflow-connection::after {
        content: '';
        position: absolute;
        right: -6px;
        top: -4px;
        width: 0;
        height: 0;
        border-left: 8px solid #6c757d;
        border-top: 4px solid transparent;
        border-bottom: 4px solid transparent;
    }

    .step-tipo-inicio { border-color: #28a745; }
    .step-tipo-revision { border-color: #ffc107; }
    .step-tipo-aprobacion { border-color: #17a2b8; }
    .step-tipo-derivacion { border-color: #6f42c1; }
    .step-tipo-notificacion { border-color: #fd7e14; }
    .step-tipo-finalizacion { border-color: #dc3545; }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .workflow-toolbar {
        position: sticky;
        top: 0;
        background: white;
        z-index: 100;
        border-bottom: 1px solid #dee2e6;
        padding: 10px 0;
        margin-bottom: 15px;
    }

    .step-connection-point {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #0d6efd;
        border-radius: 50%;
        cursor: crosshair;
    }

    .step-connection-point.input {
        top: 50%;
        left: -5px;
        transform: translateY(-50%);
    }

    .step-connection-point.output {
        top: 50%;
        right: -5px;
        transform: translateY(-50%);
    }

    .workflow-grid {
        background-image: 
            linear-gradient(to right, #e9ecef 1px, transparent 1px),
            linear-gradient(to bottom, #e9ecef 1px, transparent 1px);
        background-size: 20px 20px;
    }
</style>
@endpush

@push('scripts')
<script>
    let workflowModal, stepModal, previewModal;
    let currentWorkflow = null;
    let selectedStep = null;
    let isTableView = false;
    let canvas, steps = [], connections = [];
    let draggedStep = null;
    let isDragging = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar modales
        workflowModal = new bootstrap.Modal(document.getElementById('workflowModal'));
        stepModal = new bootstrap.Modal(document.getElementById('stepModal'));
        previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

        // Configurar canvas
        canvas = document.getElementById('workflowCanvas');
        setupCanvas();
        setupForms();
    });

    function setupCanvas() {
        canvas.addEventListener('click', function(e) {
            if (e.target === canvas) {
                deselectAllSteps();
            }
        });

        // Configurar drop zone
        canvas.addEventListener('dragover', function(e) {
            e.preventDefault();
        });

        canvas.addEventListener('drop', function(e) {
            e.preventDefault();
            // Implementar drop de nuevos pasos
        });
    }

    function setupForms() {
        document.getElementById('workflowForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveWorkflowData();
        });

        document.getElementById('stepForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveStepData();
        });
    }

    function toggleView() {
        isTableView = !isTableView;
        const listView = document.getElementById('workflowsList');
        const tableView = document.getElementById('workflowsTable');
        const button = event.target.closest('button');

        if (isTableView) {
            listView.style.display = 'none';
            tableView.style.display = 'block';
            button.innerHTML = '<i class="fas fa-project-diagram me-2"></i>Vista Editor';
        } else {
            listView.style.display = 'block';
            tableView.style.display = 'none';
            button.innerHTML = '<i class="fas fa-list me-2"></i>Vista Lista';
        }
    }

    function loadWorkflow(workflowId) {
        // Marcar workflow activo en la lista
        document.querySelectorAll('.workflow-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-workflow-id="${workflowId}"]`).classList.add('active');

        // Cargar datos del workflow
        fetch(`/api/workflows/${workflowId}`)
            .then(response => response.json())
            .then(workflow => {
                currentWorkflow = workflow;
                document.getElementById('workflowTitle').textContent = workflow.nombre;
                document.getElementById('workflowDescription').textContent = workflow.descripcion || 'Sin descripción';
                document.getElementById('editorControls').style.display = 'block';
                
                // Limpiar canvas
                clearCanvas();
                
                // Cargar pasos del workflow
                loadWorkflowSteps(workflowId);
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar el workflow', 'danger');
            });
    }

    function loadWorkflowSteps(workflowId) {
        fetch(`/api/workflows/${workflowId}/steps`)
            .then(response => response.json())
            .then(stepsData => {
                steps = stepsData;
                renderWorkflowSteps();
                renderConnections();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los pasos del workflow', 'danger');
            });
    }

    function clearCanvas() {
        const placeholder = canvas.querySelector('.canvas-placeholder');
        canvas.innerHTML = '';
        canvas.classList.add('workflow-grid');
        if (!currentWorkflow) {
            canvas.appendChild(placeholder);
            canvas.classList.remove('workflow-grid');
        }
    }

    function renderWorkflowSteps() {
        clearCanvas();
        
        steps.forEach((step, index) => {
            const stepElement = createStepElement(step, index);
            canvas.appendChild(stepElement);
        });
    }

    function createStepElement(step, index) {
        const stepDiv = document.createElement('div');
        stepDiv.className = `workflow-step step-tipo-${step.tipo}`;
        stepDiv.dataset.stepId = step.id;
        stepDiv.style.left = (step.posicion_x || (100 + index * 200)) + 'px';
        stepDiv.style.top = (step.posicion_y || (50 + (index % 3) * 150)) + 'px';

        stepDiv.innerHTML = `
            <div class="step-header">
                <div>
                    <div class="step-title">${step.nombre}</div>
                    <div class="step-type">${step.tipo}</div>
                </div>
                <div class="step-controls">
                    <button class="btn btn-sm btn-outline-primary" onclick="editStep(${step.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteStep(${step.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            ${step.descripcion ? `<div class="step-description">${step.descripcion}</div>` : ''}
            <div class="step-info">
                <small class="text-muted">Orden: ${step.orden}</small>
                ${step.tiempo_limite ? `<br><small class="text-muted">Límite: ${step.tiempo_limite} días</small>` : ''}
            </div>
            <div class="step-connection-point input" title="Entrada"></div>
            <div class="step-connection-point output" title="Salida"></div>
        `;

        // Hacer el paso arrastrable
        makeStepDraggable(stepDiv);
        
        // Agregar eventos de click
        stepDiv.addEventListener('click', function(e) {
            e.stopPropagation();
            selectStep(stepDiv);
        });

        return stepDiv;
    }

    function makeStepDraggable(stepElement) {
        let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

        stepElement.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            document.onmousemove = elementDrag;
            isDragging = true;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            stepElement.style.top = (stepElement.offsetTop - pos2) + "px";
            stepElement.style.left = (stepElement.offsetLeft - pos1) + "px";
            
            // Actualizar conexiones si existen
            updateConnections();
        }

        function closeDragElement() {
            document.onmouseup = null;
            document.onmousemove = null;
            isDragging = false;
            
            // Guardar nueva posición
            if (currentWorkflow) {
                saveStepPosition(stepElement);
            }
        }
    }

    function selectStep(stepElement) {
        deselectAllSteps();
        stepElement.classList.add('selected');
        selectedStep = stepElement;
    }

    function deselectAllSteps() {
        document.querySelectorAll('.workflow-step').forEach(step => {
            step.classList.remove('selected');
        });
        selectedStep = null;
    }

    function renderConnections() {
        // Implementar renderizado de conexiones entre pasos
        // Esto se puede hacer con SVG o canvas para mayor flexibilidad
    }

    function updateConnections() {
        // Actualizar posiciones de las conexiones cuando se mueven los pasos
    }

    function addStep() {
        if (!currentWorkflow) {
            showAlert('Primero selecciona o crea un workflow', 'warning');
            return;
        }

        document.getElementById('stepModalLabel').textContent = 'Nuevo Paso';
        document.getElementById('stepForm').reset();
        document.getElementById('stepId').value = '';
        document.getElementById('stepWorkflowId').value = currentWorkflow.id;
        document.getElementById('stepOrden').value = steps.length + 1;
        stepModal.show();
    }

    function editStep(stepId) {
        const step = steps.find(s => s.id == stepId);
        if (!step) return;

        document.getElementById('stepModalLabel').textContent = 'Editar Paso';
        document.getElementById('stepId').value = step.id;
        document.getElementById('stepWorkflowId').value = step.workflow_id;
        document.getElementById('stepNombre').value = step.nombre;
        document.getElementById('stepDescripcion').value = step.descripcion || '';
        document.getElementById('stepTipo').value = step.tipo;
        document.getElementById('stepOrden').value = step.orden;
        document.getElementById('stepResponsable').value = step.responsable_tipo || 'usuario';
        document.getElementById('stepTiempoLimite').value = step.tiempo_limite || '';
        document.getElementById('stepActivo').value = step.activo ? '1' : '0';
        document.getElementById('stepRequiereAprobacion').checked = step.requiere_aprobacion;
        document.getElementById('stepPermiteRechazo').checked = step.permite_rechazo;
        document.getElementById('stepEnviaNotificacion').checked = step.envia_notificacion;
        document.getElementById('stepInstrucciones').value = step.instrucciones || '';

        stepModal.show();
    }

    function deleteStep(stepId) {
        if (confirm('¿Estás seguro de eliminar este paso? Esta acción no se puede deshacer.')) {
            fetch(`/api/workflows/steps/${stepId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Paso eliminado correctamente', 'success');
                    loadWorkflowSteps(currentWorkflow.id);
                } else {
                    showAlert('Error al eliminar el paso', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function editWorkflow(workflowId) {
        fetch(`/api/workflows/${workflowId}`)
            .then(response => response.json())
            .then(workflow => {
                document.getElementById('workflowModalLabel').textContent = 'Editar Workflow';
                document.getElementById('workflowId').value = workflow.id;
                document.getElementById('workflowNombre').value = workflow.nombre;
                document.getElementById('workflowDescripcion').value = workflow.descripcion || '';
                document.getElementById('workflowTipoTramite').value = workflow.tipo_tramite_id || '';
                document.getElementById('workflowVersion').value = workflow.version || '1.0';
                document.getElementById('workflowActivo').value = workflow.activo ? '1' : '0';
                document.getElementById('workflowAutoInicio').checked = workflow.auto_inicio;
                document.getElementById('workflowPermiteRegreso').checked = workflow.permite_regreso;
                document.getElementById('workflowNotificaciones').checked = workflow.notificaciones;

                workflowModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del workflow', 'danger');
            });
    }

    function deleteWorkflow(workflowId) {
        if (confirm('¿Estás seguro de eliminar este workflow? Se eliminarán todos sus pasos y no se puede deshacer.')) {
            fetch(`/api/workflows/${workflowId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Workflow eliminado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar el workflow', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function duplicateWorkflow(workflowId) {
        if (confirm('¿Crear una copia de este workflow?')) {
            fetch(`/api/workflows/${workflowId}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Workflow duplicado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al duplicar el workflow', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function saveWorkflow() {
        if (!currentWorkflow) {
            showAlert('No hay workflow seleccionado para guardar', 'warning');
            return;
        }

        // Recopilar posiciones de los pasos
        const stepPositions = [];
        document.querySelectorAll('.workflow-step').forEach(stepElement => {
            const stepId = stepElement.dataset.stepId;
            stepPositions.push({
                id: stepId,
                posicion_x: parseInt(stepElement.style.left),
                posicion_y: parseInt(stepElement.style.top)
            });
        });

        fetch(`/api/workflows/${currentWorkflow.id}/save-layout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                step_positions: stepPositions
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Workflow guardado correctamente', 'success');
            } else {
                showAlert('Error al guardar el workflow', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function previewWorkflow() {
        if (!currentWorkflow) {
            showAlert('No hay workflow seleccionado', 'warning');
            return;
        }

        const content = `
            <div class="workflow-preview">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Información del Workflow</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-sm">
                                    <tr><th>Nombre:</th><td>${currentWorkflow.nombre}</td></tr>
                                    <tr><th>Versión:</th><td>${currentWorkflow.version || '1.0'}</td></tr>
                                    <tr><th>Estado:</th><td><span class="badge bg-${currentWorkflow.activo ? 'success' : 'secondary'}">${currentWorkflow.activo ? 'Activo' : 'Inactivo'}</span></td></tr>
                                    <tr><th>Pasos:</th><td>${steps.length}</td></tr>
                                    <tr><th>Tipo Trámite:</th><td>${currentWorkflow.tipo_tramite || 'General'}</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Flujo de Pasos</h6>
                            </div>
                            <div class="card-body">
                                ${steps.map((step, index) => `
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="step-number bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; font-size: 14px;">
                                            ${step.orden}
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">${step.nombre}</div>
                                            <small class="text-muted">Tipo: ${step.tipo} ${step.tiempo_limite ? `• Límite: ${step.tiempo_limite} días` : ''}</small>
                                            ${step.descripcion ? `<br><small class="text-muted">${step.descripcion}</small>` : ''}
                                        </div>
                                        ${index < steps.length - 1 ? '<i class="fas fa-arrow-down text-muted"></i>' : ''}
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('previewContent').innerHTML = content;
        previewModal.show();
    }

    function saveWorkflowData() {
        const formData = new FormData(document.getElementById('workflowForm'));
        const workflowId = document.getElementById('workflowId').value;
        const isEdit = workflowId !== '';

        const url = isEdit ? `/api/workflows/${workflowId}` : '/api/workflows';
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
                showAlert(`Workflow ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                workflowModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el workflow', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function saveStepData() {
        const formData = new FormData(document.getElementById('stepForm'));
        const stepId = document.getElementById('stepId').value;
        const isEdit = stepId !== '';

        const url = isEdit ? `/api/workflows/steps/${stepId}` : '/api/workflows/steps';
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
                showAlert(`Paso ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                stepModal.hide();
                loadWorkflowSteps(currentWorkflow.id);
            } else {
                showAlert(data.message || 'Error al guardar el paso', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function saveStepPosition(stepElement) {
        const stepId = stepElement.dataset.stepId;
        const posX = parseInt(stepElement.style.left);
        const posY = parseInt(stepElement.style.top);

        fetch(`/api/workflows/steps/${stepId}/position`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                posicion_x: posX,
                posicion_y: posY
            })
        })
        .catch(error => {
            console.error('Error al guardar posición:', error);
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

    // Limpiar formularios al abrir modales
    document.getElementById('workflowModal').addEventListener('show.bs.modal', function () {
        if (!event.relatedTarget || !event.relatedTarget.dataset.workflowId) {
            document.getElementById('workflowForm').reset();
            document.getElementById('workflowModalLabel').textContent = 'Nuevo Workflow';
            document.getElementById('workflowId').value = '';
        }
    });
</script>
@endpush