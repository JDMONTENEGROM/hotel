$(function () {
    const currentRoute = window.location.pathname;
    if (!currentRoute.split("/").includes("room")) return;
    
    // Cargar conteo de habitaciones por estado
    function loadRoomStatusCounts() {
        $('.room-count').each(function() {
            const statusId = $(this).data('status-id');
            const countElement = $(this);
            
            $.ajax({
                url: '/room',
                type: 'GET',
                data: {
                    get_status_count: true,
                    status_id: statusId
                },
                success: function(response) {
                    countElement.text(response.count + ' habitaciones');
                },
                error: function() {
                    countElement.text('Error');
                }
            });
        });
    }
    
    // Cargar conteos iniciales
    loadRoomStatusCounts();

    const datatable = $("#room-table").DataTable({
        language: {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "No hay datos disponibles en la tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        processing: true,
        serverSide: true,
        ajax: {
            url: `/room`,
            type: "GET",
            data: function (d) {
                d.status = $("#status").val();
                d.type = $("#type").val();
            },
            error: function (xhr, status, error) {},
        },
        columns: [
            {
                name: "number",
                data: "number",
            },
            {
                name: "type",
                data: "type",
            },
            {
                name: "capacity",
                data: "capacity",
            },
            {
                name: "price",
                data: "price",
                render: function (price) {
                    return `<div>${new Intl.NumberFormat().format(
                        price
                    )}</div>`;
                },
            },
            {
                name: "status",
                data: "status",
                render: function (status, type, row) {
                    let badgeClass = 'bg-info';
                    
                    if (status.toLowerCase().includes('vacant') && status.toLowerCase().includes('clean')) {
                        badgeClass = 'bg-success';
                    } else if (status.toLowerCase().includes('occupied')) {
                        badgeClass = 'bg-danger';
                    } else if (status.toLowerCase().includes('maintenance') || status.toLowerCase().includes('out of order')) {
                        badgeClass = 'bg-warning';
                    }
                    
                    return `<span class="badge ${badgeClass} room-status-badge">${status}</span>`;
                }
            },
            {
                name: "id",
                data: "id",
                render: function (roomId) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-light btn-sm rounded shadow-sm border"
                                data-action="edit-room" data-room-id="${roomId}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Editar habitación">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-light btn-sm rounded shadow-sm border"
                                data-action="change-status" data-room-id="${roomId}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Cambiar estado">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <form class="btn btn-sm delete-room" method="POST"
                                id="delete-room-form-${roomId}"
                                action="/room/${roomId}">
                                <a class="btn btn-light btn-sm rounded shadow-sm border delete"
                                    href="#" room-id="${roomId}" room-role="room"                                 data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Eliminar habitación">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </form>
                            <a class="btn btn-light btn-sm rounded shadow-sm border"
                                href="/room/${roomId}"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Detalle de habitación">
                                <i class="fas fa-info-circle"></i>
                            </a>
                        </div>
                    `;
                },
            },
        ],
        drawCallback: function() {
            // Recargar tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });

    const modal = new bootstrap.Modal($("#main-modal"), {
        backdrop: true,
        keyboard: true,
        focus: true,
    });
        
    // Evento para cambiar el estado de una habitación
    $(document).on('click', '[data-action="change-status"]', async function() {
        const roomId = $(this).data('room-id');
        modal.show();
        
        $('#main-modal .modal-title').text('Cambiar Estado de Habitación');
        $('#main-modal .modal-body').html(`
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando estados disponibles...</p>
            </div>
        `);
        
        try {
            // Obtener los estados disponibles
            const response = await $.get('/roomstatus');
            const roomResponse = await $.get(`/room/${roomId}/edit`);
            
            if (!response || !roomResponse) return;
            
            const roomData = roomResponse.room;
            const roomStatuses = response;
            
            let statusOptions = '';
            roomStatuses.forEach(status => {
                const selected = (roomData.room_status_id == status.id) ? 'selected' : '';
                statusOptions += `<option value="${status.id}" ${selected}>${status.name}</option>`;
            });
            
            $('#main-modal .modal-body').html(`
                <form id="change-room-status-form" action="/room/${roomId}" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label for="room_status_id" class="form-label">Estado de la Habitación</label>
                        <select class="form-select" id="room_status_id" name="room_status_id" required>
                            ${statusOptions}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Número de Habitación</label>
                        <input type="text" class="form-control" value="${roomData.number}" disabled>
                    </div>
                    <input type="hidden" name="type_id" value="${roomData.type_id}">
                    <input type="hidden" name="number" value="${roomData.number}">
                    <input type="hidden" name="capacity" value="${roomData.capacity}">
                    <input type="hidden" name="price" value="${roomData.price}">
                </form>
            `);
            
            // Mostrar botón para guardar
            $('#main-modal .modal-footer').html(`
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="save-room-status">Guardar Cambios</button>
            `);
            
        } catch (error) {
            console.error('Error:', error);
            $('#main-modal .modal-body').html(`
                <div class="alert alert-danger">
                    Error al cargar los datos. Por favor, inténtelo de nuevo.
                </div>
            `);
        }
    });
    
    // Guardar cambio de estado
    $(document).on('click', '#save-room-status', function() {
        $('#change-room-status-form').submit();
    });
    
    // Manejar envío del formulario de cambio de estado
    $(document).on('submit', '#change-room-status-form', async function(e) {
        e.preventDefault();
        
        try {
            const response = await $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Estado de habitación actualizado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
                
                modal.hide();
                datatable.ajax.reload();
                loadRoomStatusCounts(); // Actualizar contadores
            }
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Error al actualizar el estado',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });

    $(document)
        .on("click", ".delete", function () {
            var room_id = $(this).attr("room-id");
            var room_name = $(this).attr("room-name");
            var room_url = $(this).attr("room-url");
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: "¿Estás seguro?",
                    text: "La habitación será eliminada, ¡No podrás revertir esto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "¡Sí, elimínala!",
                    cancelButtonText: "No, cancelar! ",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        $(`#delete-room-form-${room_id}`).submit();
                    }
                });
        })
        .on("click", "#add-button", async function () {
            modal.show();

            $("#main-modal .modal-body").html(`Obteniendo datos`);

            const response = await $.get(`/room/create`);
            if (!response) return;

            $("#main-modal .modal-title").text("Crear nueva habitación");
            $("#main-modal .modal-body").html(response.view);
            $(".select2").select2();
        })
        .on("click", "#btn-modal-save", function () {
            $("#form-save-room").submit();
        })
        .on("submit", "#form-save-room", async function (e) {
            e.preventDefault();
            CustomHelper.clearError();
            $("#btn-modal-save").attr("disabled", true);
            try {
                const response = await $.ajax({
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    method: $(this).attr("method"),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });

                if (!response) return;

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                });

                modal.hide();
                datatable.ajax.reload();
            } catch (e) {
                if (e.status === 422) {
                    console.log(e);
                    Swal.fire({
                        icon: "error",
                        title: "Ups...",
                        text: e.responseJSON.message,
                    });
                    CustomHelper.errorHandlerForm(e);
                }
            } finally {
                $("#btn-modal-save").attr("disabled", false);
            }
        })
        .on("click", '[data-action="edit-room"]', async function () {
            modal.show();

            $("#main-modal .modal-body").html(`Obteniendo datos`);

            const roomId = $(this).data("room-id");

            const response = await $.get(`/room/${roomId}/edit`);
            if (!response) return;

            $("#main-modal .modal-title").text("Editar habitación");
            $("#main-modal .modal-body").html(response.view);
            $(".select2").select2();
        })
        .on("submit", ".delete-room", async function (e) {
            e.preventDefault();

            try {
                const response = await $.ajax({
                    url: $(this).attr("action"),
                    data: $(this).serialize(),
                    method: $(this).attr("method"),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });

                if (!response) return;

                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                });

                datatable.ajax.reload();
            } catch (e) {}
        })
        .on("change", "#status", function () {
            datatable.ajax.reload();
        })
        .on("change", "#type", function () {
            datatable.ajax.reload();
        });

    // Actualizar contadores cuando cambia el estado
    $(document).on('change', '#room_status_id', function() {
        loadRoomStatusCounts();
    });
});
