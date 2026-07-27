<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::where('status', 'active');
        $totalCount = $query->count();

        if ($request->filled('search')) {
            $query->where('plate_number', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('vehicle_type', $request->type);
        }

        $vehicles = $query->orderBy('registered_at', 'desc')->paginate(20)->withQueryString();

        // If searching by plate, also check if that plate is currently parked
        $activeTicket = null;
        if ($request->filled('search')) {
            $activeTicket = Ticket::where('status', 'active')
                ->whereHas('vehicle', fn($q) =>
                    $q->where('plate_number', 'like', '%' . $request->search . '%')
                )
                ->with('vehicle', 'slot', 'feeRate')
                ->first();

            if ($activeTicket) {
                $hours = \Carbon\Carbon::parse($activeTicket->entry_time)->diffInMinutes(now()) / 60;
                $activeTicket->calculated_fee = $activeTicket->feeRate->calculateFee($hours);
            }
        }

        return view('vehicles.index', compact('vehicles', 'totalCount', 'activeTicket'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            'plate_type'   => ['required', 'in:structured,custom'],
        ]);

        Vehicle::create([
            'plate_number' => $request->plate_number ?: null,
            'vehicle_type' => $request->vehicle_type,
            'plate_type'   => $request->plate_type,
            'status'       => 'active',
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle registered.');
    }

    public function show(Vehicle $vehicle)
    {
        $activeTicket = Ticket::where('vehicle_id', $vehicle->vehicle_id)
            ->where('status', 'active')
            ->with('slot', 'feeRate')
            ->first();

        if ($activeTicket) {
            $hours = \Carbon\Carbon::parse($activeTicket->entry_time)->diffInMinutes(now()) / 60;
            $activeTicket->calculated_fee = $activeTicket->feeRate->calculateFee($hours);
        }

        $tickets = Ticket::where('vehicle_id', $vehicle->vehicle_id)
            ->with('slot', 'payment', 'staff')
            ->orderBy('entry_time', 'desc')
            ->get();

        $totalVisits = $tickets->count();
        $totalPaid   = $tickets->sum(fn($t) => $t->payment?->total_fee ?? 0);

        return view('vehicles.show', compact('vehicle', 'activeTicket', 'tickets', 'totalVisits', 'totalPaid'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function destroy(Vehicle $vehicle)
    {
        $hasTickets = Ticket::where('vehicle_id', $vehicle->vehicle_id)->exists();

        if ($hasTickets) {
            $vehicle->update(['status' => 'deleted']);
            return redirect()->route('vehicles.index')->with('success', 'Vehicle removed. History preserved.');
        }

        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle permanently deleted.');
    }

        public function update(Request $request, Vehicle $vehicle)
        {
        if ($vehicle->vehicle_type !== 'bike' && $vehicle->plate_type === 'structured') {
            $prefix = in_array($vehicle->vehicle_type, ['motorcycle', 'tricycle']) ? '1' : '2';
            $request->validate([
                'plate_number' => [
                    'nullable',
                    'string',
                    'max:20',
                    function ($attribute, $value, $fail) use ($prefix) {
                        if (!$value) return;
                        $plate = strtoupper(trim($value));
                        if (!preg_match('/^[12][A-Z]{2}-\d{4}$/', $plate)) {
                            $fail('Plate must be in format like ' . $prefix . 'AB-1234.');
                        }
                        if ($plate[0] !== $prefix) {
                            $fail('Plate for this vehicle type must start with ' . $prefix . '.');
                        }
                    },
                ],
                'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            ]);
        } else {
            $request->validate([
                'plate_number' => ['nullable', 'string', 'max:20'],
                'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            ]);
        }

        $vehicle->update([
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('vehicles.show', $vehicle->vehicle_id)
            ->with('success', 'Vehicle updated.');
    }
}