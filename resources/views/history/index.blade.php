<x-layout title="History">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <div class="page-title">History</div>
            <div class="page-sub">All parking entry and exit records</div>
        </div>
        <a href="{{ route('history.export', request()->query()) }}" class="ios-btn btn-ghost btn-sm-ios">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="stat-card"><div class="stat-label">Total Sessions</div><div class="stat-val">{{ $totalSessions }}</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-label">Completed</div><div class="stat-val">{{ $completedSessions }}</div></div></div>
        <div class="col-md-4"><div class="stat-card"><div class="stat-label">Total Revenue</div><div class="stat-val">${{ number_format($totalRevenue,2) }}</div></div></div>
    </div>

    <div class="card-ios card-ios-p">
        <div class="d-flex gap-2 mb-3">
            <form method="GET" action="{{ route('history.index') }}" class="d-flex gap-2" style="flex:1;max-width:400px">
                @foreach (request()->except('search','page') as $k=>$v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <div style="position:relative;flex:1">
                    <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:15px"></i>
                    <input type="text" name="search" class="ios-input" style="padding-left:42px" placeholder="Search plate number..." value="{{ request('search') }}" autocomplete="off">
                </div>
                @if(request('search'))
                    <a href="{{ route('history.index', request()->except('search','page')) }}" class="ios-btn btn-ghost btn-sm-ios">Clear</a>
                @endif
            </form>
        </div>

        @if(!request('search'))
            <div class="d-flex gap-2 flex-wrap mb-4 align-items-center">
                <div class="filter-pills">
                    @foreach (['all'=>'All Time','today'=>'Today','7days'=>'7 Days','month'=>'This Month','3months'=>'3 Months','year'=>'This Year'] as $k=>$l)
                        <a href="{{ route('history.index', array_merge(request()->query(), ['period'=>$k])) }}" class="filter-pill {{ $period===$k?'on':'' }}">{{ $l }}</a>
                    @endforeach
                </div>
                <div class="seg">
                    @foreach (['all'=>'All','active'=>'Active','completed'=>'Completed'] as $k=>$l)
                        <a href="{{ route('history.index', array_merge(request()->query(), ['status_filter'=>$k])) }}" class="{{ $statusFilter===$k?'on':'' }}">{{ $l }}</a>
                    @endforeach
                </div>
                <div class="filter-pills">
                    @foreach (['all'=>'All','car'=>'Car','motorcycle'=>'Moto','bike'=>'Bike','tricycle'=>'Tricycle'] as $k=>$l)
                        <a href="{{ route('history.index', array_merge(request()->query(), ['type'=>$k])) }}" class="filter-pill {{ $typeFilter===$k?'on':'' }}">{{ $l }}</a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mb-3" style="font-size:13px;color:var(--gray)">Showing results for "<strong style="color:var(--label)">{{ request('search') }}</strong>"</div>
        @endif

        <div class="table-responsive">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>Ticket</th><th>Plate</th><th>Type</th><th>Slot</th><th>Entry</th><th>Exit</th><th>Duration</th><th>Fee</th><th>Status</th>
                        @if(auth()->user()->isAdmin())<th>Staff</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        @php
                            $dur='-';
                            if($ticket->exit_time){$d=\Carbon\Carbon::parse($ticket->entry_time)->diff(\Carbon\Carbon::parse($ticket->exit_time));$dur=($d->days?$d->days.'d ':'').$d->h.'h '.$d->i.'m';}
                            elseif($ticket->status==='active'){$d=\Carbon\Carbon::parse($ticket->entry_time)->diff(now());$dur=($d->days?$d->days.'d ':'').$d->h.'h '.$d->i.'m';}
                        @endphp
                        <tr>
                            <td style="font-weight:600;white-space:nowrap">{{ $ticket->ticket_id }}</td>
                            <td>
                                @if($ticket->vehicle->plate_number)
                                    <a href="{{ route('history.vehicle', $ticket->vehicle->plate_number) }}" style="color:var(--blue);font-weight:700">{{ $ticket->vehicle->plate_number }}</a>
                                @else
                                    <span style="color:var(--gray)">No plate</span>
                                @endif
                            </td>
                            <td><x-type-badge :type="$ticket->vehicle->vehicle_type" /></td>
                            <td style="color:var(--gray)">{{ $ticket->slot->slot_number??'N/A' }}</td>
                            <td style="color:var(--gray);white-space:nowrap">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}</td>
                            <td style="color:var(--gray);white-space:nowrap">{{ $ticket->exit_time?\Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A'):'-' }}</td>
                            <td style="white-space:nowrap">{{ $dur }}</td>
                            <td style="font-weight:600">{{ $ticket->payment?'$'.number_format($ticket->payment->total_fee,2):'-' }}</td>
                            <td><span class="pill {{ $ticket->status==='active'?'pill-green':($ticket->status==='completed'?'pill-blue':($ticket->status==='cancelled'?'pill-red':'pill-gray')) }}">{{ ucfirst($ticket->status) }}</span></td>
                            @if(auth()->user()->isAdmin())
                                <td style="font-size:12px;color:var(--gray);white-space:nowrap">{{ $ticket->staff->full_name??'-' }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin()?10:9 }}">
                                <div class="text-center py-5">
                                    <div style="width:52px;height:52px;border-radius:50%;background:var(--gray6);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                                        <i class="bi bi-clock-history" style="font-size:22px;color:var(--gray2)"></i>
                                    </div>
                                    <div style="font-size:15px;font-weight:600;color:var(--label2)">No records found</div>
                                    <div style="font-size:13px;color:var(--gray);margin-top:4px">Try adjusting the filters or search term</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $tickets->links() }}</div>
    </div>
</x-layout>
