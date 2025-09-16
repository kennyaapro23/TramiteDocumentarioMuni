@extends('layouts.app')

@section('title', 'Gestión de Roles y Permisos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Gestión de Roles -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tag me-2"></i>Roles del Sistema
                    </h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#roleModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Rol
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Usuarios</th>
                                    <th>Permisos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->name }}</strong>
                                        @if($role->description)
                                        <br><small class="text-muted">{{ $role->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $role->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $role->permissions_count }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editRole({{ $role->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewRolePermissions({{ $role->id }})">
                                                <i class="fas fa-shield-alt"></i>
                                            </button>
                                            @if(!in_array($role->name, ['Super Admin', 'Admin']))
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRole({{ $role->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        <i class="fas fa-user-tag fa-2x text-muted mb-2 d-block"></i>
                                        No hay roles definidos
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestión de Permisos -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Permisos del Sistema
                    </h5>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#permissionModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Permiso
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filtro por categoría -->
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="categoryFilter">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias_permisos as $categoria)
                            <option value="{{ $categoria }}">{{ ucfirst($categoria) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Permiso</th>
                                    <th>Categoría</th>
                                    <th>Roles</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="permissionsTable">
                                @forelse($permisos->groupBy('category') as $categoria => $permisos_categoria)
                                <tr class="table-active">
                                    <td colspan="4"><strong>{{ ucfirst($categoria) }}</strong></td>
                                </tr>
                                @foreach($permisos_categoria as $permission)
                                <tr data-category="{{ $categoria }}">
                                    <td>
                                        <small>{{ $permission->name }}</small>
                                        @if($permission->description)
                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $categoria }}</span>
                                    </td>
                                    <td>
                                        @foreach($permission->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editPermission({{ $permission->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">
                                        <i class="fas fa-shield-alt fa-2x text-muted mb-2 d-block"></i>
                                        No hay permisos definidos
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Matriz de Roles y Permisos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Matriz de Roles y Permisos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Permiso</th>
                                    @foreach($roles as $role)
                                    <th class="text-center" style="writing-mode: vertical-lr; transform: rotate(180deg);">
                                        {{ $role->name }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permisos->groupBy('category') as $categoria => $permisos_categoria)
                                <tr class="table-secondary">
                                    <td colspan="{{ count($roles) + 1 }}">
                                        <strong>{{ ucfirst($categoria) }}</strong>
                                    </td>
                                </tr>
                                @foreach($permisos_categoria as $permission)
                                <tr>
                                    <td>
                                        <small>{{ $permission->name }}</small>
                                        @if($permission->description)
                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                        @endif
                                    </td>
                                    @foreach($roles as $role)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="perm_{{ $permission->id }}_role_{{ $role->id }}"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                   onchange="toggleRolePermission({{ $role->id }}, {{ $permission->id }}, this.checked)"
                                                   {{ in_array($role->name, ['Super Admin', 'Admin']) && $permission->category === 'sistema' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Rol -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="roleForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="roleModalLabel">Nuevo Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="roleId" name="id">
                    
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Nombre del Rol *</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="roleDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="roleDescription" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permisos del Rol</label>
                        <div class="row">
                            @foreach($permisos->groupBy('category') as $categoria => $permisos_categoria)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-header py-2">
                                        <div class="form-check">
                                            <input class="form-check-input category-check" type="checkbox" 
                                                   id="category_{{ $categoria }}" data-category="{{ $categoria }}">
                                            <label class="form-check-label fw-bold" for="category_{{ $categoria }}">
                                                {{ ucfirst($categoria) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="row">
                                            @foreach($permisos_categoria as $permission)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-check" 
                                                           type="checkbox" 
                                                           value="{{ $permission->name }}" 
                                                           id="role_perm_{{ $permission->id }}" 
                                                           name="permissions[]"
                                                           data-category="{{ $categoria }}">
                                                    <label class="form-check-label" for="role_perm_{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Permiso -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="permissionForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Nuevo Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="permissionId" name="id">
                    
                    <div class="mb-3">
                        <label for="permissionName" class="form-label">Nombre del Permiso *</label>
                        <input type="text" class="form-control" id="permissionName" name="name" required>
                        <small class="form-text text-muted">Ej: usuarios.crear, expedientes.editar</small>
                    </div>

                    <div class="mb-3">
                        <label for="permissionCategory" class="form-label">Categoría *</label>
                        <select class="form-select" id="permissionCategory" name="category" required>
                            <option value="">Seleccionar categoría</option>
                            @foreach($categorias_permisos as $categoria)
                            <option value="{{ $categoria }}">{{ ucfirst($categoria) }}</option>
                            @endforeach
                            <option value="custom">Nueva categoría...</option>
                        </select>
                    </div>

                    <div class="mb-3" id="customCategoryGroup" style="display: none;">
                        <label for="customCategory" class="form-label">Nueva Categoría</label>
                        <input type="text" class="form-control" id="customCategory" name="custom_category">
                    </div>

                    <div class="mb-3">
                        <label for="permissionDescription" class="form-label">Descripción</label>
                        <textarea class="form-control" id="permissionDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Permiso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let roleModal, permissionModal;

    document.addEventListener('DOMContentLoaded', function() {
        roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
        permissionModal = new bootstrap.Modal(document.getElementById('permissionModal'));

        setupCategoryFilters();
        setupCategoryChecks();
        setupForms();
    });

    function setupCategoryFilters() {
        document.getElementById('categoryFilter').addEventListener('change', function() {
            const selectedCategory = this.value;
            const rows = document.querySelectorAll('#permissionsTable tr[data-category]');
            
            rows.forEach(row => {
                const category = row.dataset.category;
                row.style.display = (!selectedCategory || category === selectedCategory) ? '' : 'none';
            });
        });

        document.getElementById('permissionCategory').addEventListener('change', function() {
            const customGroup = document.getElementById('customCategoryGroup');
            customGroup.style.display = this.value === 'custom' ? 'block' : 'none';
        });
    }

    function setupCategoryChecks() {
        // Manejar selección de categorías completas
        document.querySelectorAll('.category-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const category = this.dataset.category;
                const permissionChecks = document.querySelectorAll(`input[data-category="${category}"].permission-check`);
                
                permissionChecks.forEach(permCheck => {
                    permCheck.checked = this.checked;
                });
            });
        });

        // Actualizar estado de categoría cuando cambian permisos individuales
        document.querySelectorAll('.permission-check').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const category = this.dataset.category;
                const categoryCheck = document.querySelector(`#category_${category}`);
                const categoryPermissions = document.querySelectorAll(`input[data-category="${category}"].permission-check`);
                const checkedPermissions = document.querySelectorAll(`input[data-category="${category}"].permission-check:checked`);
                
                if (checkedPermissions.length === 0) {
                    categoryCheck.checked = false;
                    categoryCheck.indeterminate = false;
                } else if (checkedPermissions.length === categoryPermissions.length) {
                    categoryCheck.checked = true;
                    categoryCheck.indeterminate = false;
                } else {
                    categoryCheck.checked = false;
                    categoryCheck.indeterminate = true;
                }
            });
        });
    }

    function setupForms() {
        document.getElementById('roleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveRole();
        });

        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            savePermission();
        });
    }

    function editRole(roleId) {
        fetch(`/api/roles/${roleId}`)
            .then(response => response.json())
            .then(role => {
                document.getElementById('roleModalLabel').textContent = 'Editar Rol';
                document.getElementById('roleId').value = role.id;
                document.getElementById('roleName').value = role.name;
                document.getElementById('roleDescription').value = role.description || '';

                // Marcar permisos del rol
                document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = role.permissions.some(perm => perm.name === checkbox.value);
                });

                // Actualizar estado de categorías
                document.querySelectorAll('.permission-check').forEach(checkbox => {
                    checkbox.dispatchEvent(new Event('change'));
                });

                roleModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del rol', 'danger');
            });
    }

    function editPermission(permissionId) {
        fetch(`/api/permisos/${permissionId}`)
            .then(response => response.json())
            .then(permission => {
                document.getElementById('permissionModalLabel').textContent = 'Editar Permiso';
                document.getElementById('permissionId').value = permission.id;
                document.getElementById('permissionName').value = permission.name;
                document.getElementById('permissionCategory').value = permission.category;
                document.getElementById('permissionDescription').value = permission.description || '';

                permissionModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del permiso', 'danger');
            });
    }

    function deleteRole(roleId) {
        if (confirm('¿Estás seguro de eliminar este rol? Los usuarios asignados perderán este rol.')) {
            fetch(`/api/roles/${roleId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Rol eliminado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar el rol', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function toggleRolePermission(roleId, permissionId, hasPermission) {
        const action = hasPermission ? 'asignar' : 'revocar';
        
        fetch(`/api/roles/${roleId}/permisos`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                permission_id: permissionId,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showAlert('Error al actualizar el permiso', 'danger');
                // Revertir checkbox
                document.getElementById(`perm_${permissionId}_role_${roleId}`).checked = !hasPermission;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
            // Revertir checkbox
            document.getElementById(`perm_${permissionId}_role_${roleId}`).checked = !hasPermission;
        });
    }

    function saveRole() {
        const formData = new FormData(document.getElementById('roleForm'));
        const roleId = document.getElementById('roleId').value;
        const isEdit = roleId !== '';

        const url = isEdit ? `/api/roles/${roleId}` : '/api/roles';
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
                showAlert(`Rol ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                roleModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el rol', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function savePermission() {
        const formData = new FormData(document.getElementById('permissionForm'));
        const permissionId = document.getElementById('permissionId').value;
        const isEdit = permissionId !== '';

        // Si es categoría personalizada, usar el valor del campo custom
        const categorySelect = document.getElementById('permissionCategory');
        if (categorySelect.value === 'custom') {
            formData.set('category', document.getElementById('customCategory').value);
        }

        const url = isEdit ? `/api/permisos/${permissionId}` : '/api/permisos';
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
                showAlert(`Permiso ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                permissionModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el permiso', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error al procesar la solicitud', 'danger');
        });
    }

    function viewRolePermissions(roleId) {
        // Implementar vista detallada de permisos del rol
        fetch(`/api/roles/${roleId}/permisos`)
            .then(response => response.json())
            .then(data => {
                // Crear modal o expandir vista con detalles
                console.log('Permisos del rol:', data);
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
    document.getElementById('roleModal').addEventListener('show.bs.modal', function () {
        if (!event.relatedTarget || !event.relatedTarget.dataset.roleId) {
            document.getElementById('roleForm').reset();
            document.getElementById('roleModalLabel').textContent = 'Nuevo Rol';
            document.getElementById('roleId').value = '';
        }
    });

    document.getElementById('permissionModal').addEventListener('show.bs.modal', function () {
        if (!event.relatedTarget || !event.relatedTarget.dataset.permissionId) {
            document.getElementById('permissionForm').reset();
            document.getElementById('permissionModalLabel').textContent = 'Nuevo Permiso';
            document.getElementById('permissionId').value = '';
            document.getElementById('customCategoryGroup').style.display = 'none';
        }
    });
</script>
@endpush