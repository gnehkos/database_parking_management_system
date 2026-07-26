<x-layout title="{{ $staff->full_name }}">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('staff.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div class="page-title" style="font-size:22px">{{ $staff->full_name }}</div>
        <a href="{{ route('staff.edit',$staff->staff_id) }}" class="ios-btn btn-ghost btn-sm-ios ms-auto"><i class="bi bi-pencil-fill me-1"></i>Edit</a>
    </div>
    <div class="text-center mb-4">
        <div style="width:80px;height:80px;border-radius:50%;margin:0 auto 12px;overflow:hidden;background:linear-gradient(135deg,var(--blue),#5856d6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:26px;font-weight:700">
            @if($staff->profile_image)<img src="{{ Storage::url($staff->profile_image) }}" style="width:100%;height:100%;object-fit:cover">@else{{ strtoupper(substr($staff->full_name,0,2)) }}@endif
        </div>
        <div style="font-size:20px;font-weight:700">{{ $staff->full_name }}</div>
        <span class="pill {{ $staff->role==='admin'?'pill-orange':'pill-green' }}">{{ $staff->role }}</span>
    </div>
    <div class="grouped" style="max-width:500px;margin:0 auto">
        <div class="grouped-row"><span class="row-label">Username</span><span class="row-val">{{ '@'.$staff->username }}</span></div>
        <div class="grouped-row"><span class="row-label">Gender</span><span class="row-val">{{ ucfirst($staff->gender??'N/A') }}</span></div>
        <div class="grouped-row"><span class="row-label">Phone</span><span class="row-val">{{ $staff->phone_number }}</span></div>
        <div class="grouped-row"><span class="row-label">Date of Birth</span><span class="row-val">{{ $staff->date_of_birth }}</span></div>
        <div class="grouped-row"><span class="row-label">Staff ID</span><span class="row-val">S{{ str_pad($staff->staff_id,3,'0',STR_PAD_LEFT) }}</span></div>
        <div class="grouped-row"><span class="row-label">Joined</span><span class="row-val">{{ \Carbon\Carbon::parse($staff->created_at)->format('Y-m-d') }}</span></div>
    </div>
</x-layout>
