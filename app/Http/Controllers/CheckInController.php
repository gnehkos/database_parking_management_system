<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\ParkingZone;
use App\Models\ParkingSlot;
use App\Models\FeeRate;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckInController extends Controller
{
    public function index()
    {
        $zones = ParkingZone::with('slots')->get();
        $freeByType = [];
        foreach ($zones as $zone) {
            $type = $zone->vehicle_type;
            $freeByType[$type] = ($freeByType[$type] ?? 0) + $zone->slots->where('status', 'available')->count();
        }
        return view('checkin.index', compact('freeByType'));
    }

    public function slotSelection(Request $request)
    {
        $request->validate([
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            'plate_type' => ['required', 'in:structured,custom'],
        ]);

        $vehicleType = $request->vehicle_type;
        $plateNumber = $request->plate_number ?: null;
        $plateType = $request->plate_type;

        if ($vehicleType === 'bike') {
            $plateNumber = null;
        }

        if ($plateNumber) {
            $existing = Ticket::where('status', 'active')
                ->whereHas('vehicle', fn($q) => $q->where('plate_number', $plateNumber))
                ->first();

            if ($existing) {
                return back()->withErrors(['plate' => 'This vehicle is already checked in at Slot ' . $existing->slot->slot_number . '.']);
            }
        }

        $zones = ParkingZone::with('slots')->get();
        $targetZone = $zones->firstWhere('vehicle_type', $vehicleType);
        $availableCount = $targetZone ? $targetZone->slots->where('status', 'available')->count() : 0;

        return view('checkin.slots', compact('zones', 'vehicleType', 'plateNumber', 'plateType', 'availableCount'));
    }

    public function assignSlot(Request $request)
    {
        $request->validate([
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            'plate_type' => ['required', 'in:structured,custom'],
            'slot_id' => ['required', 'exists:parking_slots,slot_id'],
        ]);

        return DB::transaction(function () use ($request) {
            $plateNumber = $request->plate_number ?: null;
            if ($request->vehicle_type === 'bike') {
                $plateNumber = null;
            }

            $vehicle = Vehicle::where('vehicle_type', $request->vehicle_type)
                ->where(function ($q) use ($plateNumber) {
                    if ($plateNumber) {
                        $q->where('plate_number', $plateNumber);
                    } else {
                        $q->whereNull('plate_number');
                    }
                })
                ->where('status', 'active')
                ->first();

            if (!$vehicle) {
                $vehicle = Vehicle::create([
                    'plate_number' => $plateNumber,
                    'vehicle_type' => $request->vehicle_type,
                    'plate_type' => $request->plate_type,
                    'status' => 'active',
                ]);
            }

            $slot = ParkingSlot::findOrFail($request->slot_id);
            $slot->update(['status' => 'occupied', 'updated_at' => now()]);

            $feeRate = FeeRate::where('vehicle_type', $request->vehicle_type)->first();

            $ticketId = 'T' . strtoupper(substr(uniqid(), -6));

            $ticket = Ticket::create([
                'ticket_id' => $ticketId,
                'vehicle_id' => $vehicle->vehicle_id,
                'slot_id' => $slot->slot_id,
                'staff_id' => auth()->id(),
                'rate_id' => $feeRate->rate_id,
                'entry_time' => now(),
                'barcode' => $ticketId,
                'status' => 'active',
                'created_at' => now(),
            ]);

            return redirect()->route('checkin.ticket', $ticket->ticket_id);
        });
    }

    public function ticket($ticketId)
    {
        $ticket = Ticket::with('vehicle', 'slot', 'staff')->findOrFail($ticketId);
        return view('checkin.ticket', compact('ticket'));
    }
}
