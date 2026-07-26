<?php

namespace App\Http\Controllers;

use App\Models\ParkingZone;
use App\Models\ParkingSlot;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SlotMapController extends Controller
{
    public function index()
    {
        $zones = ParkingZone::with(['slots' => function ($q) {
            $q->orderByRaw("CAST(SUBSTRING(slot_number, 2) AS UNSIGNED)");
        }])->get();

        $totalSlots = ParkingSlot::count();
        $available = ParkingSlot::where('status', 'available')->count();
        $occupied = ParkingSlot::where('status', 'occupied')->count();
        $maintenance = ParkingSlot::where('status', 'maintenance')->count();
        $occupancyPercent = $totalSlots > 0 ? round(($occupied / $totalSlots) * 100) : 0;

        $activeTickets = Ticket::where('status', 'active')
            ->with('vehicle')
            ->get()
            ->keyBy('slot_id');

        return view('slots.index', compact('zones', 'totalSlots', 'available', 'occupied', 'maintenance', 'occupancyPercent', 'activeTickets'));
    }

    public function show(ParkingSlot $slot)
    {
        $slot->load('zone');

        $currentTicket = Ticket::where('slot_id', $slot->slot_id)
            ->where('status', 'active')
            ->with('vehicle')
            ->first();

        $usageHistory = Ticket::where('slot_id', $slot->slot_id)
            ->with('vehicle')
            ->orderBy('entry_time', 'desc')
            ->limit(10)
            ->get();

        return view('slots.show', compact('slot', 'currentTicket', 'usageHistory'));
    }

    public function updateStatus(Request $request, ParkingSlot $slot)
    {
        $request->validate([
            'status' => ['required', 'in:available,maintenance'],
        ]);

        $slot->update(['status' => $request->status, 'updated_at' => now()]);

        return redirect()->route('slots.show', $slot)->with('success', 'Slot status updated.');
    }
}
