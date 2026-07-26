<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SearchVehicleController extends Controller
{
    public function index(Request $request)
    {
        $results = collect();

        if ($request->filled('q')) {
            $q = $request->q;
            $results = Vehicle::active()
                ->where(function ($query) use ($q) {
                    $query->where('plate_number', 'like', '%' . $q . '%');
                })
                ->limit(20)
                ->get();

            if ($results->isEmpty()) {
                $ticketResult = Ticket::where('ticket_id', $q)->with('vehicle')->first();
                if ($ticketResult && $ticketResult->vehicle) {
                    $results = collect([$ticketResult->vehicle]);
                }
            }
        }

        return view('search.index', compact('results'));
    }
}
