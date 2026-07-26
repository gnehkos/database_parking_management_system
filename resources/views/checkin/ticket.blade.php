<x-layout title="Check-In Successful">
    <div class="text-center py-4" style="max-width:420px;margin:0 auto">
        <div style="width:64px;height:64px;border-radius:50%;background:rgba(52,199,89,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-check-lg" style="font-size:30px;color:var(--green)"></i>
        </div>
        <div class="page-title" style="font-size:22px">Check-In Successful</div>
        <div style="color:var(--gray);font-size:14px;margin-bottom:24px">Vehicle has been parked and ticket issued.</div>

        <div class="card-ios overflow-hidden">
            <div style="background:linear-gradient(135deg,#007aff,#5856d6);padding:20px;color:#fff;text-align:left">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;opacity:0.8">Parking Ticket</div>
                <div style="font-size:22px;font-weight:800;margin:6px 0">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</div>
                <x-type-badge :type="$ticket->vehicle->vehicle_type" />
            </div>
            <div class="card-ios-p">
                <div class="grouped" style="border:none;margin:0">
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-hash me-1"></i>Ticket ID</span><span class="row-val">{{ $ticket->ticket_id }}</span></div>
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-geo-alt-fill me-1"></i>Slot</span><span class="row-val">{{ $ticket->slot->slot_number }}</span></div>
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-clock-fill me-1"></i>Entry Time</span><span class="row-val">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}</span></div>
                    @if (auth()->user()->isAdmin())
                        <div class="grouped-row"><span class="row-label"><i class="bi bi-person-fill me-1"></i>Processed by</span><span class="row-val">{{ $ticket->staff->full_name ?? 'N/A' }}</span></div>
                    @endif
                </div>
                <div class="text-center mt-3 py-2" style="background:var(--gray6);border-radius:10px;font-family:monospace;font-weight:700;font-size:13px;letter-spacing:2px">{{ $ticket->barcode }}</div>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('dashboard') }}" class="ios-btn btn-primary-ios flex-fill"><i class="bi bi-house-fill me-1"></i> Dashboard</a>
        </div>
        <a href="{{ route('checkin.index') }}" style="color:var(--blue);font-size:14px;font-weight:600;display:inline-block;margin-top:16px">Check in another vehicle</a>
    </div>
</x-layout>
