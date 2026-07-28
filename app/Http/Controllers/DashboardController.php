<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Payment;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'today');
        $now    = now();

        $periodStart = match($period) {
            'week'  => $now->copy()->startOfWeek(),
            'month' => $now->copy()->startOfMonth(),
            'year'  => $now->copy()->startOfYear(),
            default => $now->copy()->startOfDay(),
        };

        $allSlots   = ParkingSlot::all();
        $totalSlots = $allSlots->count();

        $activeTickets = Ticket::where('status', 'active')
            ->with('vehicle', 'slot')
            ->get();

        $occupied    = $activeTickets->count();
        $maintenance = $allSlots->where('status', 'maintenance')->count();
        $available   = $totalSlots - $occupied - $maintenance;

        $periodRevenue      = Payment::where('paid_at', '>=', $periodStart)->sum('total_fee');
        $periodTransactions = Payment::where('paid_at', '>=', $periodStart)->count();

        $trafficRaw = Ticket::whereDate('entry_time', $now->toDateString())
            ->selectRaw('HOUR(entry_time) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour');

        $trafficData = [];
        for ($h = 0; $h < 24; $h++) {
            $trafficData[$h] = $trafficRaw[$h] ?? 0;
        }

        $recentActivity = $activeTickets->take(6)->map(function ($ticket) {
            return (object)[
                'plate_number' => $ticket->vehicle->plate_number,
                'vehicle_type' => $ticket->vehicle->vehicle_type,
                'entry_time'   => $ticket->entry_time,
                'slot_number'  => $ticket->slot->slot_number ?? 'N/A',
            ];
        });

        return view('dashboard', compact(
            'totalSlots', 'occupied', 'available', 'maintenance',
            'periodRevenue', 'periodTransactions',
            'activeTickets', 'trafficData', 'recentActivity', 'period'
        ));
    }
}
