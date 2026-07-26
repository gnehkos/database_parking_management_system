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
            'plate_type'   => ['required', 'in:structured,custom'],
        ]);

        $vehicleType = $request->vehicle_type;
        $plateNumber = $vehicleType === 'bike' ? null : ($request->plate_number ?: null);
        $plateType   = $request->plate_type;

        if ($plateNumber) {
            $existing = Ticket::where('status', 'active')
                ->whereHas('vehicle', fn($q) => $q->where('plate_number', $plateNumber))
                ->with('slot')
                ->first();

            if ($existing) {
                return back()->withErrors(['plate' => $plateNumber . ' is already checked in at Slot ' . ($existing->slot->slot_number ?? 'N/A') . '.']);
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
            'plate_type'   => ['required', 'in:structured,custom'],
            'slot_id'      => ['required', 'exists:parking_slots,slot_id'],
        ]);

        return DB::transaction(function () use ($request) {
            $vehicleType = $request->vehicle_type;
            $plateNumber = $vehicleType === 'bike' ? null : ($request->plate_number ?: null);

            if ($vehicleType === 'bike') {
                $last = Vehicle::where('vehicle_type', 'bike')
                    ->where('plate_number', 'like', 'BIKE-%')
                    ->orderByDesc('vehicle_id')
                    ->first();
                $num = $last ? (intval(substr($last->plate_number, 5)) + 1) : 1;
                $plateNumber = 'BIKE-' . str_pad($num, 3, '0', STR_PAD_LEFT);
            }

            if ($plateNumber && $vehicleType !== 'bike') {
                $existing = Ticket::where('status', 'active')
                    ->whereHas('vehicle', fn($q) => $q->where('plate_number', $plateNumber))
                    ->first();
                if ($existing) {
                    return redirect()->route('checkin.index')
                        ->withErrors(['plate' => $plateNumber . ' is already checked in.']);
                }
            }

            $vehicle = $vehicleType !== 'bike'
                ? Vehicle::where('vehicle_type', $vehicleType)
                    ->where('plate_number', $plateNumber)
                    ->where('status', 'active')
                    ->first()
                : null;

            if (!$vehicle) {
                $vehicle = Vehicle::create([
                    'plate_number' => $plateNumber,
                    'vehicle_type' => $vehicleType,
                    'plate_type'   => $request->plate_type,
                    'status'       => 'active',
                ]);
            }

            $slot = ParkingSlot::findOrFail($request->slot_id);
            $slot->update(['status' => 'occupied', 'updated_at' => now()]);

            $feeRate = FeeRate::where('vehicle_type', $vehicleType)->first();
            $ticketId = 'T' . strtoupper(substr(uniqid(), -6));

            $ticket = Ticket::create([
                'ticket_id'  => $ticketId,
                'vehicle_id' => $vehicle->vehicle_id,
                'slot_id'    => $slot->slot_id,
                'staff_id'   => auth()->id(),
                'rate_id'    => $feeRate->rate_id,
                'entry_time' => now(),
                'barcode'    => $ticketId,
                'status'     => 'active',
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

    public function printTicket($ticketId)
    {
        $ticket = Ticket::with('vehicle', 'slot', 'staff', 'feeRate')->findOrFail($ticketId);
        return view('checkin.print-ticket', compact('ticket'));
    }
}
