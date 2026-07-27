@extends('components.layout')

@section('title', 'Slot ' . $slot->slot_number)

@section('content')

{{-- Back --}}
<div style="margin-bottom:1.5rem">
    <a href="{{ route('slots.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:500;color:var(--blue)">
        <i class="bi bi-arrow-left"></i> Slot Map
    </a>
</div>

@if (session('success'))
    <div class="alert-ios alert-success-ios" style="margin-bottom:16px;display:flex;align-items:center;gap:10px">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-ios alert-danger-ios" style="margin-bottom:16px;display:flex;align-items:center;gap:10px">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">

    {{-- Left: Slot identity --}}
    <div class="card-ios card-ios-p">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px">
            <div>
                <div style="font-size:36px;font-weight:800;color:var(--label);line-height:1;letter-spacing:-0.5px">
                    {{ $slot->slot_number }}
                </div>
                <div style="font-size:13px;color:var(--gray);margin-top:6px;display:flex;align-items:center;gap:6px">
                    <i class="bi bi-geo-alt" style="font-size:12px"></i>
                    {{ $slot->zone->zone_name ?? '—' }}
                </div>
            </div>
            @if ($realStatus === 'available')
                <span class="pill pill-green" style="padding:6px 14px;font-size:13px;flex-shrink:0">
                    <i class="bi bi-circle-fill" style="font-size:8px;margin-right:5px"></i>Available
                </span>
            @elseif ($realStatus === 'occupied')
                <span class="pill pill-red" style="padding:6px 14px;font-size:13px;flex-shrink:0">
                    <i class="bi bi-circle-fill" style="font-size:8px;margin-right:5px"></i>Occupied
                </span>
            @else
                <span class="pill pill-orange" style="padding:6px 14px;font-size:13px;flex-shrink:0">
                    <i class="bi bi-tools" style="font-size:11px;margin-right:5px"></i>Maintenance
                </span>
            @endif
        </div>

        <div style="height:0.5px;background:var(--gray5);margin-bottom:16px"></div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
            <div>
                <div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Zone</div>
                <div style="font-size:22px;font-weight:700;color:var(--label)">{{ $slot->zone->zone_code ?? '—' }}</div>
            </div>
            <div>
                <div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Type</div>
                <div style="margin-top:4px">
                    <span class="type-badge type-badge-{{ $slot->zone->vehicle_type ?? 'car' }}">
                        {{ ucfirst($slot->zone->vehicle_type ?? '—') }}
                    </span>
                </div>
            </div>
            <div>
                <div style="font-size:11px;color:var(--gray);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">Total Uses</div>
                <div style="font-size:22px;font-weight:700;color:var(--label)">{{ $totalUses }}</div>
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="card-ios card-ios-p" style="display:flex;flex-direction:column;gap:12px">
        <div style="font-size:12px;font-weight:600;color:var(--gray);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px">
            Actions
        </div>

        {{-- Maintenance toggle --}}
        @if ($realStatus !== 'occupied')
            <form method="POST" action="{{ route('slots.updateStatus', $slot->slot_id) }}" id="statusForm">
                @csrf
                @method('PATCH')
            </form>
            @if ($realStatus === 'maintenance')
                <button type="button"
                    class="ios-btn btn-primary-ios"
                    style="width:100%;justify-content:center;gap:8px"
                    data-bs-toggle="modal" data-bs-target="#confirmModal"
                    data-title="Mark as Available"
                    data-message="Mark {{ $slot->slot_number }} as available? Staff will be able to check vehicles into this slot."
                    data-form-id="statusForm"
                    data-action="Mark Available">
                    <i class="bi bi-check-circle"></i> Mark as Available
                </button>
            @else
                <button type="button"
                    class="ios-btn"
                    style="width:100%;justify-content:center;gap:8px;background:rgba(255,149,0,0.12);color:var(--orange)"
                    data-bs-toggle="modal" data-bs-target="#confirmModal"
                    data-title="Mark as Maintenance"
                    data-message="Mark {{ $slot->slot_number }} as under maintenance? It will be hidden from check-in until resolved."
                    data-form-id="statusForm"
                    data-action="Mark Maintenance"
                    data-danger="true">
                    <i class="bi bi-tools"></i> Mark as Maintenance
                </button>
            @endif
        @else
            <div style="padding:12px;background:rgba(255,59,48,0.06);border-radius:10px;font-size:13px;color:var(--gray);text-align:center">
                <i class="bi bi-lock-fill" style="margin-right:4px"></i>
                Cannot change status while occupied
            </div>
        @endif

        {{-- Checkout shortcut --}}
        @if ($realStatus === 'occupied' && $activeTicket)
            <a href="{{ route('checkout.index', ['ticket_search' => $activeTicket->ticket_id]) }}"
               class="btn-primary-ios ios-btn"
               style="width:100%;justify-content:center;gap:8px">
                <i class="bi bi-box-arrow-right"></i> Process Check-Out
            </a>
        @endif

        {{-- Slot map link --}}
        <a href="{{ route('slots.index') }}"
           class="ios-btn btn-ghost"
           style="width:100%;justify-content:center;gap:8px">
            <i class="bi bi-grid-3x3-gap"></i> Back to Slot Map
        </a>
    </div>
</div>

{{-- Currently parked --}}
@if ($realStatus === 'occupied' && $activeTicket)
    <div style="font-size:11px;font-weight:600;color:var(--gray);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px;padding:0 2px">
        Currently Parked
    </div>
    <div class="card-ios card-ios-p" style="margin-bottom:12px">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:44px;height:44px;border-radius:12px;background:rgba(0,122,255,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="bi bi-car-front-fill" style="font-size:20px;color:var(--blue)"></i>
                </div>
                <div>
                    <div style="font-size:18px;font-weight:700;color:var(--label)">
                        {{ $activeTicket->vehicle->plate_number ?? 'No Plate' }}
                    </div>
                    <div style="font-size:12px;color:var(--gray);margin-top:2px">
                        {{ ucfirst($activeTicket->vehicle->vehicle_type ?? '') }}
                    </div>
                </div>
            </div>
            <span class="pill pill-blue" style="font-size:12px">{{ $activeTicket->ticket_id }}</span>
        </div>

        @php
            $mins  = \Carbon\Carbon::parse($activeTicket->entry_time)->diffInMinutes(now());
            $hours = $mins / 60;
            $h     = intdiv($mins, 60);
            $m     = $mins % 60;
            $fee   = $activeTicket->feeRate ? $activeTicket->feeRate->calculateFee($hours) : 0;
        @endphp

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px">
            <div style="background:var(--bg);border-radius:12px;padding:12px 14px">
                <div style="font-size:11px;color:var(--gray);margin-bottom:4px">Entry Time</div>
                <div style="font-size:14px;font-weight:600;color:var(--label)">
                    {{ \Carbon\Carbon::parse($activeTicket->entry_time)->format('H:i') }}
                </div>
                <div style="font-size:11px;color:var(--gray);margin-top:1px">
                    {{ \Carbon\Carbon::parse($activeTicket->entry_time)->format('d M') }}
                </div>
            </div>
            <div style="background:var(--bg);border-radius:12px;padding:12px 14px">
                <div style="font-size:11px;color:var(--gray);margin-bottom:4px">Duration</div>
                <div style="font-size:14px;font-weight:600;color:var(--label)">
                    {{ $h > 0 ? $h . 'h ' : '' }}{{ $m }}m
                </div>
                <div style="font-size:11px;color:var(--gray);margin-top:1px">
                    {{ $hours < ($activeTicket->feeRate->threshold_hours ?? 5) ? 'Short stay' : 'Long stay' }}
                </div>
            </div>
            <div style="background:var(--bg);border-radius:12px;padding:12px 14px">
                <div style="font-size:11px;color:var(--gray);margin-bottom:4px">Est. Fee</div>
                <div style="font-size:14px;font-weight:600;color:var(--green)">${{ number_format($fee, 2) }}</div>
                <div style="font-size:11px;color:var(--gray);margin-top:1px">
                    {{ number_format($fee * 4000) }} KHR
                </div>
            </div>
            <div style="background:var(--bg);border-radius:12px;padding:12px 14px">
                <div style="font-size:11px;color:var(--gray);margin-bottom:4px">Staff</div>
                <div style="font-size:13px;font-weight:500;color:var(--label)">
                    {{ $activeTicket->staff->full_name ?? '—' }}
                </div>
            </div>
        </div>
    </div>
@endif

{{-- Maintenance notice --}}
@if ($realStatus === 'maintenance')
    <div style="background:rgba(255,149,0,0.08);border:1px solid rgba(255,149,0,0.2);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:flex-start;gap:12px">
        <i class="bi bi-tools" style="font-size:18px;color:var(--orange);flex-shrink:0;margin-top:1px"></i>
        <div>
            <div style="font-size:14px;font-weight:600;color:var(--orange);margin-bottom:3px">Under Maintenance</div>
            <div style="font-size:13px;color:var(--gray)">This slot is excluded from check-in until marked available again. Use the button above to resolve.</div>
        </div>
    </div>
@endif

{{-- Available notice --}}
@if ($realStatus === 'available')
    <div style="background:rgba(52,199,89,0.08);border:1px solid rgba(52,199,89,0.2);border-radius:var(--radius);padding:16px 18px;display:flex;align-items:center;gap:12px">
        <i class="bi bi-check-circle-fill" style="font-size:18px;color:var(--green);flex-shrink:0"></i>
        <div style="font-size:14px;font-weight:500;color:#1a7a30">Slot is free and ready for check-in</div>
    </div>
@endif

@endsection