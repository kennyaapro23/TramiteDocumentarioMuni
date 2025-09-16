@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Gestión de Usuarios
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="fas fa-plus me-2"></i>Nuevo Usuario
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchUsers" placeholder="Buscar usuarios...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterRole">
                                <option value="">Todos los roles</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterGerencia">
                                <option value="">Todas las gerencias</option>
                                @foreach($gerencias as $gerencia)
                                <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="filterStatus">
                                <option value="">Todos</option>
                                <option value="activo">Activos</option>
                                <option value="inactivo">Inactivos</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de Usuarios -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Avatar</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Gerencia</th>
                                    <th>Estado</th>
                                    <th>Último Login</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($usuarios as $usuario)
                                <tr data-user-id="{{ $usuario->id }}">
                                    <td>{{ $usuario->id }}</td>
                                    <td>
                                        <img src="{{ $usuario->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($usuario->name) }}" 
                                             class="rounded-circle" width="32" height="32" alt="Avatar">
                                    </td>
                                    <td>
                                        <strong>{{ $usuario->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $usuario->dni ?? 'Sin DNI' }}</small>
                                    </td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        @foreach($usuario->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                        @endforeach
                                        @if($usuario->roles->isEmpty())
                                        <span class="badge bg-secondary">Sin rol</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->gerencia->nombre ?? 'Sin asignar' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $usuario->estado == 'activo' ? 'success' : 'danger' }}">
                                            {{ ucfirst($usuario->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $usuario->last_login_at ? $usuario->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editUser({{ $usuario->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewUser({{ $usuario->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($usuario->estado == 'activo')
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleUserStatus({{ $usuario->id }}, 'inactivo')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            @else
                                            <button class="btn btn-sm btn-outline-success" onclick="toggleUserStatus({{ $usuario->id }}, 'activo')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $usuario->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No hay usuarios registrados</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($usuarios->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $usuarios->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Crear/Editar Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="userId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userName" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="userName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="userEmail" name="email" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userDni" class="form-label">DNI</label>
                                <input type="text" class="form-control" id="userDni" name="dni" maxlength="8">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userPhone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="userPhone" name="phone">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="userPassword" name="password">
                                <small class="form-text text-muted">Deja en blanco para mantener la contraseña actual</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userPasswordConfirmation" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="userPasswordConfirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userGerencia" class="form-label">Gerencia</label>
                                <select class="form-select" id="userGerencia" name="gerencia_id">
                                    <option value="">Seleccionar gerencia</option>
                                    @foreach($gerencias as $gerencia)
                                    <option value="{{ $gerencia->id }}">{{ $gerencia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="userEstado" class="form-label">Estado</label>
                                <select class="form-select" id="userEstado" name="estado">
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Roles del Usuario</label>
                        <div class="row">
                            @foreach($roles as $role)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $role->name }}" 
                                           id="role_{{ $role->id }}" name="roles[]">
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                    </label>
                                    <small class="d-block text-muted">{{ $role->description ?? 'Sin descripción' }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver Detalles del Usuario -->
<div class="modal fade" id="userViewModal" tabindex="-1" aria-labelledby="userViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userViewModalLabel">Detalles del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userViewContent">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    let userModal, userViewModal;

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar modales
        userModal = new bootstrap.Modal(document.getElementById('userModal'));
        userViewModal = new bootstrap.Modal(document.getElementById('userViewModal'));

        // Configurar filtros en tiempo real
        setupFilters();

        // Configurar formulario de usuario
        setupUserForm();
    });

    function setupFilters() {
        const searchInput = document.getElementById('searchUsers');
        const roleFilter = document.getElementById('filterRole');
        const gerenciaFilter = document.getElementById('filterGerencia');
        const statusFilter = document.getElementById('filterStatus');

        [searchInput, roleFilter, gerenciaFilter, statusFilter].forEach(element => {
            element.addEventListener('input', filterUsers);
        });
    }

    function filterUsers() {
        const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
        const roleFilter = document.getElementById('filterRole').value;
        const gerenciaFilter = document.getElementById('filterGerencia').value;
        const statusFilter = document.getElementById('filterStatus').value;

        const rows = document.querySelectorAll('#usersTable tbody tr[data-user-id]');

        rows.forEach(row => {
            const name = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();
            const roles = row.cells[4].textContent.toLowerCase();
            const gerencia = row.cells[5].textContent;
            const status = row.cells[6].textContent.toLowerCase();

            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesRole = !roleFilter || roles.includes(roleFilter.toLowerCase());
            const matchesGerencia = !gerenciaFilter || row.querySelector('[data-gerencia-id="' + gerenciaFilter + '"]');
            const matchesStatus = !statusFilter || status.includes(statusFilter);

            row.style.display = (matchesSearch && matchesRole && matchesGerencia && matchesStatus) ? '' : 'none';
        });
    }

    function setupUserForm() {
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveUser();
        });
    }

    function editUser(userId) {
        // Realizar petición AJAX para obtener datos del usuario
        fetch(`/api/usuarios/${userId}`)
            .then(response => response.json())
            .then(user => {
                document.getElementById('userModalLabel').textContent = 'Editar Usuario';
                document.getElementById('userId').value = user.id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userDni').value = user.dni || '';
                document.getElementById('userPhone').value = user.phone || '';
                document.getElementById('userGerencia').value = user.gerencia_id || '';
                document.getElementById('userEstado').value = user.estado;

                // Marcar roles del usuario
                document.querySelectorAll('input[name="roles[]"]').forEach(checkbox => {
                    checkbox.checked = user.roles.some(role => role.name === checkbox.value);
                });

                userModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los datos del usuario', 'danger');
            });
    }

    function viewUser(userId) {
        // Realizar petición AJAX para obtener detalles completos del usuario
        fetch(`/api/usuarios/${userId}/detalles`)
            .then(response => response.json())
            .then(user => {
                const content = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${user.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name)}" 
                                 class="rounded-circle mb-3" width="120" height="120" alt="Avatar">
                            <h5>${user.name}</h5>
                            <p class="text-muted">${user.email}</p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr><th width="30%">DNI:</th><td>${user.dni || 'No especificado'}</td></tr>
                                <tr><th>Teléfono:</th><td>${user.phone || 'No especificado'}</td></tr>
                                <tr><th>Gerencia:</th><td>${user.gerencia ? user.gerencia.nombre : 'Sin asignar'}</td></tr>
                                <tr><th>Estado:</th><td><span class="badge bg-${user.estado === 'activo' ? 'success' : 'danger'}">${user.estado}</span></td></tr>
                                <tr><th>Roles:</th><td>${user.roles.map(role => '<span class="badge bg-primary me-1">' + role.name + '</span>').join('') || 'Sin roles'}</td></tr>
                                <tr><th>Último Login:</th><td>${user.last_login_at || 'Nunca'}</td></tr>
                                <tr><th>Registrado:</th><td>${user.created_at}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                document.getElementById('userViewContent').innerHTML = content;
                userViewModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al cargar los detalles del usuario', 'danger');
            });
    }

    function toggleUserStatus(userId, newStatus) {
        if (confirm(`¿Estás seguro de ${newStatus === 'activo' ? 'activar' : 'desactivar'} este usuario?`)) {
            fetch(`/api/usuarios/${userId}/estado`, {
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
                    showAlert(`Usuario ${newStatus === 'activo' ? 'activado' : 'desactivado'} correctamente`, 'success');
                    location.reload();
                } else {
                    showAlert('Error al cambiar el estado del usuario', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function deleteUser(userId) {
        if (confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) {
            fetch(`/api/usuarios/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Usuario eliminado correctamente', 'success');
                    location.reload();
                } else {
                    showAlert('Error al eliminar el usuario', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error al procesar la solicitud', 'danger');
            });
        }
    }

    function saveUser() {
        const formData = new FormData(document.getElementById('userForm'));
        const userId = document.getElementById('userId').value;
        const isEdit = userId !== '';

        const url = isEdit ? `/api/usuarios/${userId}` : '/api/usuarios';
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
                showAlert(`Usuario ${isEdit ? 'actualizado' : 'creado'} correctamente`, 'success');
                userModal.hide();
                location.reload();
            } else {
                showAlert(data.message || 'Error al guardar el usuario', 'danger');
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

    // Limpiar formulario al abrir modal para nuevo usuario
    document.getElementById('userModal').addEventListener('show.bs.modal', function (event) {
        if (!event.relatedTarget || !event.relatedTarget.dataset.userId) {
            document.getElementById('userForm').reset();
            document.getElementById('userModalLabel').textContent = 'Nuevo Usuario';
            document.getElementById('userId').value = '';
        }
    });
</script>
@endpush