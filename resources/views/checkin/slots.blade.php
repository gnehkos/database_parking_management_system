<x-layout title="Slot Assignment">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('checkin.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div>
            <div class="page-title" style="font-size:22px">Select a Slot</div>
            <div class="d-flex align-items-center gap-2 mt-1">
                <span style="font-weight:600">{{ $plateNumber ?: 'No plate' }}</span>
                <x-type-badge :type="$vehicleType" />
                <span style="color:var(--gray);font-size:13px">· {{ $availableCount }} available</span>
            </div>
        </div>
    </div>

    <div class="d-flex gap-4 mb-4" style="font-size:13px;font-weight:500">
        <span><span class="dot dot-green me-1"></span> Available</span>
        <span><span class="dot dot-blue me-1"></span> Selected</span>
        <span><span class="dot dot-red me-1"></span> Occupied</span>
        <span><span class="dot dot-orange me-1"></span> Maintenance</span>
    </div>

    <form method="POST" action="{{ route('checkin.assign') }}">
        @csrf
        <input type="hidden" name="vehicle_type" value="{{ $vehicleType }}">
        <input type="hidden" name="plate_number" value="{{ $plateNumber }}">
        <input type="hidden" name="plate_type" value="{{ $plateType }}">
        <input type="hidden" name="slot_id" id="selectedSlotId">

        @foreach ($zones as $zone)
            <div class="card-ios card-ios-p mb-3">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--gray);letter-spacing:0.6px;margin-bottom:12px">{{ $zone->zone_name }}</div>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($zone->slots as $slot)
                        @php
                            $isTarget = $zone->vehicle_type === $vehicleType;

                            if ($slot->status === 'maintenance') {
                                $isAvail = false;
                                $bg      = 'var(--orange)';
                            } elseif (!$isTarget) {
                                $isAvail = false;
                                $bg      = 'var(--gray)';
                            } else {
                                $hasActiveTicket = isset($activeTickets[$slot->slot_id]);
                                $isAvail = !$hasActiveTicket;
                                $bg      = $hasActiveTicket ? 'var(--red)' : 'var(--green)';
                            }
                        @endphp
                        <button type="button" class="slot-btn"
                            style="width:58px;height:46px;border-radius:10px;border:none;background:{{ $bg }};color:{{ $txtColor }};font-size:12px;font-weight:700;cursor:{{ $isAvail?'pointer':'not-allowed' }};opacity:{{ $isAvail?'1':'0.45' }};transition:all 0.15s"
                            {{ !$isAvail?'disabled':'' }}
                            data-slot-id="{{ $slot->slot_id }}" data-slot-name="{{ $slot->slot_number }}"
                            onclick="selectSlot(this)">
                            {{ $slot->slot_number }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach

        <button type="submit" class="ios-btn w-100 py-3 mt-2" id="assignBtn" disabled
            style="background:var(--label);color:#fff;font-size:15px;border-radius:16px">
            Select a slot above
        </button>
    </form>

    <x-slot:scripts>
        <script>
            function selectSlot(btn) {
                document.querySelectorAll('.slot-btn:not([disabled])').forEach(b => { if(b.dataset.slotId !== btn.dataset.slotId) b.style.background='var(--green)'; });
                btn.style.background='var(--blue)';
                document.getElementById('selectedSlotId').value = btn.dataset.slotId;
                const ab = document.getElementById('assignBtn');
                ab.disabled = false;
                ab.textContent = 'Assign Slot ' + btn.dataset.slotName;
            }
        </script>
    </x-slot:scripts>
</x-layout>
