<x-layout title="Check-In">
    <div class="page-header"><div class="page-title">Vehicle Check-In</div><div class="page-sub">Register a new vehicle entry</div></div>
    <div class="card-ios card-ios-p" style="max-width:640px">
        <form method="POST" action="{{ route('checkin.slots') }}" id="checkinForm">
            @csrf
            <div class="section-hdr">Vehicle Type</div>
            <div class="d-flex gap-2 mb-4">
                @foreach (['car','motorcycle','bike','tricycle'] as $t)
                    <label style="flex:1;cursor:pointer">
                        <input type="radio" name="vehicle_type" value="{{ $t }}" class="btn-check vtype" id="vt_{{ $t }}" required>
                        <div class="text-center p-3 type-opt" style="border:2px solid var(--gray5);border-radius:14px;transition:all 0.15s">
                            <div style="font-size:14px;font-weight:600">{{ ucfirst($t) }}</div>
                            <div style="font-size:12px;color:var(--green);font-weight:500">{{ $freeByType[$t]??0 }} free</div>
                        </div>
                    </label>
                @endforeach
            </div>

            <div id="plateSection">
                <div class="section-hdr">Plate Number</div>
                <div class="d-flex gap-3 mb-3">
                    <label style="font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px"><input type="radio" name="plate_type" value="structured" id="ptStructured" checked onchange="togglePt()"> Structured</label>
                    <label style="font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px"><input type="radio" name="plate_type" value="custom" id="ptCustom" onchange="togglePt()"> Custom</label>
                </div>
                <div id="sF">
                    <div class="d-flex gap-2 align-items-center">
                        <input type="text" id="prefix" class="ios-input" style="max-width:55px;text-align:center;background:var(--gray6);font-weight:700" placeholder="2" maxlength="1" readonly>
                        <input type="text" name="plate_letters" class="ios-input" style="max-width:80px;text-align:center" placeholder="AB" maxlength="2">
                        <span style="font-weight:700;color:var(--gray2)">-</span>
                        <input type="text" name="plate_digits" class="ios-input" style="max-width:110px;text-align:center" placeholder="1234" maxlength="4">
                    </div>
                    <div style="font-size:12px;color:var(--gray);margin-top:6px">First digit is auto-set by vehicle type</div>
                </div>
                <div id="cF" style="display:none"><input type="text" name="plate_number_custom" class="ios-input" placeholder="CUSTOM PLATE"></div>
            </div>

            <input type="hidden" name="plate_number" id="finalPlate">
            @if ($errors->any())<div class="alert-ios alert-danger-ios mt-3">{{ $errors->first() }}</div>@endif

            <button type="submit" class="ios-btn btn-primary-ios w-100 mt-4" style="font-size:15px" onclick="buildPlate()">Proceed to Slot Assignment <i class="bi bi-arrow-right ms-1"></i></button>
        </form>
    </div>
    <x-slot:scripts>
        <script>
            const prefixes = {car:'2',motorcycle:'1',tricycle:'1',bike:''};
            document.querySelectorAll('.vtype').forEach(r => {
                r.addEventListener('change', function() {
                    document.querySelectorAll('.type-opt').forEach(o => o.style.borderColor='var(--gray5)');
                    this.parentElement.querySelector('.type-opt').style.borderColor='var(--blue)';
                    if(this.value==='bike'){
                        document.getElementById('plateSection').style.display='none';
                    } else {
                        document.getElementById('plateSection').style.display='block';
                        document.getElementById('prefix').value = prefixes[this.value]||'';
                    }
                });
            });
            function togglePt(){
                const pt=document.querySelector('input[name=plate_type]:checked').value;
                document.getElementById('sF').style.display=pt==='structured'?'block':'none';
                document.getElementById('cF').style.display=pt==='custom'?'block':'none';
            }
            function buildPlate(){
                const vt=document.querySelector('input[name=vehicle_type]:checked');
                if(!vt) return;
                if(vt.value==='bike'){document.getElementById('finalPlate').value='';return;}
                const pt=document.querySelector('input[name=plate_type]:checked').value;
                if(pt==='structured'){
                    const p=document.getElementById('prefix').value;
                    const l=document.querySelector('[name=plate_letters]').value;
                    const d=document.querySelector('[name=plate_digits]').value;
                    document.getElementById('finalPlate').value=p+l+'-'+d;
                } else {
                    document.getElementById('finalPlate').value=document.querySelector('[name=plate_number_custom]').value;
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
