@extends('template.master')
@section('title', 'Confirmación de Check-in')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border">
                <div class="card-header p-3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card-title">
                                <h4><i class="fas fa-clipboard-check me-2"></i>{{ __('messages.check_in_confirmation') }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 text-end">
                            <a href="{{ route('transaction.check-in.select-room', ['customer' => $customer->id]) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Información del Huésped</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Nombre</th>
                                            <td width="70%">{{ $customer->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Dirección</th>
                                            <td>{{ $customer->address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ocupación</th>
                                            <td>{{ $customer->job }}</td>
                                        </tr>
                                        <tr>
                                            <th>Género</th>
                                            <td>{{ $customer->gender }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Información de la Habitación</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Número</th>
                                            <td width="70%">{{ $room->number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tipo</th>
                                            <td>{{ $room->type->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Capacidad</th>
                                            <td>{{ $room->capacity }} personas</td>
                                        </tr>
                                        <tr>
                                            <th>Precio</th>
                                            <td>{{ Helper::convertToRupiah($room->price) }} / día</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('transaction.check-in.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="check_in" class="form-label">Fecha de Check-in *</label>
                                <input type="date" class="form-control @error('check_in') is-invalid @enderror" 
                                       id="check_in" name="check_in" value="{{ date('Y-m-d') }}" required>
                                @error('check_in')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check_out" class="form-label">Fecha de Check-out (Estimada) *</label>
                                <input type="date" class="form-control @error('check_out') is-invalid @enderror" 
                                       id="check_out" name="check_out" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                @error('check_out')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="how_many_person" class="form-label">Número de Personas *</label>
                                <input type="number" class="form-control @error('how_many_person') is-invalid @enderror" 
                                       id="how_many_person" name="how_many_person" value="1" min="1" max="{{ $room->capacity }}" required>
                                @error('how_many_person')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_amount" class="form-label">Pago Inicial (Opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" 
                                           id="payment_amount" name="payment_amount" value="0" min="0">
                                </div>
                                @error('payment_amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar Check-in
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validar que la fecha de check-out sea posterior a la de check-in
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const checkOutDate = new Date(checkOutInput.value);
            
            if (checkOutDate <= checkInDate) {
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        });
        
        checkOutInput.addEventListener('change', function() {
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(this.value);
            
            if (checkOutDate <= checkInDate) {
                alert('La fecha de check-out debe ser posterior a la fecha de check-in');
                const nextDay = new Date(checkInDate);
                nextDay.setDate(nextDay.getDate() + 1);
                this.value = nextDay.toISOString().split('T')[0];
            }
        });
    });
</script>
@endsection