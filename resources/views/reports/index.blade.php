<x-layout title="Reports">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div><div class="page-title">Reports & Analytics</div><div class="page-sub">Revenue and traffic insights</div></div>
        <a href="{{ route('reports.export', request()->query()) }}" class="ios-btn btn-ghost btn-sm-ios">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
    </div>

    <div class="d-flex gap-2 flex-wrap mb-3 align-items-center">
        <div class="filter-pills">
            @foreach (['today'=>'Today','7days'=>'7 Days','month'=>'This Month','3months'=>'3 Months','6months'=>'6 Months','year'=>'This Year'] as $k=>$l)
                <a href="{{ route('reports.index', array_merge(request()->query(), ['period'=>$k])) }}" class="filter-pill {{ $period===$k?'on':'' }}">{{ $l }}</a>
            @endforeach
        </div>
        <span class="ms-auto" style="font-size:13px;color:var(--gray)">{{ $dateRange }}</span>
    </div>
    <div class="d-flex gap-2 flex-wrap mb-4">
        <div class="seg">
            @foreach (['all'=>'All Types','car'=>'Car','motorcycle'=>'Motorcycle','bike'=>'Bike','tricycle'=>'Tricycle'] as $k=>$l)
                <a href="{{ route('reports.index', array_merge(request()->query(), ['type'=>$k])) }}" class="{{ $typeFilter===$k?'on':'' }}">{{ $l }}</a>
            @endforeach
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([
            ['Period Revenue','$'.number_format($periodRevenue,2),'bi-banknote','var(--green)'],
            ["Today's Revenue",'$'.number_format($todayRevenue,2),'bi-calendar-check-fill','var(--blue)'],
            ['Avg per Session','$'.number_format($avgPerSession,2),'bi-calculator-fill','var(--orange)'],
            ['Total Transactions',$totalTransactions,'bi-receipt','var(--purple)'],
        ] as [$l,$v,$icon,$clr])
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="stat-label">{{ $l }}</div>
                        <div style="width:34px;height:34px;border-radius:10px;background:{{ $clr }}15;display:flex;align-items:center;justify-content:center">
                            <i class="bi {{ $icon }}" style="color:{{ $clr }};font-size:16px"></i>
                        </div>
                    </div>
                    <div class="stat-val">{{ $v }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-8">
            <div class="card-ios card-ios-p">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-graph-up-arrow" style="color:var(--blue);font-size:18px"></i>
                    <span style="font-size:16px;font-weight:700">Revenue Trend</span>
                </div>
                <div style="height:250px"><canvas id="revenueChart"></canvas></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-ios card-ios-p">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-pie-chart-fill" style="color:var(--purple);font-size:18px"></i>
                    <span style="font-size:16px;font-weight:700">Vehicle Types</span>
                </div>
                <div style="height:180px"><canvas id="typeChart"></canvas></div>
                <div class="mt-3" style="font-size:13px">
                    @foreach ($vehicleTypeCounts as $type=>$count)
                        @php $pct=$totalTypeCount>0?round($count/$totalTypeCount*100):0; @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <x-type-badge :type="$type" />
                            <strong>{{ $pct }}%</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card-ios card-ios-p">
        <div class="d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-trophy-fill" style="color:var(--orange);font-size:18px"></i>
            <span style="font-size:16px;font-weight:700">Top 5 Most Frequent Vehicles</span>
            <span class="pill pill-gray ms-2" style="font-size:11px">Subquery</span>
        </div>
        @if(count($topVehicles) > 0)
            <table class="ios-table">
                <thead><tr><th>#</th><th>Plate Number</th><th>Type</th><th>Total Visits</th><th>Total Spent</th></tr></thead>
                <tbody>
                    @foreach ($topVehicles as $i => $v)
                        <tr>
                            <td>
                                <span style="width:28px;height:28px;border-radius:50%;background:{{ $i===0?'var(--orange)':($i===1?'var(--gray2)':($i===2?'#cd7f32':'var(--gray5)')) }};color:{{ $i<3?'#fff':'var(--gray)' }};display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700">{{ $i+1 }}</span>
                            </td>
                            <td>
                                @if($v->plate_number)
                                    <a href="{{ route('history.vehicle', $v->plate_number) }}" style="color:var(--blue);font-weight:700">{{ $v->plate_number }}</a>
                                @else
                                    <span style="color:var(--gray)">No plate</span>
                                @endif
                            </td>
                            <td><x-type-badge :type="$v->vehicle_type" /></td>
                            <td style="font-weight:700">{{ $v->visit_count }}</td>
                            <td style="font-weight:700;color:var(--green)">${{ number_format($v->total_spent, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-4" style="color:var(--gray)">No data available.</div>
        @endif
    </div>

    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            new Chart(document.getElementById('revenueChart'),{type:'line',data:{labels:{!! json_encode(array_keys($dailyRevenue)) !!},datasets:[{data:{!! json_encode(array_values($dailyRevenue)) !!},borderColor:'#007aff',backgroundColor:'rgba(0,122,255,0.07)',fill:true,tension:0.4,pointRadius:0,borderWidth:2.5}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{x:{grid:{display:false},ticks:{font:{size:11}}},y:{beginAtZero:true,grid:{color:'rgba(0,0,0,0.04)'},ticks:{font:{size:11}}}}}});
            new Chart(document.getElementById('typeChart'),{type:'doughnut',data:{labels:{!! json_encode(array_map('ucfirst',array_keys($vehicleTypeCounts))) !!},datasets:[{data:{!! json_encode(array_values($vehicleTypeCounts)) !!},backgroundColor:['#007aff','#34c759','#ff9500','#af52de'],borderWidth:0,spacing:3}]},options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{display:false}}}});
        </script>
    </x-slot:scripts>
</x-layout>
