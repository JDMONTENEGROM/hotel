<div class="p-4">
    <h5 class="mb-4">
        <i class="fas fa-database text-primary mr-2"></i>
        Copias de Seguridad
    </h5>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <button type="button" class="btn btn-success" id="createBackupBtn">
                <i class="fas fa-plus mr-1"></i>
                Crear Copia de Seguridad Manual
            </button>
        </div>
        <div class="col-md-6 text-right">
            <div class="form-group mb-0">
                <label for="backupFrequency" class="mr-2">Programar copias automáticas:</label>
                <select class="form-control d-inline-block w-auto" id="backupFrequency">
                    <option value="">Desactivado</option>
                    <option value="daily">Diario</option>
                    <option value="weekly">Semanal</option>
                    <option value="monthly">Mensual</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Listado de Copias de Seguridad -->
    <div class="table-responsive">
        <table class="table table-striped" id="backupsTable">
            <thead>
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Tipo</th>
                    <th>Frecuencia</th>
                    <th>Tamaño</th>
                    <th>Creado por</th>
                    <th>Fecha de Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td>
                        <i class="fas fa-file-archive mr-2"></i>
                        {{ $backup->filename }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $backup->type == 'manual' ? 'primary' : 'success' }}">
                            {{ ucfirst($backup->type) }}
                        </span>
                    </td>
                    <td>
                        @if($backup->frequency)
                        <span class="badge badge-info">{{ ucfirst($backup->frequency) }}</span>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $backup->formatted_file_size }}</td>
                    <td>{{ $backup->user->name }}</td>
                    <td>{{ $backup->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('configuration.backups.download', $backup) }}" class="btn btn-sm btn-primary" title="Descargar">
                            <i class="fas fa-download"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteBackup({{ $backup->id }})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        <i class="fas fa-database fa-2x mb-2"></i><br>
                        No hay copias de seguridad disponibles
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @if($backups->hasPages())
    <div class="d-flex justify-content-center">
        {{ $backups->links() }}
    </div>
    @endif
    
    <!-- Información sobre Copias de Seguridad -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle mr-2"></i>Información Importante:</h6>
                <ul class="mb-0">
                    <li>Las copias de seguridad incluyen toda la base de datos del sistema</li>
                    <li>Se recomienda crear copias de seguridad regulares para proteger la información</li>
                    <li>Las copias automáticas se ejecutarán según la frecuencia seleccionada</li>
                    <li>Los archivos antiguos se eliminarán automáticamente para ahorrar espacio</li>
                </ul>
            </div>
        </div>
    </div>
</div>



