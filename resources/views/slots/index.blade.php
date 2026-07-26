<x-layout title="Slot Map">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-title">Slot Map</div>
        <div class="d-flex gap-2 align-items-center">
            <span class="pill pill-green"><span class="dot dot-green me-1"></span> {{ $available }} Free</span>
            <span class="pill pill-red"><span class="dot dot-red me-1"></span> {{ $occupied }} Occupied</span>
            <span class="pill pill-orange"><span class="dot dot-orange me-1"></span> {{ $maintenance }} Maintenance</span>
            <div style="width:100px;height:6px;background:var(--gray5);border-radius:100px;overflow:hidden;margin-left:4px">
                <div style="width:{{ $occupancyPercent }}%;height:100%;background:var(--red);border-radius:100px"></div>
            </div>
            <span style="font-size:13px;font-weight:700">{{ $occupancyPercent }}%</span>
        </div>
    </div>

    @foreach ($zones as $zone)
        <div class="card-ios card-ios-p mb-3">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--gray);letter-spacing:0.6px;margin-bottom:14px">{{ $zone->zone_name }}</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($zone->slots as $slot)
                    @php
                        $at = $activeTickets->get($slot->slot_id);
                        $bg = match($slot->status) { 'occupied'=>'var(--red)', 'maintenance'=>'var(--orange)', default=>'var(--green)' };
                    @endphp
                    <a href="{{ route('slots.show', $slot->slot_id) }}" style="width:62px;height:50px;border-radius:11px;background:{{ $bg }};color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:12px;font-weight:700;transition:all 0.15s;opacity:{{ $slot->status==='maintenance'?'0.7':'1' }}">
                        {{ $slot->slot_number }}
                        @if ($at)<span style="font-size:8px;opacity:0.85;margin-top:1px">{{ Str::limit($at->vehicle->plate_number??'',6) }}</span>@endif
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</x-layout>
