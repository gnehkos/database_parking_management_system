<x-layout title="Slot {{ $slot->slot_number }}">
@php
$zoneColors = [
    'car'        => ['bg'=>'#e8f1ff','border'=>'#007aff','color'=>'#007aff','label'=>'Car'],
    'motorcycle' => ['bg'=>'#e8f9ee','border'=>'#34c759','color'=>'#34c759','label'=>'Motorcycle'],
    'tricycle'   => ['bg'=>'#f3eaff','border'=>'#af52de','color'=>'#af52de','label'=>'Tricycle'],
    'bike'       => ['bg'=>'#fff3e0','border'=>'#ff9500','color'=>'#ff9500','label'=>'Bike'],
];
$vc = $zoneColors[$slot->zone->vehicle_type] ?? $zoneColors['car'];
$statusColor = match($slot->real_status) { 'occupied'=>'var(--red)', 'maintenance'=>'var(--orange)', default=>'var(--green)' };
$statusBg = match($slot->real_status) { 'occupied'=>'rgba(255,59,48,0.1)', 'maintenance'=>'rgba(255,149,0,0.1)', default=>'rgba(52,199,89,0.1)' };
@endphp

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('slots.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
    <div class="page-title" style="font-size:22px">Slot {{ $slot->slot_number }}</div>
    <span class="pill" style="background:{{ $statusBg }};color:{{ $statusColor }}">{{ ucfirst($slot->real_status) }}</span>
    <span class="pill" style="background:{{ $vc['bg'] }};color:{{ $vc['color'] }}">{{ $vc['label'] }} Zone</span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div style="background:{{ match($slot->real_status){'occupied'=>'linear-gradient(135deg,#ff3b30,#c0392b)','maintenance'=>'linear-gradient(135deg,#ff9500,#e67e22)',default=>'linear-gradient(135deg,'.$vc['color'].','.($vc['color']).')' } }};border-radius:20px;padding:32px;text-align:center;color:#fff;position:relative;overflow:hidden">
            <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.08)"></div>
            <div style="position:absolute;bottom:-30px;left:-10px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,0.06)"></div>
            <div style="font-size:52px;font-weight:900;letter-spacing:-2px;line-height:1;position:relative">{{ $slot->slot_number }}</div>
            <div style="font-size:13px;font-weight:600;opacity:0.85;margin-top:8px;text-transform:uppercase;letter-spacing:0.5px">{{ $vc['label'] }} Zone</div>
            <div style="font-size:11px;opacity:0.7;margin-top:4px">{{ $slot->zone->zone_name }}</div>
        </div>
    </div>
    <div class="col-md-7">
        @if ($activeTicket)
            <div style="border:2px solid var(--red);border-radius:16px;padding:20px;background:rgba(255,59,48,0.03);margin-bottom:12px">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--red);margin-bottom:6px"><i class="bi bi-record-circle-fill me-1"></i>Currently Parked</div>
                        <div style="font-size:22px;font-weight:800">{{ $activeTicket->vehicle->plate_number ?? 'No plate' }}</div>
                        <x-type-badge :type="$activeTicket->vehicle->vehicle_type" />
                    </div>
                    <a href="{{ route('checkout.payment', $activeTicket->ticket_id) }}" class="ios-btn btn-sm-ios" style="background:var(--red);color:#fff;flex-shrink:0">
                        <i class="bi bi-arrow-up-right-circle-fill me-1"></i>Check Out
                    </a>
                </div>
                <div class="row g-2">
                    @php
                        $dur = \Carbon\Carbon::parse($activeTicket->entry_time)->diff(now());
                        $durStr = ($dur->days ? $dur->days.'d ' : '').$dur->h.'h '.$dur->i.'m';
                        $hours = \Carbon\Carbon::parse($activeTicket->entry_time)->diffInMinutes(now()) / 60;
                        $estFee = $activeTicket->feeRate->calculateFee($hours);
                    @endphp
                    @foreach ([
                        ['bi-hash','Ticket ID',$activeTicket->ticket_id],
                        ['bi-clock-fill','Parked Since',\Carbon\Carbon::parse($activeTicket->entry_time)->format('M d, g:i A')],
                        ['bi-stopwatch-fill','Duration',$durStr],
                        ['bi-banknote','Est. Fee','$'.number_format($estFee,2)],
                    ] as [$icon,$lbl,$val])
                        <div class="col-6">
                            <div style="background:rgba(255,59,48,0.06);border-radius:10px;padding:10px 12px">
                                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--red);margin-bottom:4px"><i class="bi {{ $icon }} me-1"></i>{{ $lbl }}</div>
                                <div style="font-size:14px;font-weight:700">{{ $val }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(auth()->user()->isAdmin() && $activeTicket->staff)
                    <div style="font-size:12px;color:var(--gray);margin-top:12px"><i class="bi bi-person-fill me-1"></i>Checked in by {{ $activeTicket->staff->full_name }}</div>
                @endif
            </div>
        @else
            <div style="border:2px solid var(--green);border-radius:16px;padding:24px;background:rgba(52,199,89,0.04);text-align:center;margin-bottom:12px">
                <div style="width:52px;height:52px;border-radius:50%;background:rgba(52,199,89,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                    <i class="bi bi-check-circle-fill" style="color:var(--green);font-size:22px"></i>
                </div>
                <div style="font-size:16px;font-weight:700;color:var(--green)">Slot Available</div>
                <div style="font-size:13px;color:var(--gray);margin-top:4px">Ready to park a {{ $vc['label'] }}</div>
                <a href="{{ route('checkin.index') }}" class="ios-btn btn-sm-ios mt-3 d-inline-flex" style="background:var(--green);color:#fff">
                    <i class="bi bi-arrow-down-right-circle-fill me-1"></i>Check In Here
                </a>
            </div>
        @endif

        @if ($slot->real_status !== 'occupied')
            <div class="card-ios card-ios-p">
                <div class="section-hdr">Update Status</div>
                <form method="POST" action="{{ route('slots.updateStatus', $slot->slot_id) }}" class="d-flex gap-2 mt-2">
                    @csrf @method('PATCH')
                    <select name="status" class="ios-input" style="max-width:200px">
                        <option value="available" {{ $slot->status==='available'?'selected':'' }}>Available</option>
                        <option value="maintenance" {{ $slot->status==='maintenance'?'selected':'' }}>Under Maintenance</option>
                    </select>
                    <button class="ios-btn btn-primary-ios btn-sm-ios">Update</button>
                </form>
            </div>
        @endif
    </div>
</div>

<div class="card-ios card-ios-p">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-clock-history" style="color:var(--blue)"></i>
        <span style="font-size:16px;font-weight:700">Usage History</span>
    </div>
    @forelse ($usageHistory as $ticket)
        @php
            $d = \Carbon\Carbon::parse($ticket->entry_time)->diff(\Carbon\Carbon::parse($ticket->exit_time ?? now()));
            $dur = ($d->days?$d->days.'d ':'').$d->h.'h '.$d->i.'m';
        @endphp
        <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
            <div class="d-flex align-items-center gap-3">
                <div style="width:36px;height:36px;border-radius:10px;background:var(--gray6);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-clock-fill" style="color:var(--gray);font-size:14px"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600">{{ $ticket->vehicle->plate_number ?? 'No plate' }}
                        <span class="pill {{ $ticket->status==='completed'?'pill-blue':'pill-gray' }} ms-1">{{ ucfirst($ticket->status) }}</span>
                    </div>
                    <div style="font-size:12px;color:var(--gray)">
                        {{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}
                        @if($ticket->exit_time) &rarr; {{ \Carbon\Carbon::parse($ticket->exit_time)->format('g:i A') }} @endif
                        &middot; {{ $dur }}
                    </div>
                </div>
            </div>
            @if($ticket->payment)
                <span style="font-weight:700">${{ number_format($ticket->payment->total_fee,2) }}</span>
            @endif
        </div>
    @empty
        <div class="text-center py-4" style="color:var(--gray)">No usage history for this slot.</div>
    @endforelse
</div>
</x-layout>
