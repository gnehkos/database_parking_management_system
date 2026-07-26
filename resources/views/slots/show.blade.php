<x-layout title="Slot {{ $slot->slot_number }}">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('slots.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div class="page-title" style="font-size:22px">Slot {{ $slot->slot_number }}</div>
    </div>

    @php $clr = match($slot->status) { 'occupied'=>'var(--red)', 'maintenance'=>'var(--orange)', default=>'var(--green)' }; @endphp
    <div class="text-center mb-4">
        <div style="font-size:52px;font-weight:900;color:{{ $clr }};letter-spacing:-1px">{{ $slot->slot_number }}</div>
        <div style="font-size:15px;font-weight:600;color:{{ $clr }}">{{ ucfirst($slot->status) }}</div>
        <div class="mt-1"><span class="pill pill-gray">{{ ucfirst($slot->zone->vehicle_type) }}</span></div>
    </div>

    @if ($currentTicket)
        <div class="card-ios card-ios-p mb-4">
            <div class="section-hdr">Current Vehicle</div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    <div style="font-size:17px;font-weight:700">{{ $currentTicket->vehicle->plate_number ?? 'No plate' }}</div>
                    <div style="font-size:13px;color:var(--gray)">Since {{ \Carbon\Carbon::parse($currentTicket->entry_time)->format('M d, g:i A') }} · {{ $currentTicket->ticket_id }}</div>
                    @if(auth()->user()->isAdmin() && $currentTicket->staff)
                        <div style="font-size:12px;color:var(--gray2)"><i class="bi bi-person-fill me-1"></i>{{ $currentTicket->staff->full_name }}</div>
                    @endif
                </div>
                <a href="{{ route('checkout.payment', $currentTicket->ticket_id) }}" class="ios-btn btn-sm-ios" style="background:var(--green);color:#fff">Check Out</a>
            </div>
        </div>
    @endif

    @if ($slot->status !== 'occupied')
        <div class="card-ios card-ios-p mb-4">
            <div class="section-hdr">Update Status</div>
            <form method="POST" action="{{ route('slots.updateStatus', $slot->slot_id) }}" class="d-flex gap-2 mt-2">
                @csrf @method('PATCH')
                <select name="status" class="ios-input" style="max-width:220px">
                    <option value="available" {{ $slot->status==='available'?'selected':'' }}>Available</option>
                    <option value="maintenance" {{ $slot->status==='maintenance'?'selected':'' }}>Under Maintenance</option>
                </select>
                <button class="ios-btn btn-primary-ios btn-sm-ios">Update</button>
            </form>
        </div>
    @endif

    <div class="card-ios card-ios-p">
        <div class="section-hdr">Usage History</div>
        @forelse ($usageHistory as $ticket)
            <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                <div>
                    <span style="font-weight:600">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</span>
                    <div style="font-size:12px;color:var(--gray)">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}
                        @if($ticket->exit_time) &rarr; {{ \Carbon\Carbon::parse($ticket->exit_time)->format('g:i A') }} @endif
                    </div>
                </div>
                <span class="pill {{ $ticket->status==='active'?'pill-green':'pill-blue' }}">{{ ucfirst($ticket->status) }}</span>
            </div>
        @empty
            <div class="text-center py-4" style="color:var(--gray)">No usage history.</div>
        @endforelse
    </div>
</x-layout>
