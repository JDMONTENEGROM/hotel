<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ocupación e Ingresos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h3 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .metrics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .metric-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 15px 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        
        .metric-item:first-child {
            border-left: none;
        }
        
        .metric-item:last-child {
            border-right: none;
        }
        
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 5px;
        }
        
        .metric-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .summary-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        
        .summary-section h4 {
            color: #007bff;
            margin-top: 0;
            margin-bottom: 15px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 5px 10px;
        }
        
        .summary-label {
            font-weight: bold;
            color: #333;
        }
        
        .summary-value {
            color: #666;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
        }
        
        .text-warning {
            color: #ffc107;
        }
        
        .text-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Reporte de Ocupación e Ingresos</h1>
        <h2>Período: {{ $period['start'] }} - {{ $period['end'] }}</h2>
        <p>Generado el {{ $generated_at }}</p>
    </div>

    <!-- Métricas Principales -->
    <div class="info-section">
        <h3>Métricas Principales</h3>
        <div class="metrics-grid">
            <div class="metric-item">
                <div class="metric-value">{{ $metrics['average_occupancy'] }}</div>
                <div class="metric-label">Ocupación Promedio</div>
            </div>
            <div class="metric-item">
                <div class="metric-value">{{ number_format($metrics['occupancy_rate'], 1) }}%</div>
                <div class="metric-label">Tasa de Ocupación</div>
            </div>
            <div class="metric-item">
                <div class="metric-value">${{ number_format($metrics['total_revenue'], 2) }}</div>
                <div class="metric-label">Ingresos Totales</div>
            </div>
            <div class="metric-item">
                <div class="metric-value">${{ number_format($metrics['average_daily_revenue'], 2) }}</div>
                <div class="metric-label">Ingresos Diarios Promedio</div>
            </div>
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="summary-section">
        <h4>Resumen Ejecutivo</h4>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total de Habitaciones:</span>
                <span class="summary-value">{{ $metrics['total_rooms'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Días del Período:</span>
                <span class="summary-value">{{ $metrics['total_days'] }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Ingreso Promedio por Transacción:</span>
                <span class="summary-value">${{ number_format($metrics['average_revenue_per_transaction'], 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Mayor Ocupación:</span>
                <span class="summary-value">{{ $metrics['max_occupancy']['count'] }} huéspedes ({{ $metrics['max_occupancy']['date'] }})</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Mayores Ingresos:</span>
                <span class="summary-value">${{ number_format($metrics['max_revenue']['amount'], 2) }} ({{ $metrics['max_revenue']['date'] }})</span>
            </div>
        </div>
    </div>

    <!-- Ocupación Diaria -->
    <div class="info-section">
        <h3>Ocupación Diaria</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Ocupación</th>
                    <th>Tasa de Ocupación</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($occupancy['daily'] as $day)
                <tr>
                    <td>{{ $day['date_formatted'] }}</td>
                    <td class="text-center">{{ $day['occupancy_count'] }}</td>
                    <td class="text-center">{{ $metrics['total_rooms'] > 0 ? number_format(($day['occupancy_count'] / $metrics['total_rooms']) * 100, 1) : 0 }}%</td>
                    <td class="text-center">
                        @if($day['occupancy_count'] >= $metrics['total_rooms'] * 0.8)
                            <span class="text-success">Alta</span>
                        @elseif($day['occupancy_count'] >= $metrics['total_rooms'] * 0.5)
                            <span class="text-warning">Media</span>
                        @else
                            <span class="text-danger">Baja</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Ingresos Diarios -->
    <div class="info-section">
        <h3>Ingresos Diarios</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Ingresos</th>
                    <th>Número de Pagos</th>
                    <th>Ingreso Promedio por Pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenue['daily'] as $day)
                <tr>
                    <td>{{ $day['date_formatted'] }}</td>
                    <td class="text-right">${{ number_format($day['revenue'], 2) }}</td>
                    <td class="text-center">{{ $day['payment_count'] }}</td>
                    <td class="text-right">
                        @if($day['payment_count'] > 0)
                            ${{ number_format($day['revenue'] / $day['payment_count'], 2) }}
                        @else
                            $0.00
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Análisis de Transacciones -->
    @if(count($occupancy['daily']) > 0)
    <div class="info-section page-break">
        <h3>Análisis Detallado de Transacciones</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Huésped</th>
                    <th>Habitación</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Días</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Pendiente</th>
                </tr>
            </thead>
            <tbody>
                @foreach($occupancy['daily'] as $day)
                    @foreach($day['transactions'] as $transaction)
                    <tr>
                        <td>{{ $day['date_formatted'] }}</td>
                        <td>{{ $transaction->customer->name ?? 'N/A' }}</td>
                        <td>{{ $transaction->room->number ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->check_in)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaction->check_out)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $transaction->getDateDifferenceWithPlural() }}</td>
                        <td class="text-right">${{ number_format($transaction->getTotalPrice(), 2) }}</td>
                        <td class="text-right">${{ number_format($transaction->getTotalPayment(), 2) }}</td>
                        <td class="text-right">
                            @php
                                $pending = $transaction->getTotalPrice() - $transaction->getTotalPayment();
                            @endphp
                            @if($pending > 0)
                                <span class="text-danger">${{ number_format($pending, 2) }}</span>
                            @else
                                <span class="text-success">$0.00</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Gestión Hotelera</p>
        <p>Para más información, contacte al administrador del sistema</p>
    </div>
</body>
</html>

