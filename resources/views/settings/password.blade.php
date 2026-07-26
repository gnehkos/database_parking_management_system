<x-layout title="Change Password">
    <div class="page-header"><div class="page-title" style="font-size:22px">Change Password</div></div>
    <div class="card-ios card-ios-p" style="max-width:440px">
        <form method="POST" action="{{ route('settings.updatePassword') }}">@csrf
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Current Password</label><input type="password" name="current_password" class="ios-input" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">New Password</label><input type="password" name="new_password" class="ios-input" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Confirm New Password</label><input type="password" name="new_password_confirmation" class="ios-input" required></div>
            @if($errors->any())<div class="alert-ios alert-danger-ios">{{ $errors->first() }}</div>@endif
            <div class="d-flex gap-3 mt-4"><a href="{{ route('settings.index') }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a><button type="submit" class="ios-btn btn-primary-ios flex-fill">Update Password</button></div>
        </form>
    </div>
</x-layout>
