<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket {{ $ticket->ticket_id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #fff; padding: 32px; max-width: 340px; }
        .brand { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 16px; }
        .brand-name { font-size: 22px; font-weight: 800; }
        .brand-sub { font-size: 11px; color: #888; margin-top: 2px; }
        .plate { font-size: 32px; font-weight: 800; text-align: center; margin: 16px 0 4px; letter-spacing: 1px; }
        .type { text-align: center; font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
        .divider { border: none; border-top: 1px dashed #ccc; margin: 14px 0; }
        .row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .row .label { color: #888; }
        .row .val { font-weight: 600; }
        .barcode { text-align: center; margin-top: 20px; border-top: 1px dashed #ccc; padding-top: 16px; }
        .barcode-text { font-family: monospace; font-size: 16px; font-weight: 700; letter-spacing: 3px; }
        .barcode-hint { font-size: 10px; color: #aaa; margin-top: 4px; }
        .footer { text-align: center; margin-top: 20px; font-size: 11px; color: #aaa; border-top: 1px dashed #ccc; padding-top: 14px; }
    </style>
</head>
<body onload="window.print()">
    <div class="brand">
        <div class="brand-name">Parkin'</div>
        <div class="brand-sub">Parking Receipt</div>
    </div>
    <div class="plate">{{ $ticket->vehicle->plate_number ?? 'NO PLATE' }}</div>
    <div class="type">{{ ucfirst($ticket->vehicle->vehicle_type) }}</div>
    <hr class="divider">
    <div class="row"><span class="label">Ticket ID</span><span class="val">{{ $ticket->ticket_id }}</span></div>
    <div class="row"><span class="label">Slot</span><span class="val">{{ $ticket->slot->slot_number }}</span></div>
    <div class="row"><span class="label">Date</span><span class="val">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, Y') }}</span></div>
    <div class="row"><span class="label">Entry Time</span><span class="val">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('g:i A') }}</span></div>
    <div class="row"><span class="label">Short Stay (&lt;5h)</span><span class="val">${{ number_format($ticket->feeRate->short_stay_fee, 2) }}</span></div>
    <div class="row"><span class="label">Long Stay (5h+)</span><span class="val">${{ number_format($ticket->feeRate->long_stay_fee, 2) }}</span></div>
    <div class="row"><span class="label">Staff</span><span class="val">{{ $ticket->staff->full_name ?? 'N/A' }}</span></div>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
        <script>
            JsBarcode("#barcode", "{{ $ticket->barcode }}", {
                format: "CODE128",
                width: 2,
                height: 60,
                displayValue: false,
                background: "transparent",
                lineColor: "#000"
            });
        </script>
    <div class="footer">Thank you for parking with us.</div>
</body>
</html>