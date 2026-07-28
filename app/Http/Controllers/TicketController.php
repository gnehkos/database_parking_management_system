<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function correctVehicle(Request $request, $ticketId)
    {
        $ticket      = Ticket::where('status', 'active')->findOrFail($ticketId);
        $vehicleType = $ticket->vehicle->vehicle_type;
        $plateType   = $ticket->vehicle->plate_type;

        if ($vehicleType !== 'bike' && $plateType === 'structured') {
            $prefix = in_array($vehicleType, ['motorcycle', 'tricycle']) ? '1' : '2';
            $request->validate([
                'plate_number' => [
                    'required', 'string', 'max:20',
                    function ($attribute, $value, $fail) use ($prefix) {
                        $plate = strtoupper(trim($value));
                        if (!preg_match('/^[12][A-Z]{2}-\d{4}$/', $plate)) {
                            $fail('Plate must be in format like ' . $prefix . 'AB-1234.');
                        }
                        if ($plate[0] !== $prefix) {
                            $fail('Plate for this vehicle type must start with ' . $prefix . '.');
                        }
                    },
                ],
            ]);
        } else {
            $request->validate([
                'plate_number' => ['required', 'string', 'max:20'],
            ]);
        }

        $newPlate       = strtoupper(trim($request->plate_number));
        $currentVehicle = $ticket->vehicle;

        $existingVehicle = Vehicle::where('plate_number', $newPlate)
            ->where('vehicle_id', '!=', $currentVehicle->vehicle_id)
            ->first();

        if ($existingVehicle) {
            $ticket->update(['vehicle_id' => $existingVehicle->vehicle_id]);
            $hasOtherTickets = Ticket::where('vehicle_id', $currentVehicle->vehicle_id)
                ->where('ticket_id', '!=', $ticketId)
                ->exists();
            if (!$hasOtherTickets) {
                $currentVehicle->delete();
            }
        } else {
            $currentVehicle->update(['plate_number' => $newPlate]);
        }

        return redirect()->route('checkin.ticket', $ticketId)
            ->with('success', 'Plate corrected to ' . $newPlate . '.');
    }
}