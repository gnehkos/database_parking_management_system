<x-layout title="Vehicles">
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <div class="page-title">Vehicles</div>
            <div class="page-sub">{{ $totalCount }} registered vehicles</div>
        </div>
        <a href="{{ route('vehicles.create') }}" class="ios-btn btn-primary-ios"><i class="bi bi-plus me-1"></i> Add Vehicle</a>
    </div>

    <div class="card-ios card-ios-p">
        <div class="d-flex gap-3 mb-4 flex-wrap align-items-center">
            <form method="GET" style="max-width:300px;flex:1">
                <input type="text" name="search" class="ios-input" placeholder="Search plate number..." value="{{ request('search') }}">
            </form>
            <div class="filter-pills">
                @foreach (['all','car','motorcycle','bike','tricycle'] as $t)
                    <a href="{{ route('vehicles.index', array_merge(request()->query(), ['type'=>$t])) }}" class="filter-pill {{ request('type','all')===$t?'on':'' }}">{{ ucfirst($t) }}</a>
                @endforeach
            </div>
        </div>

        <table class="ios-table">
            <thead><tr><th>Plate / ID</th><th>Type</th><th>Registered</th><th style="width:60px"></th></tr></thead>
            <tbody>
                @forelse ($vehicles as $vehicle)
                    <tr>
                        <td>
                            <a href="{{ route('vehicles.show', $vehicle->vehicle_id) }}" style="color:var(--blue);font-weight:600">
                                {{ $vehicle->plate_number ?? 'No plate' }}
                            </a>
                            @if(str_starts_with($vehicle->plate_number ?? '', 'BIKE-'))
                                <span class="pill pill-orange ms-1" style="font-size:10px">Auto-ID</span>
                            @endif
                        </td>
                        <td><x-type-badge :type="$vehicle->vehicle_type" /></td>
                        <td style="color:var(--gray)">{{ \Carbon\Carbon::parse($vehicle->registered_at)->format('Y-m-d') }}</td>
                        <td>
                            <button class="ios-btn btn-danger-ios btn-sm-ios" data-bs-toggle="modal" data-bs-target="#confirmModal"
                                data-title="Remove Vehicle"
                                data-message="Remove {{ $vehicle->plate_number ?? 'this vehicle' }}? This cannot be undone."
                                data-form-id="del-v-{{ $vehicle->vehicle_id }}"
                                data-action="Remove" data-danger="1">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <form id="del-v-{{ $vehicle->vehicle_id }}" method="POST" action="{{ route('vehicles.destroy', $vehicle->vehicle_id) }}" style="display:none">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-5">
                                <div style="width:52px;height:52px;border-radius:50%;background:var(--gray6);display:flex;align-items:center;justify-content:center;margin:0 auto 12px">
                                    <i class="bi bi-car-front" style="font-size:22px;color:var(--gray2)"></i>
                                </div>
                                <div style="font-size:15px;font-weight:600;color:var(--label2)">No vehicles found</div>
                                <div style="font-size:13px;color:var(--gray);margin-top:4px">Try a different search or add a vehicle</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $vehicles->links() }}</div>
    </div>
</x-layout>
