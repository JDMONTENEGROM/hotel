<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Mostrar el modal de selección de reporte
     */
    public function showModal()
    {
        return view('dashboard.report-modal');
    }

    /**
     * Generar reporte de ocupación e ingresos
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Obtener datos de ocupación
        $occupancyData = $this->getOccupancyData($startDate, $endDate);
        
        // Obtener datos de ingresos
        $revenueData = $this->getRevenueData($startDate, $endDate);

        // Calcular métricas clave
        $metrics = $this->calculateMetrics($occupancyData, $revenueData, $startDate, $endDate);

        return response()->json([
            'success' => true,
            'data' => [
                'occupancy' => $occupancyData,
                'revenue' => $revenueData,
                'metrics' => $metrics,
                'period' => [
                    'start' => $startDate->format('d/m/Y'),
                    'end' => $endDate->format('d/m/Y'),
                    'type' => $request->report_type
                ]
            ]
        ]);
    }

    /**
     * Exportar reporte a PDF
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,weekly,monthly,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Obtener datos
        $occupancyData = $this->getOccupancyData($startDate, $endDate);
        $revenueData = $this->getRevenueData($startDate, $endDate);
        $metrics = $this->calculateMetrics($occupancyData, $revenueData, $startDate, $endDate);

        // Generar PDF usando DomPDF
        $pdf = \PDF::loadView('reports.occupancy-revenue-pdf', [
            'occupancy' => $occupancyData,
            'revenue' => $revenueData,
            'metrics' => $metrics,
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'type' => $request->report_type
            ],
            'generated_at' => now()->format('d/m/Y H:i:s')
        ]);

        $filename = 'reporte_ocupacion_ingresos_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Obtener datos de ocupación
     */
    private function getOccupancyData($startDate, $endDate)
    {
        // Transacciones activas en el período
        $activeTransactions = Transaction::with(['customer', 'room', 'payment'])
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                      ->orWhereBetween('check_out', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('check_in', '<=', $startDate)
                            ->where('check_out', '>=', $endDate);
                      });
            })
            ->get();

        // Calcular ocupación por día
        $dailyOccupancy = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayOccupancy = $activeTransactions->filter(function($transaction) use ($currentDate) {
                return $currentDate->between($transaction->check_in, $transaction->check_out);
            });
            
            $dailyOccupancy[] = [
                'date' => $currentDate->format('Y-m-d'),
                'date_formatted' => $currentDate->format('d/m/Y'),
                'occupancy_count' => $dayOccupancy->count(),
                'transactions' => $dayOccupancy->values()
            ];
            
            $currentDate->addDay();
        }

        return [
            'daily' => $dailyOccupancy,
            'total_transactions' => $activeTransactions->count(),
            'average_daily_occupancy' => collect($dailyOccupancy)->avg('occupancy_count')
        ];
    }

    /**
     * Obtener datos de ingresos
     */
    private function getRevenueData($startDate, $endDate)
    {
        // Pagos realizados en el período
        $payments = Payment::with(['transaction.room', 'transaction.customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Ingresos por día
        $dailyRevenue = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dayPayments = $payments->filter(function($payment) use ($currentDate) {
                return $payment->created_at->format('Y-m-d') === $currentDate->format('Y-m-d');
            });
            
            $dailyRevenue[] = [
                'date' => $currentDate->format('Y-m-d'),
                'date_formatted' => $currentDate->format('d/m/Y'),
                'revenue' => $dayPayments->sum('price'),
                'payment_count' => $dayPayments->count(),
                'payments' => $dayPayments->values()
            ];
            
            $currentDate->addDay();
        }

        return [
            'daily' => $dailyRevenue,
            'total_revenue' => $payments->sum('price'),
            'total_payments' => $payments->count(),
            'average_daily_revenue' => collect($dailyRevenue)->avg('revenue')
        ];
    }

    /**
     * Calcular métricas clave
     */
    private function calculateMetrics($occupancyData, $revenueData, $startDate, $endDate)
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalRooms = Room::count();
        
        // Ocupación promedio
        $averageOccupancy = $occupancyData['average_daily_occupancy'];
        $occupancyRate = $totalRooms > 0 ? ($averageOccupancy / $totalRooms) * 100 : 0;
        
        // Ingresos promedio por transacción
        $averageRevenuePerTransaction = $revenueData['total_payments'] > 0 
            ? $revenueData['total_revenue'] / $revenueData['total_payments'] 
            : 0;
        
        // Ingresos promedio por día
        $averageDailyRevenue = $revenueData['average_daily_revenue'];
        
        // Días con mayor ocupación
        $maxOccupancyDay = collect($occupancyData['daily'])->max('occupancy_count');
        $maxOccupancyDate = collect($occupancyData['daily'])
            ->where('occupancy_count', $maxOccupancyDay)
            ->first();
        
        // Día con mayores ingresos
        $maxRevenueDay = collect($revenueData['daily'])->max('revenue');
        $maxRevenueDate = collect($revenueData['daily'])
            ->where('revenue', $maxRevenueDay)
            ->first();

        return [
            'total_rooms' => $totalRooms,
            'total_days' => $totalDays,
            'average_occupancy' => round($averageOccupancy, 2),
            'occupancy_rate' => round($occupancyRate, 2),
            'total_revenue' => $revenueData['total_revenue'],
            'average_daily_revenue' => round($averageDailyRevenue, 2),
            'average_revenue_per_transaction' => round($averageRevenuePerTransaction, 2),
            'max_occupancy' => [
                'count' => $maxOccupancyDay,
                'date' => $maxOccupancyDate['date_formatted'] ?? 'N/A'
            ],
            'max_revenue' => [
                'amount' => $maxRevenueDay,
                'date' => $maxRevenueDate['date_formatted'] ?? 'N/A'
            ]
        ];
    }
}

