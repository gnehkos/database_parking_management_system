<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EntryExitLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('vehicle', 'slot', 'payment');

        $period = $request->input('period', 'today');
        $now = now();

        switch ($period) {
            case 'today':
                $query->whereDate('entry_time', $now->toDateString());
                $dateRange = 'Today, ' . $now->format('M d Y');
                break;
            case '7days':
                $query->where('entry_time', '>=', $now->copy()->subDays(7));
                $dateRange = $now->copy()->subDays(7)->format('M d') . ' - ' . $now->format('M d, Y');
                break;
            case 'month':
                $query->whereMonth('entry_time', $now->month)->whereYear('entry_time', $now->year);
                $dateRange = $now->format('F Y');
                break;
            case '3months':
                $query->where('entry_time', '>=', $now->copy()->subMonths(3));
                $dateRange = $now->copy()->subMonths(3)->format('M d') . ' - ' . $now->format('M d, Y');
                break;
            case '6months':
                $query->where('entry_time', '>=', $now->copy()->subMonths(6));
                $dateRange = $now->copy()->subMonths(6)->format('M d') . ' - ' . $now->format('M d, Y');
                break;
            case 'year':
                $query->whereYear('entry_time', $now->year);
                $dateRange = $now->format('Y');
                break;
            default:
                $query->whereDate('entry_time', $now->toDateString());
                $dateRange = 'Today';
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->whereHas('vehicle', fn ($q) => $q->where('vehicle_type', $request->type));
        }

        $tickets = $query->orderBy('entry_time', 'desc')->paginate(15)->withQueryString();
        $totalCount = $tickets->total();

        return view('logs.index', compact('tickets', 'totalCount', 'period', 'dateRange'));
    }

    public function show($ticketId)
    {
        $ticket = Ticket::with('vehicle', 'slot', 'payment', 'feeRate')->findOrFail($ticketId);

        $duration = null;
        if ($ticket->exit_time) {
            $diff = Carbon::parse($ticket->entry_time)->diff(Carbon::parse($ticket->exit_time));
            $duration = $diff->h . 'h ' . $diff->i . 'm';
        } elseif ($ticket->status === 'active') {
            $diff = Carbon::parse($ticket->entry_time)->diff(now());
            $duration = $diff->h . 'h ' . $diff->i . 'm';
        }

        return view('logs.show', compact('ticket', 'duration'));
    }
}
