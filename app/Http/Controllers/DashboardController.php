<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSlots = DB::table('parking_slots')->count();
        $occupied = DB::table('parking_slots')->where('status', 'occupied')->count();
        $available = DB::table('parking_slots')->where('status', 'available')->count();
        $maintenance = DB::table('parking_slots')->where('status', 'maintenance')->count();

        $todayRevenue = DB::table('payments')
            ->whereDate('paid_at', today())
            ->sum('total_fee');

        $todayTransactions = DB::table('payments')
            ->whereDate('paid_at', today())
            ->count();

        $activeTickets = DB::table('tickets')
            ->where('status', 'active')
            ->count();

        $recentActivity = DB::table('tickets')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.vehicle_id')
            ->join('parking_slots', 'tickets.slot_id', '=', 'parking_slots.slot_id')
            ->where('tickets.status', 'active')
            ->orderBy('tickets.entry_time', 'desc')
            ->limit(5)
            ->select(
                'vehicles.plate_number',
                'vehicles.vehicle_type',
                'parking_slots.slot_number',
                'tickets.entry_time',
                'tickets.status'
            )
            ->get();

        $hourlyTraffic = DB::table('tickets')
            ->whereDate('entry_time', today())
            ->selectRaw('HOUR(entry_time) as hour, COUNT(*) as count')
            ->groupByRaw('HOUR(entry_time)')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        $trafficData = [];
        for ($h = 6; $h <= 19; $h++) {
            $trafficData[$h] = $hourlyTraffic[$h] ?? 0;
        }

        return view('dashboard', compact(
            'totalSlots', 'occupied', 'available', 'maintenance',
            'todayRevenue', 'todayTransactions', 'activeTickets',
            'recentActivity', 'trafficData'
        ));
    }
}
