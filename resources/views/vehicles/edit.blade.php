<x-layout title="Edit Vehicle">
    <div class="page-header"><div class="page-title" style="font-size:22px">Edit Vehicle</div></div>

    <div class="card-ios card-ios-p" style="max-width:500px">

        @if($errors->any())
            <div class="alert-ios alert-danger-ios mb-3">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('vehicles.update', $vehicle->vehicle_id) }}"
              id="editVehicleForm">
            @csrf @method('PATCH')

            {{-- Vehicle type --}}
            <div class="mb-3">
                <label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">
                    Vehicle Type
                </label>
                <select name="vehicle_type" id="vehicleType" class="ios-input" onchange="onTypeChange()">
                    @foreach(['car','motorcycle','bike','tricycle'] as $t)
                        <option value="{{ $t }}" {{ $vehicle->vehicle_type===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Plate number --}}
            <div id="plateSection" class="mb-3">
                <label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">
                    Plate Number
                </label>

                @if($vehicle->plate_type === 'structured')
                    <div style="display:flex;align-items:center;gap:6px">
                        <input type="text" id="platePrefix" class="ios-input"
                               style="width:48px;text-align:center;font-weight:700"
                               readonly>
                        <input type="text" id="plateLetters" class="ios-input"
                               style="width:70px;text-align:center;text-transform:uppercase"
                               maxlength="2" placeholder="AB">
                        <span style="font-weight:700;color:var(--gray)">-</span>
                        <input type="text" id="plateDigits" class="ios-input"
                               style="width:90px;text-align:center"
                               maxlength="4" placeholder="1234">
                        <input type="hidden" name="plate_number" id="plateNumber">
                    </div>
                    <div style="font-size:11px;color:var(--gray);margin-top:6px" id="plateHint"></div>
                @else
                    <input type="text" name="plate_number" class="ios-input"
                           value="{{ old('plate_number', $vehicle->plate_number) }}"
                           placeholder="Custom plate number">
                    <div style="font-size:11px;color:var(--gray);margin-top:6px">Custom plate — any format allowed</div>
                @endif
            </div>

            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('vehicles.show', $vehicle->vehicle_id) }}"
                   class="ios-btn btn-ghost flex-fill text-center">Cancel</a>
                <button type="button" onclick="submitEdit()"
                        class="ios-btn btn-primary-ios flex-fill">Save Changes</button>
            </div>
        </form>
    </div>

@if($vehicle->plate_type === 'structured')
<script>
const vehicleType = '{{ $vehicle->vehicle_type }}';
const currentPlate = '{{ $vehicle->plate_number ?? '' }}';

const prefixMap = { car: '2', motorcycle: '1', tricycle: '1', bike: '' };

function getPrefix(type) {
    return prefixMap[type] || '2';
}

function onTypeChange() {
    const type   = document.getElementById('vehicleType').value;
    const prefix = getPrefix(type);
    document.getElementById('platePrefix').value = prefix;
    updateHint(type);
    if (type === 'bike') {
        document.getElementById('plateSection').style.display = 'none';
    } else {
        document.getElementById('plateSection').style.display = 'block';
    }
}

function updateHint(type) {
    const prefix = getPrefix(type);
    const hints = {
        car:        'Format: 2AB-1234  (prefix 2, 2 letters, dash, 4 digits)',
        motorcycle: 'Format: 1AB-1234  (prefix 1, 2 letters, dash, 4 digits)',
        tricycle:   'Format: 1AB-1234  (prefix 1, 2 letters, dash, 4 digits)',
    };
    const el = document.getElementById('plateHint');
    if (el) el.textContent = hints[type] || '';
}

// Init: parse current plate into prefix/letters/digits
function initPlate() {
    const type   = document.getElementById('vehicleType').value;
    const prefix = getPrefix(type);
    document.getElementById('platePrefix').value = prefix;
    updateHint(type);

    if (currentPlate && currentPlate.length > 1) {
        const withoutPrefix = currentPlate.slice(1); // remove first char (prefix)
        const parts = withoutPrefix.split('-');
        if (parts.length === 2) {
            document.getElementById('plateLetters').value = parts[0];
            document.getElementById('plateDigits').value  = parts[1];
        }
    }
}

// Enforce letters only
document.getElementById('plateLetters')?.addEventListener('input', function() {
    this.value = this.value.replace(/[^a-zA-Z]/g, '').toUpperCase().slice(0, 2);
});

// Enforce digits only
document.getElementById('plateDigits')?.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
});

function buildPlate() {
    const prefix  = document.getElementById('platePrefix').value;
    const letters = document.getElementById('plateLetters').value.toUpperCase();
    const digits  = document.getElementById('plateDigits').value;
    return prefix + letters + '-' + digits;
}

function submitEdit() {
    const type    = document.getElementById('vehicleType').value;
    const letters = document.getElementById('plateLetters')?.value || '';
    const digits  = document.getElementById('plateDigits')?.value || '';

    if (type === 'bike') {
        document.getElementById('plateNumber').value = '';
        document.getElementById('editVehicleForm').submit();
        return;
    }

    if (letters.length < 2) {
        alert('Letters must be exactly 2 characters (e.g. AB).');
        return;
    }
    if (digits.length !== 4) {
        alert('Digits must be exactly 4 numbers (e.g. 1234).');
        return;
    }

    document.getElementById('plateNumber').value = buildPlate();
    document.getElementById('editVehicleForm').submit();
}

initPlate();
</script>
@endif

</x-layout>