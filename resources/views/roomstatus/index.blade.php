@extends('template.master')
@section('title', 'Estado de Habitaciones')
@section('content')
    <div class="container-fluid">
        <!-- Add Status Button -->
        <div class="row mb-4">
            <div class="col-12">
                <button id="add-button" type="button" class="add-item-btn">
                    <i class="fas fa-plus"></i>
                    Añadir Nuevo Estado
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="professional-table-container">
            <!-- Table Header -->
            <div class="table-header">
                <h4><i class="fas fa-toggle-on me-2"></i>Gestión de Estados de Habitación</h4>
                <p>Gestiona los estados de disponibilidad de habitaciones y sus códigos</p>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table id="roomstatus-table" class="professional-table table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">
                                <i class="fas fa-hashtag me-1"></i>#
                            </th>
                            <th scope="col">
                                <i class="fas fa-tag me-1"></i>Nombre
                            </th>
                            <th scope="col">
                                <i class="fas fa-code me-1"></i>Código
                            </th>
                            <th scope="col">
                                <i class="fas fa-info-circle me-1"></i>Información
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
                <h3><i class="fas fa-toggle-on me-2"></i>Estado de Habitaciones</h3>
            </div>
        </div>
    </div>
@endsection
