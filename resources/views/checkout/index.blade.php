<x-layout title="Check-Out">
    <div class="page-header"><div class="page-title">Vehicle Check-Out</div><div class="page-sub">Search by plate number or ticket ID</div></div>

    <div class="card-ios card-ios-p mb-4">
        <div class="section-hdr">Ticket / Plate Lookup</div>
        <form method="GET" class="d-flex gap-2 mt-2">
            <input type="text" name="ticket_search" class="ios-input" placeholder="Plate number or Ticket ID (e.g. 2AB-1234 or T001)" value="{{ request('ticket_search') }}">
            <button class="ios-btn btn-primary-ios">Search</button>
        </form>
        @if(isset($searchError))
            <div class="alert-ios alert-danger-ios mt-3"><i class="bi bi-exclamation-circle me-1"></i> {{ $searchError }}</div>
        @endif
        @if(isset($searchResult))
            <div class="mt-3 p-3" style="background:rgba(52,199,89,0.08);border-radius:12px;display:flex;justify-content:space-between;align-items:center">
                <div>
                    <div style="font-weight:700;font-size:16px">{{ $searchResult->vehicle->plate_number ?? 'No plate' }}</div>
                    <div style="font-size:13px;color:var(--gray)">Slot {{ $searchResult->slot->slot_number }} · Since {{ \Carbon\Carbon::parse($searchResult->entry_time)->format('M d, g:i A') }}</div>
                </div>
                <div class="text-end">
                    <div style="font-size:20px;font-weight:800;color:var(--red)">${{ number_format($searchResult->calculated_fee,2) }}</div>
                    <a href="{{ route('checkout.payment', $searchResult->ticket_id) }}" class="ios-btn btn-primary-ios btn-sm-ios mt-1">Proceed to Payment</a>
                </div>
            </div>
        @endif
    </div>

    <div class="section-hdr">Currently Parked ({{ $parkedVehicles->count() }})</div>
    <div class="row g-3">
        @foreach ($parkedVehicles as $ticket)
            <div class="col-md-4">
                <a href="{{ route('checkout.payment', $ticket->ticket_id) }}" class="card-ios card-ios-p d-block" style="transition:all 0.15s">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div style="font-size:16px;font-weight:700;color:var(--label)">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</div>
                            <x-type-badge :type="$ticket->vehicle->vehicle_type" />
                            <div style="font-size:12px;color:var(--gray);margin-top:6px"><i class="bi bi-geo-alt-fill me-1"></i>{{ $ticket->slot->slot_number }} · {{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}</div>
                            @if(auth()->user()->isAdmin() && $ticket->staff)
                                <div style="font-size:11px;color:var(--gray2);margin-top:2px"><i class="bi bi-person-fill me-1"></i>{{ $ticket->staff->full_name }}</div>
                            @endif
                        </div>
                        <div style="font-size:20px;font-weight:800;color:var(--blue)">${{ number_format($ticket->calculated_fee,2) }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</x-layout>
