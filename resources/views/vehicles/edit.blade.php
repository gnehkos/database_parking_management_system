<x-layout title="Edit Vehicle">
    <div class="page-header"><div class="page-title" style="font-size:22px">Edit Vehicle</div></div>
    <div class="card-ios card-ios-p" style="max-width:500px">
        <form method="POST" action="{{ route('vehicles.update', $vehicle->vehicle_id) }}">@csrf @method('PATCH')
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Plate Number</label><input type="text" name="plate_number" class="ios-input" value="{{ old('plate_number',$vehicle->plate_number) }}"></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.5px">Vehicle Type</label><select name="vehicle_type" class="ios-input">@foreach(['car','motorcycle','bike','tricycle'] as $t)<option value="{{ $t }}" {{ $vehicle->vehicle_type===$t?'selected':'' }}>{{ ucfirst($t) }}</option>@endforeach</select></div>
            @if($errors->any())<div class="alert-ios alert-danger-ios">{{ $errors->first() }}</div>@endif
            <div class="d-flex gap-3 mt-4">
                <a href="{{ route('vehicles.show',$vehicle->vehicle_id) }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a>
                <button type="submit" class="ios-btn btn-primary-ios flex-fill">Save Changes</button>
            </div>
        </form>
    </div>
</x-layout>
