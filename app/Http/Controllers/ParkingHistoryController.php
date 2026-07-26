<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $totalSessions = Ticket::count();
        $completedSessions = Ticket::where('status', 'completed')->count();
        $totalRevenue = Payment::sum('total_fee');

        $period = $request->input('period', 'all');
        $statusFilter = $request->input('status_filter', 'all');
        $typeFilter = $request->input('type', 'all');

        $query = Ticket::with('vehicle', 'slot', 'payment', 'staff')
            ->orderBy('entry_time', 'desc');

        $now = now();
        switch ($period) {
            case 'today':
                $query->whereDate('entry_time', $now->toDateString());
                break;
            case '7days':
                $query->where('entry_time', '>=', $now->copy()->subDays(7));
                break;
            case 'month':
                $query->whereMonth('entry_time', $now->month)->whereYear('entry_time', $now->year);
                break;
            case '3months':
                $query->where('entry_time', '>=', $now->copy()->subMonths(3));
                break;
            case 'year':
                $query->whereYear('entry_time', $now->year);
                break;
        }

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($typeFilter !== 'all') {
            $query->whereHas('vehicle', fn($q) => $q->where('vehicle_type', $typeFilter));
        }

        $tickets = $query->paginate(20)->withQueryString();

        return view('history.index', compact(
            'tickets', 'totalSessions', 'completedSessions', 'totalRevenue',
            'period', 'statusFilter', 'typeFilter'
        ));
    }

    public function vehicleHistory(Request $request, $plate)
    {
        $vehicle = Vehicle::where('plate_number', $plate)->where('status', 'active')->firstOrFail();

        $sessions = Ticket::with('slot', 'payment', 'staff')
            ->where('vehicle_id', $vehicle->vehicle_id)
            ->orderBy('entry_time', 'desc')
            ->get();

        $totalSessions = $sessions->count();
        $totalMinutes = $sessions->sum(function ($t) {
            if ($t->exit_time) {
                return \Carbon\Carbon::parse($t->entry_time)->diffInMinutes(\Carbon\Carbon::parse($t->exit_time));
            }
            return 0;
        });
        $totalPaid = $sessions->sum(fn($t) => $t->payment?->total_fee ?? 0);
        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;

        return view('history.vehicle', compact('vehicle', 'sessions', 'totalSessions', 'totalPaid', 'h', 'm'));
    }
}
