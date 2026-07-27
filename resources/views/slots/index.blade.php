@extends('components.layout')

@section('title', 'Slot Map')

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:1.25rem;flex-wrap:wrap">
    <div>
        <div style="font-size:26px;font-weight:800;letter-spacing:-0.5px;color:var(--label)">Slot Map</div>
        <div style="font-size:14px;color:var(--gray);margin-top:2px">Real-time parking slot overview</div>
    </div>
    <div class="seg">
        <button class="on" id="btn-aerial" onclick="setView('aerial')">Aerial</button>
        <button id="btn-list" onclick="setView('list')">List</button>
    </div>
</div>

{{-- Stat cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-label">Total Slots</div>
        <div class="stat-val">{{ $total }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label" style="color:var(--green)">Available</div>
        <div class="stat-val" style="color:var(--green)">{{ $available }}</div>
        <div class="stat-sub">{{ $total > 0 ? round($available / $total * 100) : 0 }}% free</div>
    </div>
    <div class="stat-card">
        <div class="stat-label" style="color:var(--red)">Occupied</div>
        <div class="stat-val" style="color:var(--red)">{{ $occupied }}</div>
        <div class="stat-sub">{{ $total > 0 ? round($occupied / $total * 100) : 0 }}% in use</div>
    </div>
    <div class="stat-card">
        <div class="stat-label" style="color:var(--orange)">Maintenance</div>
        <div class="stat-val" style="color:var(--orange)">{{ $maintenance }}</div>
        <div class="stat-sub">{{ $total > 0 ? round($maintenance / $total * 100) : 0 }}% unavail.</div>
    </div>
</div>

{{-- ======================== AERIAL VIEW ======================== --}}
<div id="view-aerial">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        @foreach ($zones as $zone)
            @php
                $zFree  = $zone->slots->filter(fn($s) =>
                    $s->status !== 'maintenance' && !isset($activeTickets[$s->slot_id])
                )->count();
                $zTotal = $zone->slots->count();
                $zOcc   = $zone->slots->filter(fn($s) =>
                    $s->status !== 'maintenance' && isset($activeTickets[$s->slot_id])
                )->count();
                $zMaint = $zone->slots->where('status', 'maintenance')->count();
            @endphp
            <div class="card-ios card-ios-p">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px">
                    <div>
                        <div style="font-size:14px;font-weight:600;color:var(--label)">{{ $zone->zone_name }}</div>
                        <div style="font-size:12px;color:var(--gray);margin-top:2px">{{ ucfirst($zone->vehicle_type) }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <div style="font-size:20px;font-weight:700;line-height:1;color:{{ $zFree > 0 ? 'var(--green)' : 'var(--red)' }}">
                            {{ $zFree }}
                        </div>
                        <div style="font-size:11px;color:var(--gray);margin-top:2px">of {{ $zTotal }} free</div>
                    </div>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:5px">
                    @foreach ($zone->slots as $slot)
                        @php
                            if ($slot->status === 'maintenance') {
                                $rs = 'maintenance'; $tk = null;
                            } else {
                                $tk = $activeTickets[$slot->slot_id] ?? null;
                                $rs = $tk ? 'occupied' : 'available';
                            }
                            $bg = match($rs) {
                                'occupied'    => 'var(--red)',
                                'maintenance' => 'var(--orange)',
                                default       => 'var(--green)',
                            };
                        @endphp
                        <a href="{{ route('slots.show', $slot->slot_id) }}"
                           style="background:{{ $bg }};width:52px;height:42px;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;gap:2px;flex-shrink:0;opacity:{{ $rs === 'maintenance' ? '0.7' : '1' }}"
                           title="{{ $slot->slot_number }}{{ $tk?->vehicle ? ' · ' . $tk->vehicle->plate_number : '' }}">
                            <span style="font-size:11px;font-weight:600;color:#fff;line-height:1;letter-spacing:0.02em">
                                {{ $slot->slot_number }}
                            </span>
                            @if ($tk?->vehicle)
                                <span style="font-size:8px;color:rgba(255,255,255,0.85);line-height:1;max-width:48px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;padding:0 2px">
                                    {{ $tk->vehicle->plate_number }}
                                </span>
                            @elseif ($rs === 'maintenance')
                                <span style="font-size:8px;color:rgba(255,255,255,0.8);line-height:1">Maint.</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                @if ($zOcc > 0 || $zMaint > 0)
                    <div style="display:flex;gap:12px;margin-top:12px;padding-top:10px;border-top:0.5px solid var(--gray5)">
                        @if ($zOcc > 0)
                            <div style="font-size:11px;color:var(--gray)">
                                <span style="color:var(--red);font-weight:600">{{ $zOcc }}</span> occupied
                            </div>
                        @endif
                        @if ($zMaint > 0)
                            <div style="font-size:11px;color:var(--gray)">
                                <span style="color:var(--orange);font-weight:600">{{ $zMaint }}</span> maintenance
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div style="display:flex;gap:16px;margin-top:14px;padding:0 2px">
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--gray)">
            <div style="width:10px;height:10px;border-radius:3px;background:var(--green)"></div> Available
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--gray)">
            <div style="width:10px;height:10px;border-radius:3px;background:var(--red)"></div> Occupied
        </div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--gray)">
            <div style="width:10px;height:10px;border-radius:3px;background:var(--orange)"></div> Maintenance
        </div>
    </div>
</div>

{{-- ======================== LIST VIEW ======================== --}}
<div id="view-list" style="display:none">

    {{-- Search bar --}}
    <div style="margin-bottom:16px">
        <div style="position:relative">
            <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:14px"></i>
            <input type="text" id="slotSearch"
                   class="ios-input"
                   style="padding-left:42px"
                   placeholder="Search plate number or slot..."
                   oninput="filterSlots(this.value)">
        </div>
    </div>

    <div id="noResults" style="display:none" class="card-ios card-ios-p text-center py-4">
        <div style="font-size:14px;color:var(--gray)">No slots match your search</div>
    </div>

    @foreach ($zones as $zone)
        <div class="zone-list-group" style="margin-bottom:20px">
            <div style="font-size:11px;font-weight:600;color:var(--gray);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;padding:0 2px">
                {{ $zone->zone_name }} &middot; {{ ucfirst($zone->vehicle_type) }}
            </div>
            <div class="card-ios">
                @foreach ($zone->slots as $slot)
                    @php
                        if ($slot->status === 'maintenance') {
                            $rs = 'maintenance'; $tk = null;
                        } else {
                            $tk = $activeTickets[$slot->slot_id] ?? null;
                            $rs = $tk ? 'occupied' : 'available';
                        }
                        $dot = match($rs) {
                            'occupied'    => 'var(--red)',
                            'maintenance' => 'var(--orange)',
                            default       => 'var(--green)',
                        };
                    @endphp
                    <div class="slot-list-row grouped-row"
                         data-slot="{{ strtolower($slot->slot_number) }}"
                         data-plate="{{ strtolower($tk?->vehicle?->plate_number ?? '') }}"
                         style="display:flex;align-items:center;justify-content:space-between;padding:11px 14px">

                        <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $dot }};flex-shrink:0"></div>
                            <div style="min-width:0">
                                <div style="font-size:14px;font-weight:600;color:var(--label)">{{ $slot->slot_number }}</div>
                                @if ($tk?->vehicle)
                                    <div style="font-size:12px;color:var(--gray);margin-top:1px">
                                        {{ $tk->vehicle->plate_number }}
                                        &middot; {{ ucfirst($tk->vehicle->vehicle_type) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                            @if ($rs === 'available')
                                <span class="pill pill-green">Available</span>
                            @elseif ($rs === 'occupied')
                                <span class="pill pill-red">Occupied</span>
                            @else
                                <span class="pill pill-orange">Maintenance</span>
                            @endif

                            @if ($rs === 'occupied' && $tk)
                                <a href="{{ route('slots.show', $slot->slot_id) }}"
                                   class="ios-btn btn-ghost btn-sm-ios"
                                   title="View / correct plate">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('checkout.payment', $tk->ticket_id) }}"
                                   class="ios-btn btn-sm-ios"
                                   style="background:var(--red);color:#fff">
                                    <i class="bi bi-arrow-up-right-circle-fill me-1"></i> Check Out
                                </a>
                            @else
                                <a href="{{ route('slots.show', $slot->slot_id) }}"
                                   class="ios-btn btn-ghost btn-sm-ios">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>
function setView(v) {
    document.getElementById('view-aerial').style.display = v === 'aerial' ? 'block' : 'none';
    document.getElementById('view-list').style.display   = v === 'list'   ? 'block' : 'none';
    document.getElementById('btn-aerial').classList.toggle('on', v === 'aerial');
    document.getElementById('btn-list').classList.toggle('on', v === 'list');
    localStorage.setItem('slotView', v);
}
const saved = localStorage.getItem('slotView');
if (saved) setView(saved);

function filterSlots(q) {
    q = q.toLowerCase().trim();
    let anyVisible = false;

    document.querySelectorAll('.slot-list-row').forEach(row => {
        const slot  = row.dataset.slot  || '';
        const plate = row.dataset.plate || '';
        const match = !q || slot.includes(q) || plate.includes(q);
        row.style.display = match ? 'flex' : 'none';
        if (match) anyVisible = true;
    });

    document.querySelectorAll('.zone-list-group').forEach(group => {
        const visible = [...group.querySelectorAll('.slot-list-row')]
            .some(r => r.style.display !== 'none');
        group.style.display = visible ? 'block' : 'none';
    });

    document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';
}
</script>

@endsection