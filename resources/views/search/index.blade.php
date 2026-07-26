<x-layout title="Search Vehicle">
    <div class="mb-4">
        <div class="page-title">Search Vehicle</div>
        <div class="page-subtitle">Find a vehicle by plate number or ticket ID</div>
    </div>

    <div class="ios-card mb-4" style="padding:20px">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="ios-input" style="font-size:18px" placeholder="2AB-1234" value="{{ request('q') }}" autofocus>
            <button class="ios-btn ios-btn-primary px-4">Search</button>
        </form>
    </div>

    @if (!request('q'))
        <div class="row g-3">
            <div class="col-md-6">
                <div class="ios-card" style="padding:20px">
                    <div class="ios-section-header">Search Tips</div>
                    <div style="font-size:14px;color:var(--ios-label2);line-height:2">
                        Enter full or partial plate: <code style="background:var(--ios-gray6);padding:2px 8px;border-radius:6px">2AB-1234</code><br>
                        By ticket ID: <code style="background:var(--ios-gray6);padding:2px 8px;border-radius:6px">T12345</code><br>
                        Partial works: <code style="background:var(--ios-gray6);padding:2px 8px;border-radius:6px">2AB</code>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ios-card" style="padding:20px">
                    <div class="ios-section-header">Quick Access</div>
                    <a href="{{ route('checkin.index') }}" class="d-flex align-items-center gap-3 py-3 border-bottom text-ios-blue" style="font-weight:600;font-size:15px;border-color:var(--ios-gray5)!important"><i class="bi bi-arrow-down-right-circle-fill"></i> New Check-In</a>
                    <a href="{{ route('checkout.index') }}" class="d-flex align-items-center gap-3 py-3 text-ios-blue" style="font-weight:600;font-size:15px"><i class="bi bi-arrow-up-right-circle-fill"></i> Check-Out Vehicle</a>
                </div>
            </div>
        </div>
    @else
        <div style="font-size:13px;color:var(--ios-gray);margin-bottom:12px">{{ $results->count() }} result(s) found</div>
        @foreach ($results as $vehicle)
            <a href="{{ route('vehicles.show', $vehicle->vehicle_id) }}" class="ios-card d-flex align-items-center gap-3 mb-2" style="padding:16px 20px">
                <div style="width:40px;height:40px;border-radius:12px;background:var(--ios-gray6);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-car-front-fill text-ios-blue"></i>
                </div>
                <div>
                    <span style="font-size:16px;font-weight:700;color:var(--ios-label)">{{ $vehicle->plate_number ?? 'No plate' }}</span>
                    <x-type-badge :type="$vehicle->vehicle_type" />
                    <div style="font-size:12px;color:var(--ios-gray)">Last visit: {{ \Carbon\Carbon::parse($vehicle->updated_at)->format('M d, g:i A') }}</div>
                </div>
            </a>
        @endforeach
        @if ($results->isEmpty())
            <div class="text-center py-5" style="color:var(--ios-gray)">No vehicles found for "{{ request('q') }}"</div>
        @endif
    @endif
</x-layout>
