<?php

namespace App\Http\Controllers;

use App\Models\FeeRate;
use Illuminate\Http\Request;

class FeeRateController extends Controller
{
    public function index()
    {
        $rates = FeeRate::all()->keyBy('vehicle_type');
        return view('fees.index', compact('rates'));
    }

    public function update(Request $request, FeeRate $rate)
    {
        $request->validate([
            'short_stay_fee' => ['required', 'numeric', 'min:0'],
            'long_stay_fee' => ['required', 'numeric', 'min:0'],
        ]);

        $rate->update([
            'short_stay_fee' => $request->short_stay_fee,
            'long_stay_fee' => $request->long_stay_fee,
            'updated_at' => now(),
        ]);

        return redirect()->route('fees.index')->with('success', 'Rate updated.');
    }
}
