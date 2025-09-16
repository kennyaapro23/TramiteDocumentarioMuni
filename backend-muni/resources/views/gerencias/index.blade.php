@extends('layouts.app')

@section('title', 'Gestión de Gerencias')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>Gestión de Gerencias
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#gerenciaModal">
                        <i class="fas fa-plus me-2"></i>Nueva Gerencia
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchGerencias" placeholder="Buscar gerencias...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterTipo">
                                <option value="">Todos los tipos</option>
                                <option value="gerencia">Gerencias</option>
                                <option value="subgerencia">Subgerencias</option>
                                <option value="oficina">Oficinas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activas</option>
                                <option value="inactivo">Inactivas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="toggleTreeView()">
                                <i class="fas fa-sitemap me-2"></i>Vista Árbol
                            </button>
                        </div>
                    </div>

                    <!-- Vista de Tabla -->
                    <div id="tableView">
                        <div class="table-responsive">
                            <table class="table table-hover" id="gerenciasTable">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Gerencia Padre</th>
                                        <th>Responsable</th>
                                        <th>Usuarios</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gerencias as $gerencia)
                                    <tr data-gerencia-id="{{ $gerencia->id }}" data-tipo="{{ $gerencia->tipo }}" data-estado="{{ $gerencia->estado }}">
                                        <td>
                                            <strong>{{ $gerencia->codigo }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($gerencia->nivel > 0)
                                                <span class="me-2" style="margin-left: {{ $gerencia->nivel * 20 }}px;">
                                                    <i class="fas fa-arrow-turn-down-right text-muted"></i>
                                                </span>
                                                @endif
                                                <div>
                                                    <strong>{{ $gerencia->nombre }}</strong>
                                                    @if($gerencia->descripcion)
                                                    <br><small class="text-muted">{{ Str::limit($gerencia->descripcion, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $gerencia->tipo == 'gerencia' ? 'primary' : ($gerencia->tipo == 'subgerencia' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($gerencia->tipo) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $gerencia->parent->nombre ?? '-' }}
                                        </td>
                                        <td>
                                            @if($gerencia->responsable)
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $gerencia->responsable->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($gerencia->responsable->name) }}" 
                                                     class="rounded-circle me-2" width="24" height="24" alt="Avatar">
                                                <div>
                                                    <small class="fw-semibold">{{ $gerencia->responsable->name }}</small>
                                                    <br><small class="text-muted">{{ $gerencia->responsable->email }}</small>
                                                </div>
                                            </div>
                                            @else
                                            <span class="text-muted">Sin responsable</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $gerencia->usuarios_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $gerencia->estado == 'activo' ? 'success' : 'danger' }}">
                                                {{ ucfirst($gerencia->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-sm btn-outline-primary" onclick="editGerencia({{ $gerencia->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-info" onclick="viewGerencia({{ $gerencia->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-success" onclick="addSubGerencia({{ $gerencia->id }})">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                @if($gerencia->estado == 'activo')
                                                <button class="btn btn-sm btn-outline-warning" onclick="toggleGerenciaStatus({{ $gerencia->id }}, 'inactivo')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                @else
                                                <button class="btn btn-sm btn-outline-success" onclick="toggleGerenciaStatus({{ $gerencia->id }}, 'activo')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                                @if(!$gerencia->children->count() && !$gerencia->usuarios_count)
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteGerencia({{ $gerencia->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-building fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted">No hay gerencias registradas</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Vista de Árbol -->
                    <div id="treeView" style="display: none;">
                        <div class="tree-container">
                            @include('gerencias.tree', ['gerencias' => $gerencias_tree])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Gerencia -->
<div class="modal fade" id="gerenciaModal" tabindex="-1" aria-labelledby="gerenciaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="gerenciaForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="gerenciaModalLabel">Nueva Gerencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="gerenciaId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaCodigo" class="form-label">Código *</label>
                                <input type="text" class="form-control" id="gerenciaCodigo" name="codigo" required>
                                <small class="form-text text-muted">Código único identificador</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaTipo" class="form-label">Tipo *</label>
                                <select class="form-select" id="gerenciaTipo" name="tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="gerencia">Gerencia</option>
                                    <option value="subgerencia">Subgerencia</option>
                                    <option value="oficina">Oficina</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gerenciaNombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="gerenciaNombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="gerenciaDescripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="gerenciaDescripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaParent" class="form-label">Gerencia Padre</label>
                                <select class="form-select" id="gerenciaParent" name="parent_id">
                                    <option value="">Sin gerencia padre</option>
                                    @foreach($gerencias_para_select as $ger)
                                    <option value="{{ $ger->id }}">{{ $ger->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaResponsable" class="form-label">Responsable</label>
                                <select class="form-select" id="gerenciaResponsable" name="responsable_id">
                                    <option value="">Sin responsable</option>
                                    @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaEstado" class="form-label">Estado</label>
                                <select class="form-select" id="gerenciaEstado" name="estado">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="gerenciaOrden" class="form-label">Orden</label>
                                <input type="number" class="form-control" id="gerenciaOrden" name="orden" min="0" value="0">
                                <small class="form-text text-muted">Orden de visualización</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="gerenciaFunciones" class="form-label">Funciones y Responsabilidades</label>
                        <textarea class="form-control" id="gerenciaFunciones" name="funciones" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Gerencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles de Gerencia -->
<div class="modal fade" id="gerenciaViewModal" tabindex="-1" aria-labelledby="gerenciaViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gerenciaViewModalLabel">Detalles de la Gerencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="gerenciaViewContent">
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
    .tree-container {
        font-family: Arial, sans-serif;
    }
    
    .tree-item {
        margin: 5px 0;
        padding: 8px;
        border-left: 3px solid #dee2e6;
        margin-left: 20px;
        border-radius: 0 5px 5px 0;
        background: #f8f9fa;
    }
    
    .tree-item.gerencia {
        border-left-color: #0d6efd;
        background: #e7f1ff;
    }
    
    .tree-item.subgerencia {
        border-left-color: #0dcaf0;
        background: #e5f9fd;
    }
    
    .tree-item.oficina {
        border-left-color: #6c757d;
        background: #f1f3f4;
    }
    
    .tree-item-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .tree-item-title {
        font-weight: bold;
        color: #212529;
    }
    
    .tree-item-info {
        font-size: 0.875rem;
        color: #6c757d;
    }
    
    .tree-children {
        margin-left: 15px;
        border-left: 1px dashed #dee2e6;
        padding-left: 15px;
    }
</style>
@endpush

@push('scripts')
<script>
    let gerenciaModal, gerenciaViewModal;
    let isTreeView = false;

    document.addEventListener('DOMContentLoaded', function() {
        gerenciaModal = new bootstrap.Modal(document.getElementById('gerenciaModal'));
        gerenciaViewModal = new bootstrap.Modal(document.getElementById('gerenciaViewModal'));

        setupFilters();
        setupForm();
    });

    function setupFilters() {
        const searchInput = document.getElementById('searchGerencias');
        const tipoFilter = document.getElementById('filterTipo');
        const estadoFilter = document.getElementById('filterEstado');

        [searchInput, tipoFilter, estadoFilter].forEach(element => {
            element.addEventListener('input', filterGerencias);
        });
    }

    function filterGerencias() {
        const searchTerm = document.getElementById('searchGerencias').value.toLowerCase();
        const tipoFilter = document.getElementById('filterTipo').value;
        const estadoFilter = document.getElementById('filterEstado').value;

        const rows = document.querySelectorAll('#gerenciasTable tbody tr[data-gerencia-id]');

        rows.forEach(row => {
            const nombre = row.cells[1].textContent.toLowerCase();
            const codigo = row.cells[0].textContent.toLowerCase();
            const tipo = row.dataset.tipo;
            const estado = row.dataset.estado;

            const matchesSearch = nombre.includes(searchTerm) || codigo.includes(searchTerm);
            const matchesTipo = !tipoFilter || tipo === tipoFilter;
            const matchesEstado = !estadoFilter || estado === estadoFilter;

            row.style.display = (matchesSearch && matchesTipo && matchesEstado) ? '' : 'none';
        });
    }

    function toggleTreeView() {
        const tableView = document.getElementById('tableView');
        const treeView = document.getElementById('treeView');
        const button = event.target.closest('button');

        isTreeView = !isTreeView;

        if (isTreeView) {
            tableView.style.display = 'none';
            treeView.style.display = 'block';
            button.innerHTML = '<i class="fas fa-table me-2"></i>Vista Tabla';
        } else {
            tableView.style.display = 'block';
            treeView.style.display = 'none';
            button.innerHTML = '<i class="fas fa-sitemap me-2"></i>Vista Árbol';
        }
    }

    function setupForm() {
        document.getElementById('gerenciaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveGerencia();
        });

        // Actualizar opciones de gerencia padre según el tipo
        document.getElementById('gerenciaTipo').addEventListener('change', function() {
            updateParentOptions();
        });
    }

    function updateParentOptions() {
        const tipo = document.getElementById('gerenciaTipo').value;
        const parentSelect = document.getElementById('gerenciaParent');
        const options = parentSelect.querySelectorAll('option[value!=""]');

        options.forEach(option => {
            const optionText = option.textContent;
            // Mostrar solo gerencias padre apropiadas según el tipo
            if (tipo === 'subgerencia') {
                // Las subgerencias pueden pertenecer a gerencias
                option.style.display = '';
            } else if (tipo === 'oficina') {
                // Las oficinas pueden pertenecer a gerencias o subgerencias
                option.style.display = '';
            } else {
                // Las gerencias principales no tienen padre
                option.style.display = 'none';
            }
        });

        if (tipo === 'gerencia') {
            parentSelect.value = '';
        }
    }

    function editGerencia(gerenciaId) {
        fetch(`/api/gerencias/${gerenciaId}`)
            .then(response => response.json())
            .then(gerencia => {
                document.getElementById('gerenciaModalLabel').textContent = 'Editar Gerencia';
                document.getElementById('gerenciaId').value = gerencia.id;
                document.getElementById('gerenciaCodigo').value = gerencia.codigo;
                document.getElementById('gerenciaNombre').value = gerencia.nombre;
                document.getElementById('gerenciaDescripcion').value = gerencia.descripcion || '';
                document.getElementById('gerenciaTipo').value = gerencia.tipo;
                document.getElementById('gerenciaParent').value = gerencia.parent_id || '';
                document.getElementById('gerenciaResponsable').value = gerencia.responsable_id || '';
                document.getElementById('gerenciaEstado').value = gerencia.estado;
                document.getElementById('gerenciaOrden').value = gerencia.orden || 0;
                document.getElementById('gerenciaFunciones').value = gerencia.funciones || '';

                updateParentOptions();
                gerenciaModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos de la gerencia', 'danger');
            });
    }

    function viewGerencia(gerenciaId) {
        fetch(`/api/gerencias/${gerenciaId}/detalles`)
            .then(response => response.json())
            .then(gerencia => {
                const content = `
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Información General</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr><th width="30%">Código:</th><td>${gerencia.codigo}</td></tr>
                                        <tr><th>Nombre:</th><td>${gerencia.nombre}</td></tr>
                                        <tr><th>Tipo:</th><td><span class="badge bg-${gerencia.tipo === 'gerencia' ? 'primary' : 'info'}">${gerencia.tipo}</span></td></tr>
                                        <tr><th>Estado:</th><td><span class="badge bg-${gerencia.estado === 'activo' ? 'success' : 'danger'}">${gerencia.estado}</span></td></tr>
                                        <tr><th>Gerencia Padre:</th><td>${gerencia.parent ? gerencia.parent.nombre : 'Ninguna'}</td></tr>
                                        <tr><th>Responsable:</th><td>${gerencia.responsable ? gerencia.responsable.name : 'Sin asignar'}</td></tr>
                                        <tr><th>Creada:</th><td>${gerencia.created_at}</td></tr>
                                    </table>
                                    ${gerencia.descripcion ? '<div class="mt-3"><strong>Descripción:</strong><p>' + gerencia.descripcion + '</p></div>' : ''}
                                    ${gerencia.funciones ? '<div class="mt-3"><strong>Funciones:</strong><p>' + gerencia.funciones + '</p></div>' : ''}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Estadísticas</h6>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-primary">${gerencia.usuarios_count}</h4>
                                            <small class="text-muted">Usuarios</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-success">${gerencia.subgerencias_count}</h4>
                                            <small class="text-muted">Subgerencias</small>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="text-info">${gerencia.expedientes_count}</h4>
                                            <small class="text-muted">Expedientes</small>
                                        </div>
                                        <div class="col-6">
                                            <h4 class="text-warning">${gerencia.workflows_count}</h4>
                                            <small class="text-muted">Workflows</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            ${gerencia.usuarios && gerencia.usuarios.length > 0 ? `
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Usuarios Asignados</h6>
                                </div>
                                <div class="card-body">
                                    ${gerencia.usuarios.map(usuario => `
                                        <div class="d-flex align-items-center mb-2">
                                            <img src="${usuario.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(usuario.name)}" 
                                                 class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                                            <div>
                                                <div class="fw-semibold">${usuario.name}</div>
                                                <small class="text-muted">${usuario.email}</small>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                document.getElementById('gerenciaViewContent').innerHTML = content;
                gerenciaViewModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los detalles de la gerencia', 'danger');
            });
    }

    function addSubGerencia(parentId) {
        document.getElementById('gerenciaModalLabel').textContent = 'Nueva Subgerencia';
        document.getElementById('gerenciaForm').reset();
        document.getElementById('gerenciaId').value = '';
        document.getElementById('gerenciaParent').value = parentId;
        document.getElementById('gerenciaTipo').value = 'subgerencia';
        updateParentOptions();
        gerenciaModal.show();
    }

    function toggleGerenciaStatus(gerenciaId, newStatus) {
        if (confirm(`¿Estás seguro de ${newStatus === 'activo' ? 'activar' : 'desactivar'} esta gerencia?`)) {
            fetch(`/api/gerencias/${gerenciaId}/estado`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ estado: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`Gerencia ${newStatus === 'activo' ? 'activada' : 'desactivada'} correctamente`, 'success');
                    location.reload();
                } else {
                    showAlert('Error al cambiar el estado de la gerencia', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function deleteGerencia(gerenciaId) {
        if (confirm('¿Estás seguro de eliminar esta gerencia? Esta acción no se puede deshacer.')) {
            fetch(`/api/gerencias/${gerenciaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Gerencia eliminada correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar la gerencia', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function saveGerencia() {
        const formData = new FormData(document.getElementById('gerenciaForm'));
        const gerenciaId = document.getElementById('gerenciaId').value;
        const isEdit = gerenciaId !== '';

        const url = isEdit ? `/api/gerencias/${gerenciaId}` : '/api/gerencias';
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
                showAlert(`Gerencia ${isEdit ? 'actualizada' : 'creada'} correctamente`, 'success');
                gerenciaModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar la gerencia', 'danger');
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

    // Limpiar formulario al abrir modal para nueva gerencia
    document.getElementById('gerenciaModal').addEventListener('show.bs.modal', function (event) {
        if (!event.relatedTarget || !event.relatedTarget.dataset.gerenciaId) {
            document.getElementById('gerenciaForm').reset();
            document.getElementById('gerenciaModalLabel').textContent = 'Nueva Gerencia';
            document.getElementById('gerenciaId').value = '';
            updateParentOptions();
        }
    });
</script>
@endpush