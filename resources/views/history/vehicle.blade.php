<x-layout title="{{ $vehicle->plate_number }} History">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('history.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div>
            <div class="page-title" style="font-size:22px">{{ $vehicle->plate_number }}</div>
            <div class="page-sub"><x-type-badge :type="$vehicle->vehicle_type" /></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-val">{{ $totalSessions }}</div><div class="stat-sub">Sessions</div></div></div>
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-val">{{ $h }}h {{ $m }}m</div><div class="stat-sub">Total Time</div></div></div>
        <div class="col-md-4"><div class="stat-card text-center"><div class="stat-val">${{ number_format($totalPaid,2) }}</div><div class="stat-sub">Total Paid</div></div></div>
    </div>

    <div class="card-ios card-ios-p">
        <div class="section-hdr">All Sessions</div>
        @forelse ($sessions as $s)
            @php
                $dur='-';
                if($s->exit_time){$d=\Carbon\Carbon::parse($s->entry_time)->diff(\Carbon\Carbon::parse($s->exit_time));$dur=($d->days?$d->days.'d ':'').$d->h.'h '.$d->i.'m';}
                elseif($s->status==='active'){$d=\Carbon\Carbon::parse($s->entry_time)->diff(now());$dur=($d->days?$d->days.'d ':'').$d->h.'h '.$d->i.'m';}
            @endphp
            <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:38px;height:38px;border-radius:10px;background:var(--gray6);display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-clock-fill" style="color:var(--gray);font-size:15px"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600">{{ $s->ticket_id }}
                            <span class="pill {{ $s->status==='active'?'pill-green':($s->status==='completed'?'pill-blue':'pill-gray') }} ms-1">{{ ucfirst($s->status) }}</span>
                        </div>
                        <div style="font-size:12px;color:var(--gray)">
                            Slot {{ $s->slot->slot_number??'N/A' }} ·
                            {{ \Carbon\Carbon::parse($s->entry_time)->format('M d, g:i A') }}
                            @if($s->exit_time) &rarr; {{ \Carbon\Carbon::parse($s->exit_time)->format('g:i A') }} @endif
                            · {{ $dur }}
                        </div>
                        @if(auth()->user()->isAdmin() && $s->staff)
                            <div style="font-size:11px;color:var(--gray2)"><i class="bi bi-person-fill me-1"></i>{{ $s->staff->full_name }}</div>
                        @endif
                    </div>
                </div>
                @if($s->payment)
                    <div class="text-end">
                        <div style="font-weight:700">${{ number_format($s->payment->total_fee,2) }}</div>
                        <div style="font-size:12px;color:var(--gray)">{{ ucfirst($s->payment->payment_method) }}</div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-4" style="color:var(--gray)">No sessions found.</div>
        @endforelse
    </div>
</x-layout>
