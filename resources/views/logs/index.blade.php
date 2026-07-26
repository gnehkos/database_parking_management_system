<x-layout title="Entry / Exit Log">
    <div class="mb-4">
        <div class="page-title">Entry / Exit Log</div>
        <div class="page-subtitle">{{ $totalCount }} records in range</div>
    </div>

    <div class="ios-card" style="padding:20px">
        <div class="d-flex gap-2 flex-wrap mb-3 align-items-center">
            <span style="font-size:12px;font-weight:600;color:var(--ios-gray)">PERIOD</span>
            <div class="ios-filter-pills">
                @foreach (['today' => 'Today', '7days' => '7 Days', 'month' => 'Month', '3months' => '3 Months', '6months' => '6 Months', 'year' => 'Year'] as $key => $label)
                    <a href="{{ route('logs.index', array_merge(request()->query(), ['period' => $key])) }}" class="{{ ($period ?? 'today') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
            <span class="ms-auto" style="font-size:13px;color:var(--ios-gray)">{{ $dateRange }}</span>
        </div>
        <div class="d-flex gap-2 flex-wrap mb-4">
            <div class="ios-segmented">
                @foreach (['all' => 'All', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
                    <a href="{{ route('logs.index', array_merge(request()->query(), ['status' => $key])) }}" class="{{ request('status', 'all') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
            <div class="ios-filter-pills ms-3">
                @foreach (['all' => 'All', 'car' => 'Car', 'motorcycle' => 'Motorcycle', 'bike' => 'Bike', 'tricycle' => 'Tricycle'] as $key => $label)
                    <a href="{{ route('logs.index', array_merge(request()->query(), ['type' => $key])) }}" class="{{ request('type', 'all') === $key ? 'active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <table class="ios-table">
            <thead><tr><th>Ticket</th><th>Plate</th><th>Type</th><th>Slot</th><th>Entry</th><th>Exit</th><th>Duration</th><th>Fee</th><th>Status</th><th>Payment</th></tr></thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    @php $duration = 'Active'; if ($ticket->exit_time) { $diff = \Carbon\Carbon::parse($ticket->entry_time)->diff(\Carbon\Carbon::parse($ticket->exit_time)); $duration = $diff->h . 'h ' . $diff->i . 'm'; } @endphp
                    <tr>
                        <td><a href="{{ route('logs.show', $ticket->ticket_id) }}" class="text-ios-blue" style="font-weight:600">{{ $ticket->ticket_id }}</a></td>
                        <td style="font-weight:600">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</td>
                        <td><x-type-badge :type="$ticket->vehicle->vehicle_type" /></td>
                        <td>{{ $ticket->slot->slot_number ?? 'N/A' }}</td>
                        <td style="color:var(--ios-gray)">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }}</td>
                        <td style="color:var(--ios-gray)">{{ $ticket->exit_time ? \Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A') : '-' }}</td>
                        <td>{{ $duration }}</td>
                        <td style="font-weight:600">{{ $ticket->payment ? '$' . number_format($ticket->payment->total_fee, 2) : '-' }}</td>
                        <td><span class="ios-pill" style="background:{{ $ticket->status === 'active' ? 'rgba(52,199,89,0.12)' : ($ticket->status === 'completed' ? 'rgba(0,122,255,0.12)' : 'var(--ios-gray6)') }};color:{{ $ticket->status === 'active' ? 'var(--ios-green)' : ($ticket->status === 'completed' ? 'var(--ios-blue)' : 'var(--ios-gray)') }}">{{ ucfirst($ticket->status) }}</span></td>
                        <td style="color:var(--ios-gray)">{{ $ticket->payment ? ucfirst($ticket->payment->payment_method) : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center py-5" style="color:var(--ios-gray)">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $tickets->links() }}</div>
    </div>
</x-layout>
