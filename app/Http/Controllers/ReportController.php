<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'month');
        $typeFilter = $request->input('type', 'all');
        $now = now();

        $startDate = match ($period) {
            'today' => $now->copy()->startOfDay(),
            '7days' => $now->copy()->subDays(7)->startOfDay(),
            'month' => $now->copy()->startOfMonth(),
            '3months' => $now->copy()->subMonths(3)->startOfDay(),
            '6months' => $now->copy()->subMonths(6)->startOfDay(),
            'year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };

        $dateRange = $startDate->format('M d') . ' - ' . $now->format('M d, Y');

        $ticketQuery = Ticket::where('status', 'completed')
            ->where('exit_time', '>=', $startDate);

        if ($typeFilter !== 'all') {
            $ticketQuery->whereHas('vehicle', fn ($q) => $q->where('vehicle_type', $typeFilter));
        }

        $ticketIds = $ticketQuery->pluck('ticket_id');

        $periodRevenue = Payment::whereIn('ticket_id', $ticketIds)->sum('total_fee');
        $todayRevenue = Payment::whereDate('paid_at', today())->sum('total_fee');
        $totalTransactions = $ticketIds->count();
        $avgPerSession = $totalTransactions > 0 ? round($periodRevenue / $totalTransactions, 2) : 0;

        $dailyRevenue = Payment::whereIn('ticket_id', $ticketIds)
            ->selectRaw('DATE(paid_at) as date, SUM(total_fee) as total')
            ->groupByRaw('DATE(paid_at)')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $vehicleTypeCounts = Ticket::where('tickets.status', 'completed')
            ->where('exit_time', '>=', $startDate)
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id')
            ->selectRaw('vehicles.vehicle_type, COUNT(*) as count')
            ->groupBy('vehicles.vehicle_type')
            ->pluck('count', 'vehicle_type')
            ->toArray();

        $totalTypeCount = array_sum($vehicleTypeCounts);

        return view('reports.index', compact(
            'period', 'typeFilter', 'dateRange', 'periodRevenue', 'todayRevenue',
            'totalTransactions', 'avgPerSession', 'dailyRevenue', 'vehicleTypeCounts', 'totalTypeCount'
        ));
    }
}
