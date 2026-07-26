<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::active()->orderBy('registered_at', 'desc');

        if ($request->filled('search')) {
            $query->where('plate_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('vehicle_type', $request->type);
        }

        $vehicles = $query->paginate(10)->withQueryString();
        $totalCount = Vehicle::active()->count();

        return view('vehicles.index', compact('vehicles', 'totalCount'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
            'plate_type' => ['required', 'in:structured,custom'],
        ]);

        if ($request->plate_type === 'structured') {
            $request->validate([
                'plate_prefix' => ['required', 'size:1'],
                'plate_letters' => ['required', 'size:2', 'alpha'],
                'plate_digits' => ['required', 'size:4', 'numeric'],
            ]);
            $plate = $request->plate_prefix . $request->plate_letters . '-' . $request->plate_digits;
        } else {
            $plate = $request->plate_number;
        }

        Vehicle::create([
            'plate_number' => $plate ?: null,
            'vehicle_type' => $request->vehicle_type,
            'plate_type' => $request->plate_type,
            'status' => 'active',
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle registered.');
    }

    public function show(Vehicle $vehicle)
    {
        $tickets = Ticket::where('vehicle_id', $vehicle->vehicle_id)
            ->with('slot', 'payment')
            ->orderBy('entry_time', 'desc')
            ->get();

        $totalVisits = $tickets->count();
        $totalPaid = $tickets->sum(fn ($t) => $t->payment?->total_fee ?? 0);

        $activeTicket = $tickets->firstWhere('status', 'active');

        return view('vehicles.show', compact('vehicle', 'tickets', 'totalVisits', 'totalPaid', 'activeTicket'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => ['nullable', 'string', 'max:20'],
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
        ]);

        $vehicle->update([
            'plate_number' => $request->plate_number ?: null,
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('vehicles.show', $vehicle)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->update(['status' => 'deleted']);
        return redirect()->route('vehicles.index')->with('success', 'Vehicle ' . ($vehicle->plate_number ?? 'No plate') . ' removed.');
    }
}
