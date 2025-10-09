@extends('template.master')
@section('title', 'Realizar Check-in')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border">
                <div class="card-header p-3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card-title">
                                <h4><i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.check_in') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>{{ __('messages.search_guest') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form id="searchGuestForm" method="GET" action="{{ route('transaction.check-in.search') }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="document" class="form-label">{{ __('messages.guest_document') }}</label>
                                                    <input type="text" class="form-control" id="document" name="document" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-2"></i>{{ __('messages.search') }}
                                                </button>
                                                <a href="{{ route('transaction.check-in.create') }}" class="btn btn-success ms-2">
                                                    <i class="fas fa-user-plus me-2"></i>{{ __('messages.register_new_guest') }}
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4" id="guestResultContainer" style="display: none;">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('messages.guest_details') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div id="guestDetails">
                                        <!-- Los detalles del huésped se cargarán aquí mediante AJAX -->
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" id="selectRoomBtn" class="btn btn-primary">
                                            <i class="fas fa-door-open me-2"></i>{{ __('messages.select_room') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    $(document).ready(function() {
        $('#searchGuestForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#guestDetails').html(`
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Nombre:</strong> ${response.customer.name}</p>
                                    <p><strong>Dirección:</strong> ${response.customer.address}</p>
                                    <p><strong>Trabajo:</strong> ${response.customer.job}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Fecha de nacimiento:</strong> ${response.customer.birthdate}</p>
                                    <p><strong>Género:</strong> ${response.customer.gender}</p>
                                    <p><strong>ID:</strong> ${response.customer.id}</p>
                                </div>
                            </div>
                        `);
                        
                        $('#selectRoomBtn').attr('href', `/transaction/check-in/select-room/${response.customer.id}`);
                        $('#guestResultContainer').show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("messages.guest_not_found") }}',
                            text: '{{ __("messages.register_new_guest") }}',
                            showCancelButton: true,
                            confirmButtonText: '{{ __("messages.register_new_guest") }}',
                            cancelButtonText: '{{ __("messages.cancel") }}'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route("transaction.check-in.create") }}';
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error al buscar el huésped'
                    });
                }
            });
        });
    });
</script>
@endsection