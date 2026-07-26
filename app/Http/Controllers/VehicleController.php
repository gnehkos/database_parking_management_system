<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'plate_number' => ['nullable', 'string', 'max:20'],
            'vehicle_type' => ['required', 'in:car,motorcycle,bike,tricycle'],
        ]);

        $vehicle->update([
            'plate_number' => $request->plate_number,
            'vehicle_type' => $request->vehicle_type,
        ]);

        return redirect()->route('vehicles.show', $vehicle->vehicle_id)->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $hasTickets = Ticket::where('vehicle_id', $vehicle->vehicle_id)->exists();

        if ($hasTickets) {
            $vehicle->update(['status' => 'deleted']);
            return redirect()->route('vehicles.index')->with('success', 'Vehicle removed from active list. History preserved.');
        }

        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle permanently deleted.');
    }

    public function hardDelete(Vehicle $vehicle)
    {
        try {
            DB::transaction(function () use ($vehicle) {
                Ticket::where('vehicle_id', $vehicle->vehicle_id)->each(function ($ticket) {
                    $ticket->payment()->delete();
                    $ticket->delete();
                });
                $vehicle->delete();
            });
            return redirect()->route('vehicles.index')->with('success', 'Vehicle and all history permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Could not delete vehicle: ' . $e->getMessage());
        }
    }
}
