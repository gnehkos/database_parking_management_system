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
        $zones = ParkingZone::with('slots')->get();

        $activeTickets = Ticket::where('status', 'active')
            ->with('vehicle')
            ->get()
            ->keyBy('slot_id');

        foreach ($zones as $zone) {
            foreach ($zone->slots as $slot) {
                if ($slot->status === 'maintenance') {
                    $slot->real_status = 'maintenance';
                } else {
                    $slot->real_status = $activeTickets->has($slot->slot_id) ? 'occupied' : 'available';
                }
                $slot->active_ticket = $activeTickets->get($slot->slot_id);
            }
        }

        $allSlots = $zones->flatMap(fn($z) => $z->slots);
        $total = $allSlots->count();
        $occupied = $allSlots->filter(fn($s) => $s->real_status === 'occupied')->count();
        $available = $allSlots->filter(fn($s) => $s->real_status === 'available')->count();
        $maintenance = $allSlots->filter(fn($s) => $s->real_status === 'maintenance')->count();
        $occupancyPercent = $total > 0 ? round(($occupied / $total) * 100) : 0;

        return view('slots.index', compact('zones', 'total', 'occupied', 'available', 'maintenance', 'occupancyPercent'));
    }

    public function show(ParkingSlot $slot)
    {
        $slot->load('zone');

        $activeTicket = Ticket::where('slot_id', $slot->slot_id)
            ->where('status', 'active')
            ->with('vehicle', 'staff', 'feeRate')
            ->first();

        $slot->real_status = $slot->status === 'maintenance' ? 'maintenance' : ($activeTicket ? 'occupied' : 'available');

        $usageHistory = Ticket::where('slot_id', $slot->slot_id)
            ->where('status', '!=', 'active')
            ->with('vehicle', 'payment')
            ->orderBy('entry_time', 'desc')
            ->take(10)
            ->get();

        return view('slots.show', compact('slot', 'activeTicket', 'usageHistory'));
    }

    public function updateStatus(Request $request, ParkingSlot $slot)
    {
        $request->validate(['status' => ['required', 'in:available,maintenance']]);
        $slot->update(['status' => $request->status, 'updated_at' => now()]);
        return redirect()->route('slots.show', $slot->slot_id)->with('success', 'Slot status updated.');
    }
}
