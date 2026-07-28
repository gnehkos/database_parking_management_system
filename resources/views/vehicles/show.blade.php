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
                data-title="Remove Vehicle"
                data-message="Remove {{ $vehicle->plate_number ?? 'this vehicle' }}?"
                data-form-id="soft-del-{{ $vehicle->vehicle_id }}"
                data-action="Remove">
                <i class="bi bi-eye-slash-fill me-1"></i> Remove
            </button>
            <form id="soft-del-{{ $vehicle->vehicle_id }}" method="POST" action="{{ route('vehicles.destroy', $vehicle->vehicle_id) }}" style="display:none">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    @if ($activeTicket)
        <div style="border:2px solid var(--red);border-radius:16px;padding:20px;background:rgba(255,59,48,0.03);margin-bottom:20px">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--red);margin-bottom:6px">
                        <span class="dot dot-red me-1" style="width:7px;height:7px"></span> Currently Parked
                    </div>
                    <div style="font-size:20px;font-weight:700">Slot {{ $activeTicket->slot->slot_number }}</div>
                    <div style="font-size:13px;color:var(--gray);margin-top:4px">
                        Since {{ \Carbon\Carbon::parse($activeTicket->entry_time)->format('M d, g:i A') }}
                        &middot; Ticket {{ $activeTicket->ticket_id }}
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php
                        $h = \Carbon\Carbon::parse($activeTicket->entry_time)->diffInMinutes(now()) / 60;
                        $estFee = $activeTicket->feeRate->calculateFee($h);
                        $diff = \Carbon\Carbon::parse($activeTicket->entry_time)->diff(now());
                        $dur = ($diff->days ? $diff->days.'d ' : '').$diff->h.'h '.$diff->i.'m';
                    @endphp
                    <div class="text-center"><div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px">Duration</div><div style="font-size:18px;font-weight:700">{{ $dur }}</div></div>
                    <div class="text-center mx-3"><div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px">Est. Fee</div><div style="font-size:18px;font-weight:700;color:var(--red)">${{ number_format($estFee,2) }}</div></div>
                    <a href="{{ route('checkout.payment', $activeTicket->ticket_id) }}" class="ios-btn" style="background:var(--red);color:#fff">
                        <i class="bi bi-arrow-up-right-circle-fill me-1"></i> Check Out
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="grouped">
                <div style="padding:14px 18px 4px"><div class="section-hdr" style="margin:0">Vehicle Info</div></div>
                <div class="grouped-row"><span class="row-label">Plate Number</span><span class="row-val">{{ $vehicle->plate_number ?? 'No plate' }}</span></div>
                <div class="grouped-row"><span class="row-label">Type</span><x-type-badge :type="$vehicle->vehicle_type" /></div>
                <div class="grouped-row"><span class="row-label">Plate Type</span><span class="row-val">{{ ucfirst($vehicle->plate_type) }}</span></div>
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
                        <i class="bi bi-clock-fill" style="color:var(--gray);font-size:14px"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600">{{ $ticket->ticket_id }}
                            <span class="pill {{ $ticket->status==='active'?'pill-green':($ticket->status==='completed'?'pill-blue':($ticket->status==='cancelled'?'pill-red':'pill-gray')) }} ms-1">{{ ucfirst($ticket->status) }}</span>
                        </div>
                        <div style="font-size:12px;color:var(--gray)">
                            {{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}
                            @if($ticket->exit_time) &rarr; {{ \Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A') }} @endif
                            &middot; Slot {{ $ticket->slot->slot_number ?? 'N/A' }}
                        </div>
                        @if(auth()->user()->isAdmin() && $ticket->staff)
                            <div style="font-size:11px;color:var(--gray2)"><i class="bi bi-person-fill me-1"></i>{{ $ticket->staff->full_name }}</div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($ticket->payment)
                        <span style="font-weight:700">${{ number_format($ticket->payment->total_fee,2) }}</span>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('tickets.edit', $ticket->ticket_id) }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-pencil-fill"></i></a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div style="width:52px;height:52px;border-radius:50%;background:var(--gray6);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <i class="bi bi-clock-history" style="font-size:22px;color:var(--gray2)"></i>
                </div>
                <div style="font-size:15px;font-weight:600;color:var(--label2)">No parking history</div>
            </div>
        @endforelse
    </div>
</x-layout>
