<div class="p-4">
    <h5 class="mb-4">
        <i class="fas fa-users-cog text-primary mr-2"></i>
        Roles y Permisos
    </h5>
    
    <div class="row mb-4">
        <div class="col-12">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#roleModal">
                <i class="fas fa-plus mr-1"></i>
                Crear Nuevo Rol
            </button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#assignRoleModal">
                <i class="fas fa-user-plus mr-1"></i>
                Asignar Rol a Usuario
            </button>
        </div>
    </div>
    
    <!-- Listado de Roles -->
    <div class="row">
        <div class="col-md-6">
            <h6 class="text-muted mb-3">
                <i class="fas fa-list mr-1"></i>
                Roles Existentes
            </h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Rol</th>
                            <th>Permisos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>
                                <strong>{{ $role->name }}</strong>
                                @if(in_array($role->name, ['Super', 'Admin']))
                                <span class="badge badge-warning ml-2">Sistema</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $role->permissions->count() }} permisos</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" onclick="editRole({{ $role->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if(!in_array($role->name, ['Super', 'Admin']))
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteRole({{ $role->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-md-6">
            <h6 class="text-muted mb-3">
                <i class="fas fa-users mr-1"></i>
                Usuarios y sus Roles
            </h6>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Roles</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <strong>{{ $user->name }}</strong><br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge badge-secondary mr-1">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" onclick="manageUserRoles({{ $user->id }})">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Ejemplo de Permisos por Rol -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle mr-2"></i>Ejemplo de Asignación de Permisos:</h6>
                <ul class="mb-0">
                    <li><strong>Administrador:</strong> Acceso completo a todas las funcionalidades</li>
                    <li><strong>Recepcionista:</strong> Acceso a "Reservas" y "Habitaciones", sin acceso a "Configuración" ni "Reportes"</li>
                    <li><strong>Supervisor:</strong> Acceso a "Reservas", "Habitaciones" y "Reportes", sin acceso a "Configuración"</li>
                </ul>
            </div>
        </div>
    </div>
</div>



