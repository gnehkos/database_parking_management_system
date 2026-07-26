<x-layout title="{{ $vehicle->plate_number ?? 'Vehicle' }}">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('vehicles.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
            <div class="page-title" style="font-size:22px">{{ $vehicle->plate_number ?? 'No plate' }}</div>
            <x-type-badge :type="$vehicle->vehicle_type" />
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('vehicles.edit', $vehicle->vehicle_id) }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-pencil me-1"></i> Edit</a>
            <button class="ios-btn btn-danger-ios btn-sm-ios" data-bs-toggle="modal" data-bs-target="#confirmModal"
                data-title="Delete Vehicle"
                data-message="Permanently delete {{ $vehicle->plate_number ?? 'this vehicle' }}? This cannot be undone."
                data-form-id="del-vehicle-{{ $vehicle->vehicle_id }}"
                data-action="Delete" data-danger="1">
                <i class="bi bi-trash-fill me-1"></i> Delete
            </button>
            <form id="del-vehicle-{{ $vehicle->vehicle_id }}" method="POST" action="{{ route('vehicles.destroy', $vehicle->vehicle_id) }}" style="display:none">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    @if ($activeTicket)
        <div class="alert-ios alert-success-ios mb-4 d-flex justify-content-between align-items-center">
            <span><span class="dot dot-green me-2"></span> Currently parked at Slot {{ $activeTicket->slot->slot_number }} since {{ \Carbon\Carbon::parse($activeTicket->entry_time)->format('M d, g:i A') }}</span>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="grouped">
                <div style="padding:14px 18px 4px"><div class="section-hdr" style="margin:0">Vehicle Info</div></div>
                <div class="grouped-row"><span class="row-label">Plate Number</span><span class="row-val">{{ $vehicle->plate_number ?? 'No plate' }}</span></div>
                <div class="grouped-row"><span class="row-label">Type</span><x-type-badge :type="$vehicle->vehicle_type" /></div>
                <div class="grouped-row"><span class="row-label">Registered</span><span class="row-val">{{ \Carbon\Carbon::parse($vehicle->registered_at)->format('Y-m-d') }}</span></div>
                <div class="grouped-row"><span class="row-label">Vehicle ID</span><span class="pill pill-gray">V{{ str_pad($vehicle->vehicle_id,3,'0',STR_PAD_LEFT) }}</span></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-ios card-ios-p text-center">
                <div class="section-hdr">Parking Stats</div>
                <div class="row mt-3">
                    <div class="col-6"><div style="font-size:32px;font-weight:800">{{ $totalVisits }}</div><div style="font-size:13px;color:var(--gray)">Total Visits</div></div>
                    <div class="col-6"><div style="font-size:32px;font-weight:800">${{ number_format($totalPaid,2) }}</div><div style="font-size:13px;color:var(--gray)">Total Paid</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-ios card-ios-p">
        <div class="section-hdr">Parking History</div>
        @forelse ($tickets as $ticket)
            <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:var(--gray6);display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-clock-fill" style="color:var(--gray);font-size:15px"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600">{{ $ticket->ticket_id }}
                            <span class="pill {{ $ticket->status==='active'?'pill-green':($ticket->status==='completed'?'pill-blue':'pill-gray') }} ms-1">{{ ucfirst($ticket->status) }}</span>
                        </div>
                        <div style="font-size:12px;color:var(--gray)">
                            {{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}
                            @if($ticket->exit_time) &rarr; {{ \Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A') }} @endif
                            · Slot {{ $ticket->slot->slot_number ?? 'N/A' }}
                        </div>
                        @if(auth()->user()->isAdmin() && $ticket->staff)
                            <div style="font-size:11px;color:var(--gray2)"><i class="bi bi-person-fill me-1"></i>{{ $ticket->staff->full_name }}</div>
                        @endif
                    </div>
                </div>
                @if ($ticket->payment)
                    <span style="font-weight:700;font-size:15px">${{ number_format($ticket->payment->total_fee,2) }}</span>
                @endif
            </div>
        @empty
            <div class="text-center py-4" style="color:var(--gray)">No parking history.</div>
        @endforelse
    </div>
</x-layout>
