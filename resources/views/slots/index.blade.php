<x-layout title="Slot Map">
@php
$zoneColors = [
    'car'        => ['bg'=>'#e8f1ff','border'=>'#007aff','dot'=>'#007aff','label'=>'#0058cc','slot_avail'=>'#007aff','name'=>'Car Zone'],
    'motorcycle' => ['bg'=>'#e8f9ee','border'=>'#34c759','dot'=>'#34c759','label'=>'#1a7a30','slot_avail'=>'#34c759','name'=>'Motorcycle Zone'],
    'tricycle'   => ['bg'=>'#f3eaff','border'=>'#af52de','dot'=>'#af52de','label'=>'#7a2da8','slot_avail'=>'#af52de','name'=>'Tricycle Zone'],
    'bike'       => ['bg'=>'#fff3e0','border'=>'#ff9500','dot'=>'#ff9500','label'=>'#b86a00','slot_avail'=>'#ff9500','name'=>'Bike Zone'],
];
@endphp

<div class="page-header d-flex justify-content-between align-items-start">
    <div><div class="page-title">Slot Map</div><div class="page-sub">Real-time parking overview</div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col">
        <div class="stat-card d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(142,142,147,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-grid-3x3-gap-fill" style="color:var(--gray);font-size:18px"></i>
            </div>
            <div><div class="stat-label">Total Slots</div><div class="stat-val" style="font-size:22px">{{ $total }}</div></div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(52,199,89,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-check-circle-fill" style="color:var(--green);font-size:18px"></i>
            </div>
            <div><div class="stat-label">Available</div><div class="stat-val" style="font-size:22px;color:var(--green)">{{ $available }}</div></div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,59,48,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-car-front-fill" style="color:var(--red);font-size:18px"></i>
            </div>
            <div><div class="stat-label">Occupied</div><div class="stat-val" style="font-size:22px;color:var(--red)">{{ $occupied }}</div></div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card d-flex align-items-center gap-3">
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,149,0,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="bi bi-cone-striped" style="color:var(--orange);font-size:18px"></i>
            </div>
            <div><div class="stat-label">Maintenance</div><div class="stat-val" style="font-size:22px;color:var(--orange)">{{ $maintenance }}</div></div>
        </div>
    </div>
    <div class="col">
        <div class="stat-card">
            <div class="stat-label mb-2">Occupancy</div>
            <div style="font-size:22px;font-weight:800;margin-bottom:8px">{{ $occupancyPercent }}%</div>
            <div style="height:8px;background:var(--gray5);border-radius:100px;overflow:hidden">
                <div style="width:{{ $occupancyPercent }}%;height:100%;background:{{ $occupancyPercent > 80 ? 'var(--red)' : ($occupancyPercent > 50 ? 'var(--orange)' : 'var(--green)') }};border-radius:100px;transition:width 0.5s ease"></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="seg" id="viewToggle">
        <button class="on" id="aerialBtn" onclick="switchView('aerial')"><i class="bi bi-map-fill me-1"></i>Aerial View</button>
        <button id="listBtn" onclick="switchView('list')"><i class="bi bi-list-ul me-1"></i>List View</button>
    </div>
    <div class="d-flex gap-3 align-items-center" style="font-size:12px;font-weight:600">
        <span style="color:var(--gray)">LEGEND</span>
        <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:var(--green);margin-right:4px"></span>Available</span>
        <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:var(--red);margin-right:4px"></span>Occupied</span>
        <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:var(--orange);margin-right:4px"></span>Maintenance</span>
    </div>
</div>

<div id="aerialView">
    <div class="row g-3">
        @foreach ($zones as $zone)
            @php $zc = $zoneColors[$zone->vehicle_type] ?? $zoneColors['car']; @endphp
            <div class="col-12">
                <div style="background:{{ $zc['bg'] }};border:1.5px solid {{ $zc['border'] }}22;border-radius:16px;padding:20px">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $zc['dot'] }}"></div>
                        <span style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.7px;color:{{ $zc['label'] }}">{{ $zc['name'] }} &mdash; {{ $zone->zone_name }}</span>
                        @php
                            $zAvail = $zone->slots->filter(fn($s)=>$s->real_status==='available')->count();
                            $zOcc = $zone->slots->filter(fn($s)=>$s->real_status==='occupied')->count();
                        @endphp
                        <span class="ms-auto" style="font-size:12px;color:{{ $zc['label'] }};font-weight:600">{{ $zAvail }} free &middot; {{ $zOcc }} occupied</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($zone->slots as $slot)
                            @php
                                $rs = $slot->real_status;
                                $bg = match($rs) { 'occupied'=>'#ff3b30', 'maintenance'=>'#ff9500', default=>$zc['slot_avail'] };
                                $opacity = $rs === 'maintenance' ? '0.65' : '1';
                                $plate = $slot->active_ticket?->vehicle?->plate_number;
                            @endphp
                            <a href="{{ route('slots.show', $slot->slot_id) }}"
                                title="{{ $plate ?? ucfirst($rs) }}"
                                style="width:64px;height:56px;border-radius:12px;background:{{ $bg }};color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;font-weight:700;font-size:12px;opacity:{{ $opacity }};transition:all 0.15s;box-shadow:0 2px 8px {{ $bg }}44;position:relative">
                                {{ $slot->slot_number }}
                                @if ($plate)
                                    <span style="font-size:8px;font-weight:600;opacity:0.9;margin-top:2px;max-width:58px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;padding:0 2px">{{ $plate }}</span>
                                @elseif ($rs === 'maintenance')
                                    <i class="bi bi-cone-striped" style="font-size:10px;margin-top:2px"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div id="listView" style="display:none">
    <div class="card-ios">
        <table class="ios-table">
            <thead>
                <tr><th>Slot</th><th>Zone</th><th>Type</th><th>Status</th><th>Vehicle</th><th>Ticket</th><th style="width:50px"></th></tr>
            </thead>
            <tbody>
                @foreach ($zones as $zone)
                    @php $zc = $zoneColors[$zone->vehicle_type] ?? $zoneColors['car']; @endphp
                    @foreach ($zone->slots as $slot)
                        @php $rs = $slot->real_status; $at = $slot->active_ticket; @endphp
                        <tr>
                            <td style="font-weight:700">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:30px;border-radius:8px;background:{{ match($rs){'occupied'=>'rgba(255,59,48,0.1)','maintenance'=>'rgba(255,149,0,0.1)',default=>'rgba(52,199,89,0.1)'} }};color:{{ match($rs){'occupied'=>'var(--red)','maintenance'=>'var(--orange)',default=>'var(--green)'} }};font-size:13px">{{ $slot->slot_number }}</span>
                            </td>
                            <td style="font-size:13px;color:var(--gray)">{{ $zone->zone_code }}</td>
                            <td><span class="type-badge type-badge-{{ $zone->vehicle_type }}">{{ ucfirst($zone->vehicle_type) }}</span></td>
                            <td>
                                <span class="pill {{ match($rs){'occupied'=>'pill-red','maintenance'=>'pill-orange',default=>'pill-green'} }}">
                                    {{ ucfirst($rs) }}
                                </span>
                            </td>
                            <td style="font-weight:600">{{ $at?->vehicle?->plate_number ?? ($rs==='occupied'?'Unknown':'—') }}</td>
                            <td style="color:var(--gray);font-size:13px">{{ $at?->ticket_id ?? '—' }}</td>
                            <td><a href="{{ route('slots.show', $slot->slot_id) }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-right"></i></a></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<x-slot:scripts>
<script>
    function switchView(v) {
        document.getElementById('aerialView').style.display = v==='aerial' ? 'block' : 'none';
        document.getElementById('listView').style.display = v==='list' ? 'block' : 'none';
        document.getElementById('aerialBtn').className = v==='aerial' ? 'on' : '';
        document.getElementById('listBtn').className = v==='list' ? 'on' : '';
    }
</script>
</x-slot:scripts>
</x-layout>
