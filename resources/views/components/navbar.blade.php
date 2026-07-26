<div class="top-navbar d-flex align-items-center justify-content-between">
    <div></div>
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('notifications.index') }}" class="text-ios-gray" style="font-size:20px"><i class="bi bi-bell-fill"></i></a>
        <div class="dropdown">
            <button class="btn d-flex align-items-center gap-2 p-1 pe-3" style="background:var(--ios-gray6);border-radius:100px;border:none" data-bs-toggle="dropdown">
                <div class="ios-avatar bg-ios-blue" style="width:32px;height:32px;font-size:12px">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                </div>
                <span style="font-size:14px;font-weight:600">{{ auth()->user()->full_name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius:14px;border:1px solid var(--ios-border);box-shadow:0 10px 40px rgba(0,0,0,0.12);padding:6px">
                <li><a class="dropdown-item" href="{{ route('settings.profile') }}" style="border-radius:10px;font-size:14px;padding:8px 14px"><i class="bi bi-person-fill me-2 text-ios-gray"></i>My Profile</a></li>
                <li><hr class="dropdown-divider" style="margin:4px 0"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-ios-red" style="border-radius:10px;font-size:14px;padding:8px 14px"><i class="bi bi-rectangle-portrait.and" me-2></i>Sign Out</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
