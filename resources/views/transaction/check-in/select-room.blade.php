@extends('template.master')
@section('title', 'Seleccionar Habitación')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border">
                <div class="card-header p-3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="card-title">
                                <h4><i class="fas fa-door-open me-2"></i>{{ __('messages.select_room') }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 text-end">
                            <a href="{{ route('transaction.check-in.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5>Información del Huésped:</h5>
                                <p><strong>Nombre:</strong> {{ $customer->name }}</p>
                                <p><strong>Dirección:</strong> {{ $customer->address }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3">Habitaciones Disponibles</h5>
                            
                            <div class="row">
                                @forelse ($rooms as $room)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 room-card">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0">Habitación {{ $room->number }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <span class="badge bg-info">{{ $room->type->name }}</span>
                                                <span class="badge bg-success">{{ $room->roomStatus->name }}</span>
                                            </div>
                                            <p><strong>Capacidad:</strong> {{ $room->capacity }} personas</p>
                                            <p><strong>Precio:</strong> {{ Helper::convertToRupiah($room->price) }} / día</p>
                                            <p><strong>Vista:</strong> {{ $room->view }}</p>
                                            
                                            <div class="text-center mt-3">
                                                <a href="{{ route('transaction.check-in.confirmation', ['customer' => $customer->id, 'room' => $room->id]) }}" 
                                                   class="btn btn-primary">
                                                    <i class="fas fa-check-circle me-2"></i>Seleccionar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        No hay habitaciones disponibles en este momento.
                                    </div>
                                </div>
                                @endforelse
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
<style>
    .room-card {
        transition: transform 0.3s;
        border: 1px solid #dee2e6;
    }
    
    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endsection