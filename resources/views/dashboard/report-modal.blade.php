<!-- Modal para selección de reporte -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Generar Reporte de Ocupación e Ingresos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="reportType" class="form-label fw-bold">Tipo de Reporte</label>
                            <select class="form-select" id="reportType" name="report_type" required>
                                <option value="">Seleccionar tipo de reporte</option>
                                <option value="daily">Reporte Diario</option>
                                <option value="weekly">Reporte Semanal</option>
                                <option value="monthly">Reporte Mensual</option>
                                <option value="custom">Período Personalizado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="exportFormat" class="form-label fw-bold">Formato de Exportación</label>
                            <select class="form-select" id="exportFormat" name="export_format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fechas automáticas (se llenan según el tipo) -->
                    <div class="row" id="autoDatesSection">
                        <div class="col-md-6 mb-3">
                            <label for="startDate" class="form-label fw-bold">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="startDate" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="endDate" class="form-label fw-bold">Fecha de Fin</label>
                            <input type="date" class="form-control" id="endDate" name="end_date" required>
                        </div>
                    </div>

                    <!-- Información del período seleccionado -->
                    <div class="alert alert-info" id="periodInfo" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="periodText"></span>
                    </div>

                    <!-- Vista previa de métricas -->
                    <div id="metricsPreview" style="display: none;">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-eye me-2"></i>
                            Vista Previa de Métricas
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">Período</small>
                                        <div class="fw-bold" id="previewPeriod"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">Días</small>
                                        <div class="fw-bold" id="previewDays"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="card bg-light">
                                    <div class="card-body p-2 text-center">
                                        <small class="text-muted">Tipo</small>
                                        <div class="fw-bold" id="previewType"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="generateReportBtn" disabled>
                    <i class="fas fa-chart-line me-2"></i>Generar Reporte
                </button>
                <button type="button" class="btn btn-success" id="exportReportBtn" disabled>
                    <i class="fas fa-download me-2"></i>Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportTypeSelect = document.getElementById('reportType');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const periodInfo = document.getElementById('periodInfo');
    const periodText = document.getElementById('periodText');
    const metricsPreview = document.getElementById('metricsPreview');
    const generateReportBtn = document.getElementById('generateReportBtn');
    const exportReportBtn = document.getElementById('exportReportBtn');
    const reportForm = document.getElementById('reportForm');

    // Función para calcular fechas según el tipo de reporte
    function calculateDates(type) {
        const today = new Date();
        let startDate, endDate;

        switch(type) {
            case 'daily':
                startDate = new Date(today);
                endDate = new Date(today);
                break;
            case 'weekly':
                startDate = new Date(today);
                startDate.setDate(today.getDate() - today.getDay()); // Domingo
                endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 6); // Sábado
                break;
            case 'monthly':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'custom':
                // Para período personalizado, no se calculan fechas automáticamente
                return;
        }

        if (startDate && endDate) {
            startDateInput.value = startDate.toISOString().split('T')[0];
            endDateInput.value = endDate.toISOString().split('T')[0];
            updatePeriodInfo();
        }
    }

    // Función para actualizar la información del período
    function updatePeriodInfo() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        const reportType = reportTypeSelect.value;

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

            periodText.textContent = `Período: ${formatDate(start)} - ${formatDate(end)} (${days} días)`;
            periodInfo.style.display = 'block';

            // Actualizar vista previa
            document.getElementById('previewPeriod').textContent = `${formatDate(start)} - ${formatDate(end)}`;
            document.getElementById('previewDays').textContent = `${days} días`;
            document.getElementById('previewType').textContent = getReportTypeName(reportType);
            metricsPreview.style.display = 'block';

            // Habilitar botones
            generateReportBtn.disabled = false;
            exportReportBtn.disabled = false;
        } else {
            periodInfo.style.display = 'none';
            metricsPreview.style.display = 'none';
            generateReportBtn.disabled = true;
            exportReportBtn.disabled = true;
        }
    }

    // Función para formatear fechas
    function formatDate(date) {
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Función para obtener el nombre del tipo de reporte
    function getReportTypeName(type) {
        const types = {
            'daily': 'Diario',
            'weekly': 'Semanal',
            'monthly': 'Mensual',
            'custom': 'Personalizado'
        };
        return types[type] || type;
    }

    // Event listeners
    reportTypeSelect.addEventListener('change', function() {
        calculateDates(this.value);
    });

    startDateInput.addEventListener('change', updatePeriodInfo);
    endDateInput.addEventListener('change', updatePeriodInfo);

    // Generar reporte
    generateReportBtn.addEventListener('click', function() {
        const formData = new FormData(reportForm);
        
        fetch('{{ route("reports.generate") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar los datos del reporte en una nueva ventana o modal
                showReportResults(data.data);
            } else {
                alert('Error al generar el reporte');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al generar el reporte');
        });
    });

    // Exportar reporte
    exportReportBtn.addEventListener('click', function() {
        const formData = new FormData(reportForm);
        const exportFormat = document.getElementById('exportFormat').value;
        
        // Crear un formulario temporal para la descarga
        const tempForm = document.createElement('form');
        tempForm.method = 'POST';
        tempForm.action = '{{ route("reports.export") }}';
        tempForm.style.display = 'none';

        // Agregar campos del formulario
        for (let [key, value] of formData.entries()) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            tempForm.appendChild(input);
        }

        // Agregar token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        tempForm.appendChild(csrfInput);

        document.body.appendChild(tempForm);
        tempForm.submit();
        document.body.removeChild(tempForm);
    });

    // Función para mostrar los resultados del reporte
    function showReportResults(data) {
        // Crear modal para mostrar resultados
        const resultsModal = document.createElement('div');
        resultsModal.className = 'modal fade';
        resultsModal.innerHTML = `
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Reporte de Ocupación e Ingresos
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4>${data.metrics.average_occupancy}</h4>
                                        <small>Ocupación Promedio</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4>${data.metrics.occupancy_rate.toFixed(1)}%</h4>
                                        <small>Tasa de Ocupación</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>$${data.metrics.total_revenue.toLocaleString()}</h4>
                                        <small>Ingresos Totales</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4>$${data.metrics.average_daily_revenue.toLocaleString()}</h4>
                                        <small>Ingresos Diarios Promedio</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Ocupación Diaria</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Ocupación</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.occupancy.daily.map(day => `
                                                <tr>
                                                    <td>${day.date_formatted}</td>
                                                    <td>${day.occupancy_count}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Ingresos Diarios</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Ingresos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${data.revenue.daily.map(day => `
                                                <tr>
                                                    <td>${day.date_formatted}</td>
                                                    <td>$${day.revenue.toLocaleString()}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(resultsModal);
        const modal = new bootstrap.Modal(resultsModal);
        modal.show();

        // Limpiar el modal cuando se cierre
        resultsModal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(resultsModal);
        });
    }
});
</script>

