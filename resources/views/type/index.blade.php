@extends('template.master')
@section('title', 'Tipos de Habitación')
@section('content')
    <div class="container-fluid">
        <!-- Add Type Button -->
        <div class="row mb-4">
            <div class="col-12">
                <button id="add-button" type="button" class="add-type-btn">
                    <i class="fas fa-plus"></i>
                    Añadir Nuevo Tipo
                </button>
            </div>
        </div>

        <!-- Professional Table Container -->
        <div class="professional-table-container">
            <!-- Table Header -->
            <div class="table-header">
                <h4><i class="fas fa-home me-2"></i>Gestión de Tipos de Habitación</h4>
                <p>Gestiona diferentes tipos de habitaciones y su información</p>
            </div>

            <!-- Professional Table -->
            <div class="table-responsive">
                <table id="type-table" class="professional-table table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th scope="col">
                                <i class="fas fa-hashtag me-1"></i>#
                            </th>
                            <th scope="col">
                                <i class="fas fa-tag me-1"></i>Nombre
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
                <h3><i class="fas fa-home me-2"></i>Tipos de Habitación</h3>
            </div>
        </div>
    </div>
@endsection
