<x-layout title="Staff Management">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><div class="page-title">Staff Management</div><div class="page-sub">{{ $activeCount }} active staff members</div></div>
        <a href="{{ route('staff.create') }}" class="ios-btn btn-primary-ios"><i class="bi bi-plus me-1"></i> Add Staff</a>
    </div>
    <div class="card-ios card-ios-p">
        <form method="GET" class="mb-4"><input type="text" name="search" class="ios-input" placeholder="Search by name or username..." value="{{ request('search') }}"></form>
        @foreach ($staffMembers as $member)
            <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar" style="width:44px;height:44px;font-size:14px;border-radius:12px;flex-shrink:0">
                        @if($member->profile_image)
                            <img src="{{ Storage::url($member->profile_image) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:12px">
                        @else
                            {{ strtoupper(substr($member->full_name,0,2)) }}
                        @endif
                    </div>
                    <div>
                        <div>
                            <span style="font-size:15px;font-weight:600">{{ $member->full_name }}</span>
                            <span class="pill {{ $member->role==='admin'?'pill-orange':'pill-green' }} ms-1">{{ $member->role }}</span>
                            @if($member->status!=='active')<span class="pill pill-gray ms-1">{{ $member->status }}</span>@endif
                        </div>
                        <div style="font-size:12px;color:var(--gray)">{{ '@'.$member->username }} · {{ $member->phone_number }} · Joined {{ \Carbon\Carbon::parse($member->created_at)->format('Y-m-d') }}</div>
                    </div>
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('staff.show',$member->staff_id) }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-eye-fill"></i></a>
                    <a href="{{ route('staff.edit',$member->staff_id) }}" class="ios-btn btn-sm-ios" style="background:rgba(0,122,255,0.1);color:var(--blue)"><i class="bi bi-pencil-fill"></i></a>
                    @if($member->staff_id!==auth()->id())
                        <button class="ios-btn btn-sm-ios {{ $member->status==='active'?'btn-danger-ios':'' }}" style="{{ $member->status!=='active'?'background:rgba(52,199,89,0.1);color:var(--green)':'' }}"
                            data-bs-toggle="modal" data-bs-target="#confirmModal"
                            data-title="{{ $member->status==='active'?'Deactivate':'Activate' }} Staff"
                            data-message="{{ $member->status==='active'?'Deactivating':'Activating' }} {{ $member->full_name }} will {{ $member->status==='active'?'prevent them from logging in':'restore their access' }}."
                            data-form-id="toggle-{{ $member->staff_id }}"
                            data-action="{{ $member->status==='active'?'Deactivate':'Activate' }}"
                            {{ $member->status==='active'?'data-danger=1':'' }}>
                            <i class="bi bi-{{ $member->status==='active'?'person-x-fill':'person-check-fill' }}"></i>
                        </button>
                        <form id="toggle-{{ $member->staff_id }}" method="POST" action="{{ route('staff.toggleStatus',$member->staff_id) }}" style="display:none">@csrf @method('PATCH')</form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
