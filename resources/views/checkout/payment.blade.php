<x-layout title="Payment">
    <div style="max-width:520px;margin:0 auto">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('checkout.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
            <div class="page-title" style="font-size:22px">Payment</div>
        </div>

        <div class="card-ios card-ios-p mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div style="font-size:17px;font-weight:700">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</div>
                    <x-type-badge :type="$ticket->vehicle->vehicle_type" />
                    <div style="font-size:13px;color:var(--gray);margin-top:6px">Slot {{ $ticket->slot->slot_number }}</div>
                    @if(auth()->user()->isAdmin() && $ticket->staff)
                        <div style="font-size:12px;color:var(--gray2);margin-top:2px"><i class="bi bi-person-fill me-1"></i>Checked in by {{ $ticket->staff->full_name }}</div>
                    @endif
                </div>
                <div class="text-end">
                    <div id="feeDisplay" style="font-size:30px;font-weight:800;color:var(--red)">${{ number_format($fee,2) }}</div>
                    <div id="khrDisplay" style="font-size:12px;color:var(--gray)">~ {{ number_format($khrAmount) }} KHR</div>
                    <div id="stayType" style="font-size:11px;color:var(--gray2);margin-top:2px">{{ $hours < $ticket->feeRate->threshold_hours ? 'Short stay rate' : 'Long stay rate' }}</div>
                </div>
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--gray5);display:flex;align-items:center;justify-content:space-between">
                <div style="font-size:12px;color:var(--gray)">Duration</div>
                <div id="durationDisplay" style="font-size:15px;font-weight:700">{{ $durationText }}</div>
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

    <x-slot:scripts>
        <script>
            const entryTime = new Date('{{ $ticket->entry_time }}');
            const shortFee = {{ $ticket->feeRate->short_stay_fee }};
            const longFee  = {{ $ticket->feeRate->long_stay_fee }};
            const threshold = {{ $ticket->feeRate->threshold_hours }};
            const khrRate  = {{ \App\Models\FeeRate::KHR_RATE }};

            function updateTimer() {
                const now = new Date();
                const diffMs = now - entryTime;
                const diffHours = diffMs / (1000 * 60 * 60);
                const totalSecs = Math.floor(diffMs / 1000);
                const days = Math.floor(totalSecs / 86400);
                const h    = Math.floor((totalSecs % 86400) / 3600);
                const m    = Math.floor((totalSecs % 3600) / 60);
                const s    = totalSecs % 60;

                let durStr = '';
                if (days > 0) durStr += days + 'd ';
                if (h > 0)    durStr += h + 'h ';
                durStr += m + 'm ' + s + 's';
                document.getElementById('durationDisplay').textContent = durStr;

                const fee = diffHours < threshold ? shortFee : longFee;
                document.getElementById('feeDisplay').textContent = '$' + fee.toFixed(2);
                document.getElementById('khrDisplay').textContent = '~ ' + Math.round(fee * khrRate).toLocaleString() + ' KHR';
                document.getElementById('stayType').textContent = diffHours < threshold ? 'Short stay rate' : 'Long stay rate';
            }

            setInterval(updateTimer, 1000);
            updateTimer();
        </script>
    </x-slot:scripts>
</x-layout>
