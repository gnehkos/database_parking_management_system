<x-layout title="Settings">
    <div class="page-header"><div class="page-title">Settings</div></div>
    <div style="max-width:500px">
        <div class="grouped mb-4">
            <a href="{{ route('settings.profile') }}" class="grouped-row" style="color:var(--label)">
                <div class="d-flex align-items-center gap-3"><div style="width:36px;height:36px;border-radius:10px;background:rgba(0,122,255,0.1);display:flex;align-items:center;justify-content:center"><i class="bi bi-person-fill" style="color:var(--blue)"></i></div><div><div style="font-weight:600">My Profile</div><div style="font-size:12px;color:var(--gray)">View and edit your profile</div></div></div>
                <i class="bi bi-chevron-right" style="color:var(--gray2)"></i>
            </a>
            <a href="{{ route('settings.password') }}" class="grouped-row" style="color:var(--label)">
                <div class="d-flex align-items-center gap-3"><div style="width:36px;height:36px;border-radius:10px;background:rgba(255,149,0,0.1);display:flex;align-items:center;justify-content:center"><i class="bi bi-lock-fill" style="color:var(--orange)"></i></div><div><div style="font-weight:600">Change Password</div><div style="font-size:12px;color:var(--gray)">Update your account password</div></div></div>
                <i class="bi bi-chevron-right" style="color:var(--gray2)"></i>
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="ios-btn w-100" style="background:rgba(255,59,48,0.1);color:var(--red)"><i class="bi bi-box-arrow-right me-1"></i>Sign Out</button>
        </form>
    </div>
</x-layout>
