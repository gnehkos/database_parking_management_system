<x-layout title="Log Detail">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('logs.index') }}" class="ios-btn ios-btn-secondary ios-btn-sm"><i class="bi bi-chevron-left"></i></a>
        <div class="page-title" style="font-size:24px">{{ $ticket->ticket_id }}</div>
        <span class="ios-pill" style="background:{{ $ticket->status === 'active' ? 'rgba(52,199,89,0.12)' : 'rgba(0,122,255,0.12)' }};color:{{ $ticket->status === 'active' ? 'var(--ios-green)' : 'var(--ios-blue)' }}">{{ ucfirst($ticket->status) }}</span>
    </div>
    <div style="max-width:600px">
        <div class="ios-card-grouped mb-3">
            <div style="padding:14px 16px 6px"><div class="ios-section-header" style="padding:0;margin:0">Vehicle</div></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Plate Number</span><span style="font-weight:600">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</span></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Type</span><x-type-badge :type="$ticket->vehicle->vehicle_type" /></div>
        </div>
        <div class="ios-card-grouped mb-3">
            <div style="padding:14px 16px 6px"><div class="ios-section-header" style="padding:0;margin:0">Parking Details</div></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Slot</span><span style="font-weight:600">{{ $ticket->slot->slot_number ?? 'N/A' }}</span></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Entry Time</span><span style="font-weight:600">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}</span></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Exit Time</span><span style="font-weight:600">{{ $ticket->exit_time ? \Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A') : '-' }}</span></div>
            <div class="ios-row"><span style="color:var(--ios-gray)">Duration</span><span style="font-weight:600">{{ $duration ?? '-' }}</span></div>
        </div>
        @if ($ticket->payment)
            <div class="ios-card-grouped">
                <div style="padding:14px 16px 6px"><div class="ios-section-header" style="padding:0;margin:0">Payment</div></div>
                <div class="ios-row"><span style="color:var(--ios-gray)">Fee</span><span style="font-weight:700;font-size:18px;color:var(--ios-green)">${{ number_format($ticket->payment->total_fee, 2) }}</span></div>
                <div class="ios-row"><span style="color:var(--ios-gray)">Method</span><span class="ios-pill" style="background:rgba(52,199,89,0.12);color:var(--ios-green)">{{ ucfirst($ticket->payment->payment_method) }}</span></div>
            </div>
        @endif
    </div>
</x-layout>
