<x-layout title="Check-In Successful">
    <div class="text-center py-4" style="max-width:420px;margin:0 auto">

        <div style="width:64px;height:64px;border-radius:50%;background:rgba(52,199,89,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
            <i class="bi bi-check-lg" style="font-size:30px;color:var(--green)"></i>
        </div>
        <div class="page-title" style="font-size:22px">Check-In Successful</div>
        <div style="color:var(--gray);font-size:14px;margin-bottom:24px">Vehicle has been parked and ticket issued.</div>

        <div class="card-ios overflow-hidden">

            {{-- Plate header with Wrong plate button --}}
            <div style="padding:20px;text-align:left;border-bottom:0.5px solid var(--gray5)">
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--gray);margin-bottom:8px">Parking Ticket</div>
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px">
                    <div>
                        <div style="font-size:22px;font-weight:800;color:var(--label)">{{ $ticket->vehicle->plate_number ?? 'No plate' }}</div>
                        <div style="margin-top:5px"><x-type-badge :type="$ticket->vehicle->vehicle_type" /></div>
                        @if(str_starts_with($ticket->vehicle->plate_number ?? '', 'BIKE-'))
                            <div style="font-size:11px;color:var(--gray);margin-top:4px">Auto-assigned Bike ID</div>
                        @endif
                    </div>
                    <button type="button" class="ios-btn btn-ghost btn-sm-ios" style="flex-shrink:0;margin-top:2px"
                            onclick="document.getElementById('cpForm').style.display=document.getElementById('cpForm').style.display==='none'?'block':'none'">
                        <i class="bi bi-pencil me-1"></i> Wrong plate?
                    </button>
                </div>

                @php
                    $vtype  = $ticket->vehicle->vehicle_type;
                    $ptype  = $ticket->vehicle->plate_type;
                    $prefix = in_array($vtype, ['motorcycle','tricycle']) ? '1' : '2';
                    $cur    = $ticket->vehicle->plate_number ?? '';
                    $dp     = strpos($cur, '-');
                    $cpL    = $dp !== false ? substr($cur, 1, $dp - 1) : '';
                    $cpD    = $dp !== false ? substr($cur, $dp + 1) : '';
                @endphp

                <div id="cpForm" style="display:none;background:var(--gray6);border-radius:10px;padding:12px;margin-top:14px">
                    <div style="font-size:12px;font-weight:600;color:var(--gray);margin-bottom:8px">Enter the correct plate number</div>
                    <form method="POST" action="{{ route('tickets.correctVehicle', $ticket->ticket_id) }}"
                          id="cpFormEl" style="display:flex;flex-direction:column;gap:10px">
                        @csrf
                        @if($ptype === 'structured' && $vtype !== 'bike')
                            <div style="display:flex;align-items:center;gap:6px">
                                <input type="text" class="ios-input" style="width:48px;text-align:center;font-weight:700"
                                       value="{{ $prefix }}" readonly>
                                <input type="text" id="cpL" class="ios-input"
                                       style="width:70px;text-align:center;text-transform:uppercase"
                                       maxlength="2" placeholder="AB" value="{{ $cpL }}">
                                <span style="font-weight:700;color:var(--gray)">-</span>
                                <input type="text" id="cpD" class="ios-input"
                                       style="width:90px;text-align:center"
                                       maxlength="4" placeholder="1234" value="{{ $cpD }}">
                                <input type="hidden" name="plate_number" id="cpP">
                            </div>
                            <div style="font-size:11px;color:var(--gray)">Format: {{ $prefix }}AB-1234 · 2 letters + 4 digits</div>
                            <div style="display:flex;gap:8px">
                                <button type="button" class="ios-btn btn-primary-ios"
                                        onclick="submitCP('{{ $prefix }}','cpL','cpD','cpP','cpFormEl')">Save</button>
                                <button type="button" class="ios-btn btn-ghost"
                                        onclick="document.getElementById('cpForm').style.display='none'">Cancel</button>
                            </div>
                        @else
                            <div style="display:flex;gap:8px">
                                <input type="text" name="plate_number" class="ios-input"
                                       value="{{ $cur }}" style="text-transform:uppercase;flex:1">
                                <button class="ios-btn btn-primary-ios">Save</button>
                                <button type="button" class="ios-btn btn-ghost"
                                        onclick="document.getElementById('cpForm').style.display='none'">Cancel</button>
                            </div>
                        @endif
                    </form>
                    <div style="font-size:11px;color:var(--gray);margin-top:8px">
                        <i class="bi bi-info-circle me-1"></i>
                        If this plate already exists, the ticket is reassigned automatically.
                    </div>
                </div>
            </div>

            {{-- Ticket details --}}
            <div class="card-ios-p">
                <div class="grouped" style="border:none;margin:0">
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-hash me-1"></i>Ticket ID</span><span class="row-val">{{ $ticket->ticket_id }}</span></div>
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-geo-alt-fill me-1"></i>Slot</span><span class="row-val">{{ $ticket->slot->slot_number }}</span></div>
                    <div class="grouped-row"><span class="row-label"><i class="bi bi-clock-fill me-1"></i>Entry Time</span><span class="row-val">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, Y g:i A') }}</span></div>
                    @if(auth()->user()->isAdmin())
                        <div class="grouped-row"><span class="row-label"><i class="bi bi-person-fill me-1"></i>Processed by</span><span class="row-val">{{ $ticket->staff->full_name ?? 'N/A' }}</span></div>
                    @endif
                </div>
                <div class="text-center mt-3 py-2" style="background:var(--gray6);border-radius:10px;font-family:monospace;font-weight:700;font-size:13px;letter-spacing:2px">
                    {{ $ticket->barcode }}
                </div>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('checkin.print', $ticket->ticket_id) }}" target="_blank" class="ios-btn btn-ghost flex-fill">
                <i class="bi bi-printer-fill me-1"></i> Print / Save PDF
            </a>
            <a href="{{ route('dashboard') }}" class="ios-btn btn-primary-ios flex-fill">
                <i class="bi bi-house-fill me-1"></i> Dashboard
            </a>
        </div>
        <a href="{{ route('checkin.index') }}" style="color:var(--blue);font-size:14px;font-weight:600;display:inline-block;margin-top:16px">
            Check in another vehicle
        </a>
    </div>

    <x-slot:scripts>
    <script>
    document.getElementById('cpL')?.addEventListener('input',function(){
        this.value=this.value.replace(/[^a-zA-Z]/g,'').toUpperCase().slice(0,2);
    });
    document.getElementById('cpD')?.addEventListener('input',function(){
        this.value=this.value.replace(/[^0-9]/g,'').slice(0,4);
    });
    function submitCP(prefix,lId,dId,pId,fId){
        const l=document.getElementById(lId).value;
        const d=document.getElementById(dId).value;
        if(l.length<2){alert('Letters must be exactly 2 characters.');return;}
        if(d.length!==4){alert('Digits must be exactly 4 numbers.');return;}
        document.getElementById(pId).value=prefix+l+'-'+d;
        document.getElementById(fId).submit();
    }
    </script>
    </x-slot:scripts>
</x-layout>