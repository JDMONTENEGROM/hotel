@extends('template.master')
@section('title', 'Instalaciones')
@section('content')
    <div class="container-fluid">
        <!-- Encabezado -->
        <div class="professional-table-container">
            <div class="table-header">
                <h4><i class="fas fa-concierge-bell me-2"></i>Gestión de Instalaciones</h4>
                <p>Lista de instalaciones/servicios disponibles en el hotel</p>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="professional-table table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col"><i class="fas fa-hashtag me-1"></i>#</th>
                            <th scope="col"><i class="fas fa-tag me-1"></i>Nombre</th>
                            <th scope="col"><i class="fas fa-info-circle me-1"></i>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $index => $facility)
                            <tr>
                                <td>{{ $facilities->firstItem() + $index }}</td>
                                <td>{{ $facility->name }}</td>
                                <td>{{ $facility->detail }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay instalaciones registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-end">
                {{ $facilities->links() }}
            </div>

            <!-- Pie -->
            <div class="table-footer">
                <h3><i class="fas fa-concierge-bell me-2"></i>Instalaciones</h3>
            </div>
        </div>
    </div>
@endsection
{{--
@section('footer')
<script>
    $('.delete').click(function() {
        var room_id = $(this).attr('room-id');
        var room_name = $(this).attr('room-name');
        var room_url = $(this).attr('room-url');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "Room number " + room_name + " will be deleted, You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel! ',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                id = "#delete-room-form-" + room_id
                console.log(id)
                $(id).submit();
            }
        })
    });

</script>
@endsection --}}
