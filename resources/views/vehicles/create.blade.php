<x-layout title="Add Vehicle">
    <div class="page-header"><div class="page-title">Add Vehicle</div><div class="page-sub">Register a new vehicle plate</div></div>
    <div class="card-ios card-ios-p" style="max-width:620px">
        <form method="POST" action="{{ route('vehicles.store') }}" id="vehicleForm">@csrf
            <div class="section-hdr">Vehicle Type</div>
            <div class="d-flex gap-2 mb-4">
                @foreach (['car','motorcycle','bike','tricycle'] as $t)
                    <label style="flex:1;cursor:pointer">
                        <input type="radio" name="vehicle_type" value="{{ $t }}" class="btn-check vtype-radio" required {{ old('vehicle_type')===$t?'checked':'' }}>
                        <div class="type-card text-center p-3" style="border:2px solid var(--gray5);border-radius:14px;transition:all 0.15s">
                            <div style="font-size:14px;font-weight:600">{{ ucfirst($t) }}</div>
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="section-hdr">Plate Number</div>
            <div class="d-flex gap-3 mb-3">
                <label style="font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px"><input type="radio" name="plate_type" value="structured" checked onchange="togglePlate()"> Structured</label>
                <label style="font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px"><input type="radio" name="plate_type" value="custom" onchange="togglePlate()"> Custom</label>
            </div>
            <div id="sF">
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" name="plate_prefix" id="platePrefix" class="ios-input" style="max-width:55px;text-align:center;background:var(--gray6);font-weight:700" placeholder="2" maxlength="1" readonly>
                    <input type="text" id="plateLetters" name="plate_letters" class="ios-input" style="max-width:80px;text-align:center" placeholder="AB" maxlength="2" autocomplete="off">
                    <span style="font-weight:700;color:var(--gray2)">-</span>
                    <input type="text" id="plateDigits" name="plate_digits" class="ios-input" style="max-width:110px;text-align:center" placeholder="1234" maxlength="4" autocomplete="off">
                </div>
                <div style="font-size:12px;color:var(--gray);margin-top:6px">e.g. 2AB-1234</div>
            </div>
            <div id="cF" style="display:none"><input type="text" name="plate_number" class="ios-input" placeholder="CUSTOM PLATE"></div>
            @if ($errors->any())<div class="alert-ios alert-danger-ios mt-3">{{ $errors->first() }}</div>@endif
            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('vehicles.index') }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a>
                <button type="button" class="ios-btn btn-primary-ios flex-fill" onclick="submitVehicle()">Register Vehicle</button>
            </div>
        </form>
    </div>
    <x-slot:scripts>
        <script>
            const prefixes = {car:'2',motorcycle:'1',tricycle:'1',bike:''};

            document.querySelectorAll('.vtype-radio').forEach(r => {
                r.addEventListener('change', function() {
                    document.querySelectorAll('.type-card').forEach(c => c.style.borderColor='var(--gray5)');
                    this.nextElementSibling.style.borderColor='var(--blue)';
                    document.getElementById('platePrefix').value = prefixes[this.value]||'';
                    if(this.value==='bike'){
                        document.getElementById('sF').style.display='none';
                        document.getElementById('cF').style.display='none';
                    } else {
                        togglePlate();
                    }
                });
            });

            document.getElementById('plateLetters').addEventListener('input', function() {
                this.value = this.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
            });

            document.getElementById('plateDigits').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            function togglePlate(){
                const pt=document.querySelector('input[name=plate_type]:checked').value;
                document.getElementById('sF').style.display=pt==='structured'?'block':'none';
                document.getElementById('cF').style.display=pt==='custom'?'block':'none';
            }

            function submitVehicle() {
                const vt = document.querySelector('input[name=vehicle_type]:checked');
                if (!vt) { alert('Please select a vehicle type.'); return; }
                const pt = document.querySelector('input[name=plate_type]:checked').value;
                if (vt.value !== 'bike' && pt === 'structured') {
                    const l = document.getElementById('plateLetters').value;
                    const d = document.getElementById('plateDigits').value;
                    if (!/^[A-Z]{1,2}$/.test(l)) { alert('Letters must be A-Z only (1-2 characters).'); return; }
                    if (!/^\d{4}$/.test(d)) { alert('Digits must be exactly 4 numbers.'); return; }
                }
                document.getElementById('vehicleForm').submit();
            }
        </script>
    </x-slot:scripts>
</x-layout>