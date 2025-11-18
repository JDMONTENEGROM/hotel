$(document).ready(function() {
    // Configuración de CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ===== DATOS DEL HOTEL =====
    $('#hotelSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("configuration.hotel-settings") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += `• ${response.errors[key][0]}\n`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar los datos del hotel'
                    });
                }
            }
        });
    });

    // ===== PREFERENCIAS DEL SISTEMA =====
    $('#systemPreferencesForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("configuration.system-preferences") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += `• ${response.errors[key][0]}\n`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar las preferencias del sistema'
                    });
                }
            }
        });
    });

    // ===== CONFIGURACIÓN DE SEGURIDAD =====
    $('#securitySettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("configuration.security-settings") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += `• ${response.errors[key][0]}\n`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al actualizar la configuración de seguridad'
                    });
                }
            }
        });
    });

    // ===== COPIAS DE SEGURIDAD =====
    $('#createBackupBtn').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Creando...');
        
        $.ajax({
            url: '{{ route("configuration.backups.create") }}',
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Recargar la página para mostrar el nuevo backup
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al crear la copia de seguridad'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // ===== ROLES Y PERMISOS =====
    $('#roleForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("configuration.roles.create") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#roleModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMessage += `• ${response.errors[key][0]}\n`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de validación',
                        text: errorMessage
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al crear el rol'
                    });
                }
            }
        });
    });

    $('#assignRoleForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("configuration.roles.assign") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#assignRoleModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al asignar el rol'
                });
            }
        });
    });

    // ===== FUNCIONES GLOBALES =====
    window.deleteBackup = function(backupId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/configuration/backups/${backupId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Error al eliminar la copia de seguridad'
                        });
                    }
                });
            }
        });
    };

    window.deleteRole = function(roleId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/configuration/roles/${roleId}`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Error al eliminar el rol'
                        });
                    }
                });
            }
        });
    };

    window.editRole = function(roleId) {
        // Implementar edición de roles
        Swal.fire({
            icon: 'info',
            title: 'Función en desarrollo',
            text: 'La edición de roles estará disponible próximamente'
        });
    };

    window.manageUserRoles = function(userId) {
        // Implementar gestión de roles de usuario
        Swal.fire({
            icon: 'info',
            title: 'Función en desarrollo',
            text: 'La gestión de roles de usuario estará disponible próximamente'
        });
    };

    // Limpiar formularios al cerrar modales
    $('#roleModal').on('hidden.bs.modal', function() {
        $('#roleForm')[0].reset();
        $('#roleModalLabel').text('Crear Nuevo Rol');
    });

    $('#assignRoleModal').on('hidden.bs.modal', function() {
        $('#assignRoleForm')[0].reset();
    });

    // Manejar archivos seleccionados
    $('.custom-file-input').on('change', function() {
        const fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
});



