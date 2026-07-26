<x-layout title="Payment">
    <div style="max-width:520px;margin:0 auto">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('checkout.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
            <div class="page-title" style="font-size:22px">Payment</div>
        </div>

        <div class="card-ios card-ios-p mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div style="font-size:17px;font-weight:700">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</div>
                    <x-type-badge :type="$ticket->vehicle->vehicle_type" />
                    <div style="font-size:13px;color:var(--gray);margin-top:6px">Slot {{ $ticket->slot->slot_number }} · Parked for {{ $durationText }}</div>
                    @if(auth()->user()->isAdmin() && $ticket->staff)
                        <div style="font-size:12px;color:var(--gray2);margin-top:2px"><i class="bi bi-person-fill me-1"></i>Checked in by {{ $ticket->staff->full_name }}</div>
                    @endif
                </div>
                <div class="text-end">
                    <div style="font-size:30px;font-weight:800;color:var(--red)">${{ number_format($fee,2) }}</div>
                    <div style="font-size:12px;color:var(--gray)">~ {{ number_format($khrAmount) }} KHR</div>
                    <div style="font-size:11px;color:var(--gray2);margin-top:2px">{{ $hours < $ticket->feeRate->threshold_hours ? 'Short stay rate' : 'Long stay rate' }}</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.process', $ticket->ticket_id) }}">
            @csrf
            <div class="section-hdr">Payment Method</div>
            @foreach ([['cash','Cash','Physical currency','bi-banknote'],['card','Card','Credit / debit','bi-credit-card-fill'],['qrScan','QR Scan','KHQR / ABA / WING','bi-qr-code']] as [$val,$lbl,$desc,$icon])
                <label class="card-ios card-ios-p d-flex align-items-center justify-content-between mb-2" style="cursor:pointer">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:12px;background:var(--gray6);display:flex;align-items:center;justify-content:center">
                            <i class="bi {{ $icon }}" style="font-size:19px;color:var(--blue)"></i>
                        </div>
                        <div><div style="font-size:15px;font-weight:600">{{ $lbl }}</div><div style="font-size:12px;color:var(--gray)">{{ $desc }}</div></div>
                    </div>
                    <input type="radio" name="payment_method" value="{{ $val }}" style="width:22px;height:22px;accent-color:var(--blue)" required>
                </label>
            @endforeach
            <button type="submit" class="ios-btn btn-primary-ios w-100 mt-3 py-3" style="font-size:16px;border-radius:16px">Confirm Payment</button>
        </form>
    </div>
</x-layout>
