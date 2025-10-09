@extends('template.master')
@section('title', 'Gestión de Habitaciones')
@section('content')
    <div class="container-fluid">
        <!-- Add Room Button -->
        <div class="row mb-4">
            <div class="col-12">
                <button id="add-button" type="button" class="add-room-btn">
                    <i class="fas fa-plus"></i>
                    Añadir Nueva Habitación
                </button>
            </div>
        </div>

        <!-- Room Status Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Vista General de Estados</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse ($roomStatuses as $roomStatus)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="status-indicator 
                                                    @if(strpos(strtolower($roomStatus->name), 'vacant') !== false && strpos(strtolower($roomStatus->name), 'clean') !== false)
                                                        bg-success
                                                    @elseif(strpos(strtolower($roomStatus->name), 'occupied') !== false)
                                                        bg-danger
                                                    @elseif(strpos(strtolower($roomStatus->name), 'maintenance') !== false || strpos(strtolower($roomStatus->name), 'out of order') !== false)
                                                        bg-warning
                                                    @else
                                                        bg-info
                                                    @endif
                                                    me-3"></div>
                                                <div>
                                                    <h6 class="mb-0">{{ $roomStatus->name }}</h6>
                                                    <small class="text-muted">{{ $roomStatus->code }}</small>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge bg-secondary room-count" data-status-id="{{ $roomStatus->id }}">
                                                    Cargando...
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        No hay estados de habitación definidos.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="professional-table-container">
            <!-- Table Header -->
            <div class="table-header">
                <h4><i class="fas fa-bed me-2"></i>Gestión de Habitaciones</h4>
                <p>Gestiona las habitaciones de tu hotel, tipos y estado de disponibilidad</p>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <div class="filters-title">
                    <i class="fas fa-filter"></i>
                    Filtrar Habitaciones
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>Estado
                            </label>
                            <select id="status" class="form-select" aria-label="Choose status">
                                <option selected>Todos</option>
                                @forelse ($roomStatuses as $roomStatus)
                                    <option value="{{ $roomStatus->id }}">{{ $roomStatus->name }}</option>
                                @empty
                                    <option value="">No hay estados de habitación disponibles</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="mb-3">
                            <label for="type" class="form-label">
                                <i class="fas fa-home me-1"></i>Tipo
                            </label>
                            <select id="type" class="form-select" aria-label="Choose type">
                                <option selected>Todos</option>
                                @forelse ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @empty
                                    <option value="">No hay tipos de habitación disponibles</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="room-table" class="professional-table table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">
                                <i class="fas fa-hashtag me-1"></i>Número de Habitación
                            </th>
                            <th scope="col">
                                <i class="fas fa-home me-1"></i>Tipo
                            </th>
                            <th scope="col">
                                <i class="fas fa-users me-1"></i>Capacidad
                            </th>
                            <th scope="col">
                                <i class="fas fa-dollar-sign me-1"></i>Precio / Día
                            </th>
                            <th scope="col">
                                <i class="fas fa-info-circle me-1"></i>Estado
                            </th>
                            <th scope="col">
                                <i class="fas fa-cog me-1"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <h3><i class="fas fa-bed me-2"></i>Inventario de Habitaciones</h3>
            </div>
        </div>
    </div>

    <style>
        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }
        .room-status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }
    </style>
@endsection
