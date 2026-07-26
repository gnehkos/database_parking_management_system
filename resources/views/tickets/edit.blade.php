<x-layout title="Edit Ticket">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('history.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div class="page-title" style="font-size:22px">Edit Ticket</div>
        <span class="pill pill-gray ms-1">{{ $ticket->ticket_id }}</span>
    </div>

    <div class="card-ios card-ios-p" style="max-width:580px">
        <form method="POST" action="{{ route('tickets.update', $ticket->ticket_id) }}">
            @csrf @method('PATCH')

            <div class="section-hdr">Vehicle</div>
            <div class="mb-4">
                <label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Assigned Vehicle</label>
                <select name="vehicle_id" class="ios-input">
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->vehicle_id }}" {{ $ticket->vehicle_id == $v->vehicle_id ? 'selected' : '' }}>
                            {{ $v->plate_number ?? 'No plate' }} — {{ ucfirst($v->vehicle_type) }}
                        </option>
                    @endforeach
                </select>
                <div style="font-size:12px;color:var(--orange);margin-top:6px"><i class="bi bi-exclamation-triangle-fill me-1"></i>Changing the vehicle does not change the fee rate or slot zone.</div>
            </div>

            <div class="section-hdr">Entry Time</div>
            <div class="mb-4">
                <label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Entry Date & Time</label>
                <input type="datetime-local" name="entry_time" class="ios-input"
                    value="{{ \Carbon\Carbon::parse($ticket->entry_time)->format('Y-m-d\TH:i') }}" required>
                <div style="font-size:12px;color:var(--gray);margin-top:6px">Current: {{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, Y g:i A') }}</div>
            </div>

            <div class="section-hdr">Current Info</div>
            <div class="grouped mb-4" style="border:none;background:var(--gray6);border-radius:12px">
                <div class="grouped-row"><span class="row-label">Slot</span><span class="row-val">{{ $ticket->slot->slot_number ?? 'N/A' }}</span></div>
                <div class="grouped-row"><span class="row-label">Status</span><span class="pill {{ $ticket->status==='active'?'pill-green':'pill-blue' }}">{{ ucfirst($ticket->status) }}</span></div>
                <div class="grouped-row"><span class="row-label">Fee Rate</span><span class="row-val">{{ ucfirst($ticket->feeRate->vehicle_type) }}</span></div>
            </div>

            @if ($errors->any())
                <div class="alert-ios alert-danger-ios mb-3">{{ $errors->first() }}</div>
            @endif

            <div class="d-flex gap-3">
                <a href="{{ route('history.index') }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a>
                <button type="submit" class="ios-btn btn-primary-ios flex-fill">Save Changes</button>
            </div>
        </form>

        <div style="border-top:1px solid var(--gray5);margin-top:24px;padding-top:20px">
            <div style="font-size:13px;font-weight:600;color:var(--red);margin-bottom:12px">Danger Zone</div>
            <div class="d-flex gap-2">
                @if($ticket->status === 'completed' && $ticket->payment)
                    <button class="ios-btn btn-ghost btn-sm-ios" data-bs-toggle="modal" data-bs-target="#confirmModal"
                        data-title="Void Payment"
                        data-message="Void payment for ticket {{ $ticket->ticket_id }}? This reopens the ticket as active and marks the payment as voided."
                        data-form-id="void-pay-{{ $ticket->ticket_id }}"
                        data-action="Void Payment">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Void Payment
                    </button>
                    <form id="void-pay-{{ $ticket->ticket_id }}" method="POST" action="{{ route('tickets.voidPayment', $ticket->ticket_id) }}" style="display:none">@csrf</form>
                @endif
                <button class="ios-btn btn-danger-ios btn-sm-ios" data-bs-toggle="modal" data-bs-target="#confirmModal"
                    data-title="Delete Ticket"
                    data-message="Permanently delete ticket {{ $ticket->ticket_id }}? This also deletes the payment and frees the slot."
                    data-form-id="del-ticket-{{ $ticket->ticket_id }}"
                    data-action="Delete" data-danger="1">
                    <i class="bi bi-trash-fill me-1"></i>Delete Ticket
                </button>
                <form id="del-ticket-{{ $ticket->ticket_id }}" method="POST" action="{{ route('tickets.destroy', $ticket->ticket_id) }}" style="display:none">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-layout>
