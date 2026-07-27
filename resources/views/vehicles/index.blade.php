<x-layout title="Vehicles">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <div class="page-title">Vehicles</div>
            <div class="page-sub">{{ $totalCount }} registered vehicles</div>
        </div>
        <a href="{{ route('vehicles.create') }}" class="ios-btn btn-primary-ios">
            <i class="bi bi-plus me-1"></i> Add Vehicle
        </a>
    </div>

    <div class="card-ios card-ios-p mb-3">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <div style="position:relative;flex:1;min-width:220px">
                <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:15px"></i>
                <input type="text" name="search" class="ios-input" style="padding-left:42px"
                    placeholder="Search plate number..." value="{{ request('search') }}" autocomplete="off">
            </div>
            <button class="ios-btn btn-primary-ios">Search</button>
            @if(request('search'))
                <a href="{{ route('vehicles.index') }}" class="ios-btn btn-ghost">Clear</a>
            @endif
            <div class="filter-pills">
                @foreach (['all','car','motorcycle','bike','tricycle'] as $t)
                    <a href="{{ route('vehicles.index', array_merge(request()->query(), ['type'=>$t])) }}"
                       class="filter-pill {{ request('type','all')===$t?'on':'' }}">{{ ucfirst($t) }}</a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Active ticket found for searched plate --}}
    @if(isset($activeTicket) && $activeTicket)
        @php
            $diff = \Carbon\Carbon::parse($activeTicket->entry_time)->diff(now());
            $dur  = ($diff->days ? $diff->days.'d ' : '') . $diff->h . 'h ' . $diff->i . 'm';
        @endphp
        <div style="border:2px solid var(--red);border-radius:16px;padding:20px;background:rgba(255,59,48,0.03);margin-bottom:16px">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--red);margin-bottom:12px">
                <span class="dot dot-red me-1" style="width:7px;height:7px"></span> Currently Parked — Ticket Found
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div style="font-size:20px;font-weight:700">{{ $activeTicket->vehicle->plate_number ?? 'No plate' }}</div>
                    <div style="font-size:13px;color:var(--gray);margin-top:4px">
                        Slot {{ $activeTicket->slot->slot_number }}
                        &middot; Ticket {{ $activeTicket->ticket_id }}
                        &middot; Since {{ \Carbon\Carbon::parse($activeTicket->entry_time)->format('M d, g:i A') }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-center">
                        <div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px">Duration</div>
                        <div style="font-size:18px;font-weight:700">{{ $dur }}</div>
                    </div>
                    <div class="text-center">
                        <div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.5px">Est. Fee</div>
                        <div style="font-size:18px;font-weight:700;color:var(--red)">${{ number_format($activeTicket->calculated_fee, 2) }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('checkin.ticket', $activeTicket->ticket_id) }}"
                           class="ios-btn btn-ghost btn-sm-ios">
                            <i class="bi bi-ticket-perforated me-1"></i> View Ticket
                        </a>
                        <a href="{{ route('vehicles.edit', $activeTicket->vehicle->vehicle_id) }}"
                           class="ios-btn btn-ghost btn-sm-ios">
                            <i class="bi bi-pencil me-1"></i> Edit Plate
                        </a>
                        <a href="{{ route('checkout.payment', $activeTicket->ticket_id) }}"
                           class="ios-btn" style="background:var(--red);color:#fff">
                            <i class="bi bi-arrow-up-right-circle-fill me-1"></i> Check Out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif(request('search') && request('search') !== '')
        <div class="alert-ios" style="background:rgba(142,142,147,0.1);color:var(--gray);margin-bottom:16px">
            <i class="bi bi-info-circle me-2"></i>
            No active parking session found for "<strong>{{ request('search') }}</strong>"
        </div>
    @endif

    <div class="card-ios card-ios-p">
        <table class="ios-table">
            <thead>
                <tr><th>Plate / ID</th><th>Type</th><th>Registered</th><th style="width:60px"></th></tr>
            </thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td>
                            <a href="{{ route('vehicles.show', $vehicle->vehicle_id) }}"
                               style="color:var(--blue);font-weight:600">
                                {{ $vehicle->plate_number ?? 'No plate' }}
                            </a>
                            @if(str_starts_with($vehicle->plate_number ?? '', 'BIKE-'))
                                <span class="pill pill-orange ms-1" style="font-size:10px">Auto-ID</span>
                            @endif
                        </td>
                        <td><x-type-badge :type="$vehicle->vehicle_type" /></td>
                        <td style="color:var(--gray)">{{ \Carbon\Carbon::parse($vehicle->registered_at)->format('Y-m-d') }}</td>
                        <td>
                            <button class="ios-btn btn-danger-ios btn-sm-ios"
                                data-bs-toggle="modal" data-bs-target="#confirmModal"
                                data-title="Remove Vehicle"
                                data-message="Remove {{ $vehicle->plate_number ?? 'this vehicle' }}? If it has parking history it will be hidden but history is preserved."
                                data-form-id="del-v-{{ $vehicle->vehicle_id }}"
                                data-action="Remove" data-danger="1">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <form id="del-v-{{ $vehicle->vehicle_id }}" method="POST"
                                  action="{{ route('vehicles.destroy', $vehicle->vehicle_id) }}" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-5">
                                <div style="width:52px;height:52px;border-radius:50%;background:var(--gray6);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                                    <i class="bi bi-car-front" style="font-size:22px;color:var(--gray2)"></i>
                                </div>
                                <div style="font-size:15px;font-weight:600;color:var(--label2)">No vehicles found</div>
                                <div style="font-size:13px;color:var(--gray);margin-top:4px">Try a different search or add a vehicle</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $vehicles->links() }}</div>
    </div>
</x-layout>