<?php

namespace App\Http\Controllers;

use App\Models\ParkingZone;
use App\Models\ParkingSlot;
use App\Models\Ticket;

class SlotMapController extends Controller
{
    public function index()
    {
        $zones = ParkingZone::with(['slots' => fn($q) => $q->orderBy('slot_number')])->get();

        $activeTickets = Ticket::where('status', 'active')
            ->with('vehicle')
            ->get()
            ->keyBy('slot_id');

        $total       = $zones->sum(fn($z) => $z->slots->count());
        $maintenance = $zones->sum(fn($z) => $z->slots->where('status', 'maintenance')->count());
        $occupied    = $activeTickets->count();
        $available   = $total - $maintenance - $occupied;

        return view('slots.index', compact(
            'zones', 'activeTickets', 'total', 'occupied', 'available', 'maintenance'
        ));
    }

    public function show(ParkingSlot $slot)
    {
        $slot->load('zone');

        $activeTicket = null;
        $realStatus   = $slot->status;

        if ($slot->status !== 'maintenance') {
            $activeTicket = Ticket::where('slot_id', $slot->slot_id)
                ->where('status', 'active')
                ->with('vehicle', 'staff', 'feeRate')
                ->first();

            $realStatus = $activeTicket ? 'occupied' : 'available';
        }

        $totalUses = Ticket::where('slot_id', $slot->slot_id)->count();

        return view('slots.show', compact('slot', 'activeTicket', 'realStatus', 'totalUses'));
    }

    public function updateStatus(ParkingSlot $slot)
    {
        if ($slot->status === 'maintenance') {
            $slot->update(['status' => 'available', 'updated_at' => now()]);
            $msg = $slot->slot_number . ' marked as available.';
        } else {
            $hasActiveTicket = Ticket::where('slot_id', $slot->slot_id)
                ->where('status', 'active')
                ->exists();

            if ($hasActiveTicket) {
                return redirect()->route('slots.show', $slot->slot_id)
                    ->with('error', 'Cannot mark as maintenance — slot is currently occupied.');
            }

            $slot->update(['status' => 'maintenance', 'updated_at' => now()]);
            $msg = $slot->slot_number . ' marked as under maintenance.';
        }

        return redirect()->route('slots.show', $slot->slot_id)
            ->with('success', $msg);
    }
}