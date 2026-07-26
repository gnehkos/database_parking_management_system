<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function edit($ticketId)
    {
        $ticket   = Ticket::with('vehicle', 'slot', 'feeRate')->findOrFail($ticketId);
        $vehicles = Vehicle::where('status', 'active')->orderBy('plate_number')->get();
        $slots    = ParkingSlot::with('zone')->get();

        return view('tickets.edit', compact('ticket', 'vehicles', 'slots'));
    }

    public function update(Request $request, $ticketId)
    {
        $request->validate([
            'entry_time'  => ['required', 'date'],
            'vehicle_id'  => ['required', 'exists:vehicles,vehicle_id'],
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update([
            'entry_time' => $request->entry_time,
            'vehicle_id' => $request->vehicle_id,
        ]);

        return redirect()->route('history.index')->with('success', 'Ticket ' . $ticketId . ' updated successfully.');
    }

    public function destroy($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        DB::transaction(function () use ($ticket) {
            if ($ticket->status === 'active') {
                ParkingSlot::where('slot_id', $ticket->slot_id)
                    ->update(['status' => 'available', 'updated_at' => now()]);
            }
            $ticket->payment()->delete();
            $ticket->delete();
        });

        return redirect()->route('history.index')->with('success', 'Ticket deleted.');
    }
}
