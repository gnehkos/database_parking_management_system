<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Vehicle;
use App\Models\Payment;
use Illuminate\Http\Request;

class ParkingHistoryController extends Controller
{
    private function buildQuery(Request $request)
    {
        $period       = $request->input('period', 'all');
        $statusFilter = $request->input('status_filter', 'all');
        $typeFilter   = $request->input('type', 'all');
        $search       = $request->input('search');

        $query = Ticket::with('vehicle', 'slot', 'payment', 'staff')
            ->orderBy('entry_time', 'desc');

        if ($search) {
            $query->whereHas('vehicle', fn($q) => $q->where('plate_number', 'like', '%' . $search . '%'));
            return $query;
        }

        $now = now();
        switch ($period) {
            case 'today':   $query->whereDate('entry_time', $now->toDateString()); break;
            case '7days':   $query->where('entry_time', '>=', $now->copy()->subDays(7)); break;
            case 'month':   $query->whereMonth('entry_time', $now->month)->whereYear('entry_time', $now->year); break;
            case '3months': $query->where('entry_time', '>=', $now->copy()->subMonths(3)); break;
            case 'year':    $query->whereYear('entry_time', $now->year); break;
        }

        if ($statusFilter !== 'all') $query->where('status', $statusFilter);
        if ($typeFilter !== 'all')   $query->whereHas('vehicle', fn($q) => $q->where('vehicle_type', $typeFilter));

        return $query;
    }

    public function index(Request $request)
    {
        $totalSessions      = Ticket::count();
        $completedSessions  = Ticket::where('status', 'completed')->count();
        $totalRevenue       = Payment::sum('total_fee');

        $period       = $request->input('period', 'all');
        $statusFilter = $request->input('status_filter', 'all');
        $typeFilter   = $request->input('type', 'all');

        $tickets = $this->buildQuery($request)->paginate(20)->withQueryString();

        return view('history.index', compact(
            'tickets', 'totalSessions', 'completedSessions', 'totalRevenue',
            'period', 'statusFilter', 'typeFilter'
        ));
    }

    public function export(Request $request)
    {
        $tickets = $this->buildQuery($request)->get();

        $filename = 'history-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($tickets) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Ticket ID', 'Plate Number', 'Vehicle Type', 'Slot', 'Entry Time', 'Exit Time', 'Duration (min)', 'Fee (USD)', 'Payment Method', 'Status', 'Staff']);

            foreach ($tickets as $t) {
                $dur = null;
                if ($t->exit_time) {
                    $dur = \Carbon\Carbon::parse($t->entry_time)->diffInMinutes(\Carbon\Carbon::parse($t->exit_time));
                }
                fputcsv($handle, [
                    $t->ticket_id,
                    $t->vehicle->plate_number ?? 'No plate',
                    $t->vehicle->vehicle_type,
                    $t->slot->slot_number ?? 'N/A',
                    $t->entry_time,
                    $t->exit_time ?? '',
                    $dur ?? '',
                    $t->payment ? $t->payment->total_fee : '',
                    $t->payment ? $t->payment->payment_method : '',
                    $t->status,
                    $t->staff->full_name ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, $headers);
    }

    public function vehicleHistory(Request $request, $plate)
    {
        $vehicle = Vehicle::where('plate_number', $plate)->where('status', 'active')->firstOrFail();

        $sessions = Ticket::with('slot', 'payment', 'staff')
            ->where('vehicle_id', $vehicle->vehicle_id)
            ->orderBy('entry_time', 'desc')
            ->get();

        $totalSessions = $sessions->count();
        $totalMinutes  = $sessions->sum(function ($t) {
            return $t->exit_time
                ? \Carbon\Carbon::parse($t->entry_time)->diffInMinutes(\Carbon\Carbon::parse($t->exit_time))
                : 0;
        });
        $totalPaid = $sessions->sum(fn($t) => $t->payment?->total_fee ?? 0);
        $h = floor($totalMinutes / 60);
        $m = $totalMinutes % 60;

        return view('history.vehicle', compact('vehicle', 'sessions', 'totalSessions', 'totalPaid', 'h', 'm'));
    }
}
