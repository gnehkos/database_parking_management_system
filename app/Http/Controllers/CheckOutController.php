<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Payment;
use App\Models\ParkingSlot;
use App\Models\FeeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckOutController extends Controller
{
    public function index(Request $request)
    {
        $parkedVehicles = Ticket::where('status', 'active')
            ->with('vehicle', 'slot', 'feeRate', 'staff')
            ->orderBy('entry_time', 'desc')
            ->get()
            ->map(function ($ticket) {
                $hours = Carbon::parse($ticket->entry_time)->diffInMinutes(now()) / 60;
                $ticket->calculated_fee = $ticket->feeRate->calculateFee($hours);
                return $ticket;
            });

        $searchResult = null;
        $searchError  = null;

        if ($request->filled('ticket_search')) {
            $q = trim($request->ticket_search);

            $searchResult = Ticket::where('status', 'active')
                ->where('ticket_id', $q)
                ->with('vehicle', 'slot', 'feeRate')
                ->first();

            if (!$searchResult) {
                $searchResult = Ticket::where('status', 'active')
                    ->whereHas('vehicle', fn($query) => $query->where('plate_number', $q))
                    ->with('vehicle', 'slot', 'feeRate')
                    ->first();
            }

            if ($searchResult) {
                $hours = Carbon::parse($searchResult->entry_time)->diffInMinutes(now()) / 60;
                $searchResult->calculated_fee = $searchResult->feeRate->calculateFee($hours);
            } else {
                $searchError = 'No active parking found for "' . $q . '"';
            }
        }

        return view('checkout.index', compact('parkedVehicles', 'searchResult', 'searchError'));
    }

    public function payment($ticketId)
    {
        $ticket = Ticket::where('status', 'active')
            ->with('vehicle', 'slot', 'feeRate', 'staff')
            ->findOrFail($ticketId);

        $now          = now();
        $hours        = Carbon::parse($ticket->entry_time)->diffInMinutes($now) / 60;
        $fee          = $ticket->feeRate->calculateFee($hours);
        $diff         = Carbon::parse($ticket->entry_time)->diff($now);
        $durationText = ($diff->days ? $diff->days . 'd ' : '') . $diff->h . 'h ' . $diff->i . 'm';
        $khrAmount    = round($fee * FeeRate::KHR_RATE);

        return view('checkout.payment', compact('ticket', 'fee', 'durationText', 'hours', 'khrAmount'));
    }

    public function processPayment(Request $request, $ticketId)
    {
        $request->validate([
            'payment_method' => ['required', 'in:cash,card,qrScan'],
        ]);

        return DB::transaction(function () use ($request, $ticketId) {
            $ticket  = Ticket::where('status', 'active')->lockForUpdate()->findOrFail($ticketId);
            $feeRate = FeeRate::find($ticket->rate_id);
            $now     = now();

            $hours = Carbon::parse($ticket->entry_time)->diffInMinutes($now) / 60;
            $fee   = $feeRate->calculateFee($hours);

            $ticket->update(['exit_time' => $now, 'status' => 'completed']);

            ParkingSlot::where('slot_id', $ticket->slot_id)
                ->update(['status' => 'available', 'updated_at' => $now]);

            Payment::create([
                'ticket_id'      => $ticket->ticket_id,
                'staff_id'       => auth()->id(),
                'duration'       => round($hours, 2),
                'total_fee'      => $fee,
                'payment_method' => $request->payment_method,
                'paid_at'        => $now,
            ]);

            return redirect()->route('checkout.complete', $ticket->ticket_id);
        });
    }

    public function complete($ticketId)
    {
        $ticket = Ticket::with('vehicle', 'payment', 'staff')->findOrFail($ticketId);
        return view('checkout.complete', compact('ticket'));
    }

    public function cancelTicket(Request $request, $ticketId)
    {
        $ticket = Ticket::where('status', 'active')->findOrFail($ticketId);

        DB::transaction(function () use ($ticket) {
            $ticket->update(['status' => 'cancelled', 'exit_time' => now()]);
            ParkingSlot::where('slot_id', $ticket->slot_id)
                ->update(['status' => 'available', 'updated_at' => now()]);
        });

        return redirect()->route('slots.index')
            ->with('success', 'Ticket ' . $ticket->ticket_id . ' cancelled and slot freed.');
    }
}
