<x-layout title="Payment Complete">
    <div class="text-center py-4" style="max-width:420px;margin:0 auto">
        <div style="width:64px;height:64px;border-radius:50%;background:rgba(52,199,89,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-check-lg" style="font-size:30px;color:var(--green)"></i>
        </div>
        <div class="page-title" style="font-size:22px">Payment Complete</div>
        <div style="color:var(--gray);font-size:14px;margin-bottom:24px">Vehicle has been checked out successfully.</div>

        <div class="grouped" style="text-align:left">
            <div class="grouped-row"><span class="row-label">Plate Number</span><span class="row-val">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</span></div>
            <div class="grouped-row"><span class="row-label">Ticket ID</span><span class="row-val">{{ $ticket->ticket_id }}</span></div>
            <div class="grouped-row"><span class="row-label">Exit Time</span><span class="row-val">{{ \Carbon\Carbon::parse($ticket->exit_time)->format('M d, g:i A') }}</span></div>
            <div class="grouped-row"><span class="row-label">Payment Method</span><span class="row-val">{{ ucfirst($ticket->payment->payment_method) }}</span></div>
            @if(auth()->user()->isAdmin())
                <div class="grouped-row"><span class="row-label">Processed by</span><span class="row-val">{{ $ticket->payment->staff->full_name ?? 'N/A' }}</span></div>
            @endif
            <div class="grouped-row"><span class="row-label">Amount Paid</span><span style="font-size:20px;font-weight:800;color:var(--green)">${{ number_format($ticket->payment->total_fee,2) }}</span></div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('checkout.index') }}" class="ios-btn btn-ghost flex-fill text-center">New Exit</a>
            <a href="{{ route('dashboard') }}" class="ios-btn btn-primary-ios flex-fill text-center">Dashboard</a>
        </div>
    </div>
</x-layout>
