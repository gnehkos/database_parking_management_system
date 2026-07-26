<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function getPeriodStart(string $period)
    {
        $now = now();
        return match($period) {
            'today'   => $now->copy()->startOfDay(),
            '7days'   => $now->copy()->subDays(7),
            'month'   => $now->copy()->startOfMonth(),
            '3months' => $now->copy()->subMonths(3),
            '6months' => $now->copy()->subMonths(6),
            'year'    => $now->copy()->startOfYear(),
            default   => null,
        };
    }

    public function index(Request $request)
    {
        $period     = $request->input('period', 'year');
        $typeFilter = $request->input('type', 'all');
        $now        = now();
        $start      = $this->getPeriodStart($period);

        $dateRange = $start
            ? $start->format('M d, Y') . ' - ' . $now->format('M d, Y')
            : 'All time';

        $paymentQuery = Payment::where('payments.status', 'paid')
            ->join('tickets', 'payments.ticket_id', '=', 'tickets.ticket_id')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id');

        if ($start) {
            $paymentQuery->where('payments.paid_at', '>=', $start);
        }
        if ($typeFilter !== 'all') {
            $paymentQuery->where('vehicles.vehicle_type', $typeFilter);
        }

        $periodRevenue      = (clone $paymentQuery)->sum('payments.total_fee');
        $totalTransactions  = (clone $paymentQuery)->count('payments.payment_id');
        $avgPerSession      = $totalTransactions > 0 ? $periodRevenue / $totalTransactions : 0;

       $todayRevenue = Payment::where('payments.status', 'paid')
            ->whereDate('payments.paid_at', $now->toDateString())
            ->sum('payments.total_fee');

        $dailyRevenue = Payment::where('payments.status', 'paid')
            ->join('tickets', 'payments.ticket_id', '=', 'tickets.ticket_id')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id')
            ->when($start, fn($q) => $q->where('payments.paid_at', '>=', $start))
            ->when($typeFilter !== 'all', fn($q) => $q->where('vehicles.vehicle_type', $typeFilter))
            ->selectRaw('DATE(payments.paid_at) as date, SUM(payments.total_fee) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $vehicleTypeCounts = Ticket::join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id')
            ->join('payments', 'tickets.ticket_id', '=', 'payments.ticket_id')
            ->where('payments.status', 'paid')
            ->when($start, fn($q) => $q->where('payments.paid_at', '>=', $start))
            ->selectRaw('vehicles.vehicle_type, COUNT(tickets.ticket_id) as count')
            ->groupBy('vehicles.vehicle_type')
            ->pluck('count', 'vehicle_type')
            ->toArray();

        $totalTypeCount = array_sum($vehicleTypeCounts);

        $topVehicles = DB::select("
            SELECT
                v.plate_number,
                v.vehicle_type,
                (SELECT COUNT(*) FROM tickets t WHERE t.vehicle_id = v.vehicle_id) AS visit_count,
                (SELECT COALESCE(SUM(p.total_fee), 0)
                 FROM payments p
                 JOIN tickets t2 ON t2.ticket_id = p.ticket_id
                 WHERE t2.vehicle_id = v.vehicle_id AND p.status = 'paid') AS total_spent
            FROM vehicles v
            WHERE v.status = 'active'
            HAVING visit_count > 0
            ORDER BY visit_count DESC
            LIMIT 5
        ");

        return view('reports.index', compact(
            'period', 'typeFilter', 'dateRange',
            'periodRevenue', 'totalTransactions', 'avgPerSession', 'todayRevenue',
            'dailyRevenue', 'vehicleTypeCounts', 'totalTypeCount', 'topVehicles'
        ));
    }

    public function export(Request $request)
    {
        $period     = $request->input('period', 'year');
        $typeFilter = $request->input('type', 'all');
        $start      = $this->getPeriodStart($period);

        $payments = Payment::where('status', 'paid')
            ->join('tickets', 'payments.ticket_id', '=', 'tickets.ticket_id')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id')
            ->leftJoin('parking_slots', 'tickets.slot_id', '=', 'parking_slots.slot_id')
            ->when($start, fn($q) => $q->where('payments.paid_at', '>=', $start))
            ->when($typeFilter !== 'all', fn($q) => $q->where('vehicles.vehicle_type', $typeFilter))
            ->select(
                'payments.ticket_id',
                'vehicles.plate_number',
                'vehicles.vehicle_type',
                'parking_slots.slot_number',
                'payments.duration',
                'payments.total_fee',
                'payments.payment_method',
                'payments.paid_at'
            )
            ->orderBy('payments.paid_at', 'desc')
            ->get();

        $filename = 'report-' . $period . '-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($payments) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Ticket ID', 'Plate Number', 'Vehicle Type', 'Slot', 'Duration (min)', 'Fee (USD)', 'Payment Method', 'Paid At']);
            foreach ($payments as $p) {
                fputcsv($handle, [
                    $p->ticket_id,
                    $p->plate_number ?? 'No plate',
                    $p->vehicle_type,
                    $p->slot_number ?? 'N/A',
                    $p->duration,
                    $p->total_fee,
                    $p->payment_method,
                    $p->paid_at,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
