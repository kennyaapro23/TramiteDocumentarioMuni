<!-- Modal para Crear/Editar Rol -->
<div id="roleModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRoleModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="roleForm" onsubmit="submitRoleForm(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="roleModalTitle">
                                Crear Rol
                            </h3>
                            <div class="mt-4 space-y-6">
                                <!-- Información Básica del Rol -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="role-name" class="block text-sm font-medium text-gray-700">Nombre del Rol *</label>
                                        <input type="text" id="role-name" name="name" required 
                                               class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                               placeholder="Ej: Jefe de Mesa de Partes">
                                    </div>

                                    <div>
                                        <label for="role-description" class="block text-sm font-medium text-gray-700">Descripción</label>
                                        <textarea id="role-description" name="description" rows="3" 
                                                  class="mt-1 focus:ring-municipal-500 focus:border-municipal-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                  placeholder="Describe las responsabilidades de este rol..."></textarea>
                                    </div>

                                    <div>
                                        <label for="role-level" class="block text-sm font-medium text-gray-700">Nivel de Acceso *</label>
                                        <select id="role-level" name="level" required 
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-municipal-500 focus:border-municipal-500 sm:text-sm rounded-md">
                                            <option value="">Seleccionar nivel</option>
                                            <option value="1">Nivel 1 - Básico</option>
                                            <option value="2">Nivel 2 - Intermedio</option>
                                            <option value="3">Nivel 3 - Avanzado</option>
                                            <option value="4">Nivel 4 - Administrador</option>
                                            <option value="5">Nivel 5 - Super Administrador</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="role-active" name="active" type="checkbox" checked 
                                               class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                        <label for="role-active" class="ml-2 block text-sm text-gray-900">
                                            Rol activo
                                        </label>
                                    </div>
                                </div>

                                <!-- Selección de Permisos -->
                                <div class="border-t pt-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">Permisos del Rol</h4>
                                    
                                    <!-- Acciones rápidas -->
                                    <div class="mb-4 flex space-x-2">
                                        <button type="button" onclick="selectAllPermissions()" 
                                                class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                            Seleccionar Todo
                                        </button>
                                        <button type="button" onclick="clearAllPermissions()" 
                                                class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500">
                                            Limpiar Todo
                                        </button>
                                    </div>

                                    <!-- Permisos por Módulo -->
                                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md">
                                        <!-- Módulo: Usuarios -->
                                        <div class="border-b border-gray-200">
                                            <div class="bg-gray-50 px-3 py-2">
                                                <div class="flex items-center">
                                                    <input id="module-users" type="checkbox" onchange="toggleModulePermissions('users')" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                                    <label for="module-users" class="ml-2 text-sm font-medium text-gray-900">
                                                        Gestión de Usuarios
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="px-6 py-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input id="perm-users-create" name="permissions[]" value="users.create" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-users-perm">
                                                    <label for="perm-users-create" class="ml-2 text-sm text-gray-900">Crear usuarios</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-users-read" name="permissions[]" value="users.read" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-users-perm">
                                                    <label for="perm-users-read" class="ml-2 text-sm text-gray-900">Ver usuarios</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-users-update" name="permissions[]" value="users.update" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-users-perm">
                                                    <label for="perm-users-update" class="ml-2 text-sm text-gray-900">Editar usuarios</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-users-delete" name="permissions[]" value="users.delete" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-users-perm">
                                                    <label for="perm-users-delete" class="ml-2 text-sm text-gray-900">
                                                        <span>Eliminar usuarios</span>
                                                        <span class="text-red-600 text-xs">(Crítico)</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Módulo: Expedientes -->
                                        <div class="border-b border-gray-200">
                                            <div class="bg-gray-50 px-3 py-2">
                                                <div class="flex items-center">
                                                    <input id="module-expedientes" type="checkbox" onchange="toggleModulePermissions('expedientes')" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                                    <label for="module-expedientes" class="ml-2 text-sm font-medium text-gray-900">
                                                        Gestión de Expedientes
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="px-6 py-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input id="perm-expedientes-create" name="permissions[]" value="expedientes.create" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-expedientes-perm">
                                                    <label for="perm-expedientes-create" class="ml-2 text-sm text-gray-900">Crear expedientes</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-expedientes-read" name="permissions[]" value="expedientes.read" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-expedientes-perm">
                                                    <label for="perm-expedientes-read" class="ml-2 text-sm text-gray-900">Ver expedientes</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-expedientes-update" name="permissions[]" value="expedientes.update" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-expedientes-perm">
                                                    <label for="perm-expedientes-update" class="ml-2 text-sm text-gray-900">Editar expedientes</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-expedientes-approve" name="permissions[]" value="expedientes.approve" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-expedientes-perm">
                                                    <label for="perm-expedientes-approve" class="ml-2 text-sm text-gray-900">Aprobar expedientes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Módulo: Mesa de Partes -->
                                        <div class="border-b border-gray-200">
                                            <div class="bg-gray-50 px-3 py-2">
                                                <div class="flex items-center">
                                                    <input id="module-mesa-partes" type="checkbox" onchange="toggleModulePermissions('mesa-partes')" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                                    <label for="module-mesa-partes" class="ml-2 text-sm font-medium text-gray-900">
                                                        Mesa de Partes
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="px-6 py-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input id="perm-mesa-recibir" name="permissions[]" value="mesa.recibir" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-mesa-partes-perm">
                                                    <label for="perm-mesa-recibir" class="ml-2 text-sm text-gray-900">Recibir documentos</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-mesa-derivar" name="permissions[]" value="mesa.derivar" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-mesa-partes-perm">
                                                    <label for="perm-mesa-derivar" class="ml-2 text-sm text-gray-900">Derivar documentos</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-mesa-consultar" name="permissions[]" value="mesa.consultar" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-mesa-partes-perm">
                                                    <label for="perm-mesa-consultar" class="ml-2 text-sm text-gray-900">Consultar estado</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Módulo: Reportes -->
                                        <div>
                                            <div class="bg-gray-50 px-3 py-2">
                                                <div class="flex items-center">
                                                    <input id="module-reports" type="checkbox" onchange="toggleModulePermissions('reports')" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded">
                                                    <label for="module-reports" class="ml-2 text-sm font-medium text-gray-900">
                                                        Reportes y Estadísticas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="px-6 py-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input id="perm-reports-view" name="permissions[]" value="reports.view" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-reports-perm">
                                                    <label for="perm-reports-view" class="ml-2 text-sm text-gray-900">Ver reportes</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input id="perm-reports-export" name="permissions[]" value="reports.export" type="checkbox" 
                                                           class="focus:ring-municipal-500 h-4 w-4 text-municipal-600 border-gray-300 rounded module-reports-perm">
                                                    <label for="perm-reports-export" class="ml-2 text-sm text-gray-900">Exportar reportes</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-municipal-600 text-base font-medium text-white hover:bg-municipal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <span id="roleSubmitText">Crear Rol</span>
                        <svg id="roleSubmitSpinner" class="hidden animate-spin -mr-1 ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="closeRoleModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-municipal-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>