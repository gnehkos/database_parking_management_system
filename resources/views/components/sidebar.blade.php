@php
    $isAdmin = auth()->user()->isAdmin();
    $r = request()->route()?->getName() ?? '';
@endphp

<div class="sidebar">
    <div class="brand">
        <div class="brand-logo"><i class="bi bi-car-front-fill"></i></div>
        <div class="brand-text">
            <div class="brand-name">Parkin'</div>
            <div class="brand-sub">Pro</div>
        </div>
    </div>

    <nav>
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ $r === 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Dashboard
        </a>
        <a href="{{ route('vehicles.index') }}" class="nav-link {{ str_starts_with($r, 'vehicles.') ? 'active' : '' }}">
            <i class="bi bi-car-front-fill"></i> Vehicles
        </a>
        <a href="{{ route('checkin.index') }}" class="nav-link {{ str_starts_with($r, 'checkin.') ? 'active' : '' }}">
            <i class="bi bi-arrow-down-right-circle-fill"></i> Check-In
        </a>
        <a href="{{ route('checkout.index') }}" class="nav-link {{ str_starts_with($r, 'checkout.') ? 'active' : '' }}">
            <i class="bi bi-arrow-up-right-circle-fill"></i> Check-Out
        </a>

        <div class="nav-section">Parking</div>
        <a href="{{ route('slots.index') }}" class="nav-link {{ str_starts_with($r, 'slots.') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Slot Map
        </a>
        <a href="{{ route('history.index') }}" class="nav-link {{ str_starts_with($r, 'history.') ? 'active' : '' }}">
            <i class="bi bi-clock-fill"></i> History
        </a>
        <a href="{{ route('fees.index') }}" class="nav-link {{ str_starts_with($r, 'fees.') ? 'active' : '' }}">
            <i class="bi bi-tag-fill"></i> Fee Rates
        </a>

        @if ($isAdmin)
            <div class="nav-section">Admin</div>
            <a href="{{ route('reports.index') }}" class="nav-link {{ str_starts_with($r, 'reports.') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-fill"></i> Reports
                <i class="bi bi-patch-check-fill ms-auto" style="font-size:14px;color:var(--blue);opacity:0.85"></i>
            </a>
            <a href="{{ route('staff.index') }}" class="nav-link {{ str_starts_with($r, 'staff.') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Staff
                <i class="bi bi-patch-check-fill ms-auto" style="font-size:14px;color:var(--blue);opacity:0.85"></i>
            </a>
        @endif

        <div class="nav-section">Account</div>
        <a href="{{ route('settings.index') }}" class="nav-link {{ str_starts_with($r, 'settings.') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i> Settings
        </a>
    </nav>
</div>
