<x-layout title="Dashboard">
@php
    $hour = now()->format('H');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $occupancyPercent = $totalSlots > 0 ? round(($occupied / $totalSlots) * 100) : 0;
    $periodLabel = ['today'=>'Today','week'=>'This Week','month'=>'This Month','year'=>'This Year'][$period] ?? 'Today';
@endphp

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <div class="page-title">{{ $greeting }}, {{ auth()->user()->full_name }}</div>
        <div class="page-sub">Here's what's happening at the parking facility.</div>
    </div>
    <div class="seg">
        @foreach (['today'=>'Today','week'=>'Week','month'=>'Month','year'=>'Year'] as $k=>$l)
            <a href="{{ route('dashboard', ['period'=>$k]) }}" class="{{ $period===$k?'on':'' }}">{{ $l }}</a>
        @endforeach
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach ([
        ['Total Slots',      $totalSlots,    'All parking bays',            'bi-geo-alt-fill',      '#007aff'],
        ['Occupied',         $occupied,      $occupancyPercent.'% occupancy','bi-car-front-fill',    '#ff3b30'],
        ['Available',        $available,     'Ready to park',               'bi-check-circle-fill', '#34c759'],
        [$periodLabel.' Revenue', '$'.number_format($periodRevenue,2), $periodTransactions.' transactions', 'bi-banknote', '#ff9500'],
    ] as [$lbl,$val,$sub,$icon,$clr])
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="stat-label">{{ $lbl }}</div>
                        <div class="stat-val">{{ $val }}</div>
                        <div class="stat-sub">{{ $sub }}</div>
                    </div>
                    <div style="width:38px;height:38px;border-radius:11px;background:{{ $clr }}15;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi {{ $icon }}" style="color:{{ $clr }};font-size:17px"></i>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card-ios card-ios-p">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div style="font-size:16px;font-weight:700">Today's Traffic</div>
                    <div style="font-size:13px;color:var(--gray)">Hourly vehicle activity</div>
                </div>
                <div class="island"><span class="dot dot-green"></span> {{ $activeTickets->count() }} Active</div>
            </div>
            <div style="height:200px"><canvas id="trafficChart"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-ios card-ios-p">
            <div style="font-size:16px;font-weight:700;margin-bottom:12px">Slot Occupancy</div>
            <div style="height:160px"><canvas id="occupancyChart"></canvas></div>
            <div class="mt-3" style="font-size:13px">
                <div class="d-flex justify-content-between mb-2"><span><span class="dot dot-red me-2"></span>Occupied</span><strong>{{ $occupied }}</strong></div>
                <div class="d-flex justify-content-between mb-2"><span><span class="dot dot-green me-2"></span>Available</span><strong>{{ $available }}</strong></div>
                <div class="d-flex justify-content-between"><span><span class="dot dot-orange me-2"></span>Maintenance</span><strong>{{ $maintenance }}</strong></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card-ios card-ios-p">
            <div style="font-size:16px;font-weight:700;margin-bottom:16px">Quick Actions</div>
            <div class="row g-2">
                @foreach ([
                    ['checkin.index','bi-arrow-down-right-circle-fill','Check-In','#34c759'],
                    ['checkout.index','bi-arrow-up-right-circle-fill','Check-Out','#ff9500'],
                    ['slots.index','bi-grid-fill','Slot Map','#af52de'],
                    ['vehicles.index','bi-car-front-fill','Vehicles','#007aff'],
                ] as [$route,$icon,$lbl,$clr])
                    <div class="col-6">
                        <a href="{{ route($route) }}" class="d-flex flex-column align-items-center gap-2 p-3" style="background:var(--gray6);border-radius:14px;color:var(--label);transition:all 0.15s">
                            <i class="bi {{ $icon }}" style="font-size:22px;color:{{ $clr }}"></i>
                            <span style="font-size:12px;font-weight:600">{{ $lbl }}</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-ios card-ios-p">
            <div class="d-flex justify-content-between mb-3">
                <div style="font-size:16px;font-weight:700">Currently Parked</div>
                <a href="{{ route('history.index') }}" style="color:var(--blue);font-size:14px;font-weight:600">View all</a>
            </div>
            @forelse ($recentActivity as $a)
                <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:38px;height:38px;border-radius:11px;background:var(--gray6);display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-car-front-fill" style="color:var(--blue)"></i>
                        </div>
                        <div>
                            <div style="font-size:15px;font-weight:600">{{ $a->plate_number ?? 'No plate' }}</div>
                            <div style="font-size:12px;color:var(--gray)">{{ \Carbon\Carbon::parse($a->entry_time)->format('g:i A') }} · Slot {{ $a->slot_number }}</div>
                        </div>
                        <x-type-badge :type="$a->vehicle_type" />
                    </div>
                    <span class="pill pill-green">Parked</span>
                </div>
            @empty
                <div class="text-center py-5">
                    <div style="width:52px;height:52px;border-radius:50%;background:var(--gray6);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                        <i class="bi bi-car-front" style="font-size:22px;color:var(--gray2)"></i>
                    </div>
                    <div style="font-size:15px;font-weight:600;color:var(--label2)">No vehicles parked right now</div>
                    <div style="font-size:13px;color:var(--gray);margin-top:4px">Check-in a vehicle to get started</div>
                    <a href="{{ route('checkin.index') }}" class="ios-btn btn-primary-ios btn-sm-ios d-inline-flex mt-3">Check-In Vehicle</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<x-slot:scripts>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        new Chart(document.getElementById('trafficChart'), {
            type:'line',
            data:{labels:{!! json_encode(array_map(fn($h)=>($h%12?:12).($h<12?'AM':'PM'),array_keys($trafficData))) !!},datasets:[{data:{!! json_encode(array_values($trafficData)) !!},borderColor:'#007aff',backgroundColor:'rgba(0,122,255,0.07)',fill:true,tension:0.4,pointRadius:0,borderWidth:2.5}]},
            options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{font:{size:11}}},y:{beginAtZero:true,grid:{color:'rgba(0,0,0,0.04)'},ticks:{font:{size:11}}}}}
        });
        new Chart(document.getElementById('occupancyChart'), {
            type:'doughnut',
            data:{labels:['Occupied','Available','Maintenance'],datasets:[{data:[{{ $occupied }},{{ $available }},{{ $maintenance }}],backgroundColor:['#ff3b30','#34c759','#ff9500'],borderWidth:0,spacing:3}]},
            options:{responsive:true,maintainAspectRatio:false,cutout:'74%',plugins:{legend:{display:false}}},
            plugins:[{id:'ct',beforeDraw(c){const{ctx,width:w,height:h}=c;ctx.save();ctx.font='800 24px Inter,sans-serif';ctx.textAlign='center';ctx.textBaseline='middle';ctx.fillStyle='#1c1c1e';ctx.fillText('{{ $occupancyPercent }}%',w/2,h/2-6);ctx.font='500 12px Inter,sans-serif';ctx.fillStyle='#8e8e93';ctx.fillText('Full',w/2,h/2+14);ctx.restore();}}]
        });
    </script>
</x-slot:scripts>
</x-layout>
