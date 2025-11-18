@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog mr-2"></i>
                        Configuración del Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Navegación por pestañas -->
                    <ul class="nav nav-tabs" id="configurationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab" aria-controls="hotel" aria-selected="true">
                                <i class="fas fa-hotel mr-1"></i> Datos del Hotel
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="preferences-tab" data-toggle="tab" href="#preferences" role="tab" aria-controls="preferences" aria-selected="false">
                                <i class="fas fa-sliders-h mr-1"></i> Preferencias
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="false">
                                <i class="fas fa-users-cog mr-1"></i> Roles y Permisos
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="security-tab" data-toggle="tab" href="#security" role="tab" aria-controls="security" aria-selected="false">
                                <i class="fas fa-shield-alt mr-1"></i> Seguridad
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="backups-tab" data-toggle="tab" href="#backups" role="tab" aria-controls="backups" aria-selected="false">
                                <i class="fas fa-database mr-1"></i> Copias de Seguridad
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content" id="configurationTabsContent">
                        <!-- Pestaña: Datos del Hotel -->
                        <div class="tab-pane fade show active" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                            @include('configuration.partials.hotel-settings')
                        </div>

                        <!-- Pestaña: Preferencias del Sistema -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                            @include('configuration.partials.system-preferences')
                        </div>

                        <!-- Pestaña: Roles y Permisos -->
                        <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="roles-tab">
                            @include('configuration.partials.roles-permissions')
                        </div>

                        <!-- Pestaña: Seguridad -->
                        <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                            @include('configuration.partials.security-settings')
                        </div>

                        <!-- Pestaña: Copias de Seguridad -->
                        <div class="tab-pane fade" id="backups" role="tabpanel" aria-labelledby="backups-tab">
                            @include('configuration.partials.backups')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear/editar roles -->
<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModalLabel">Crear Nuevo Rol</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="roleForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roleName">Nombre del Rol</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Permisos</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para asignar roles a usuarios -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" role="dialog" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Asignar Rol a Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignRoleForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="assignUser">Usuario</label>
                        <select class="form-control" id="assignUser" name="user_id" required>
                            <option value="">Seleccionar usuario</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assignRole">Rol</label>
                        <select class="form-control" id="assignRole" name="role_id" required>
                            <option value="">Seleccionar rol</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Asignar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/configuration.js') }}"></script>
@endpush



