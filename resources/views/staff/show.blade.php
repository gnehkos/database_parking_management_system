<x-layout title="{{ $staff->full_name }}">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('staff.index') }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-chevron-left"></i></a>
        <div class="page-title" style="font-size:22px">{{ $staff->full_name }}</div>
        <div class="d-flex gap-2 ms-auto">
            <button class="ios-btn btn-ghost btn-sm-ios" data-bs-toggle="modal" data-bs-target="#confirmModal"
                data-title="Reset Password"
                data-message="Reset {{ $staff->full_name }}'s password back to the default? They'll need to change it on next login."
                data-form-id="reset-pw-{{ $staff->staff_id }}"
                data-action="Reset Password">
                <i class="bi bi-key-fill me-1"></i>Reset Password
            </button>
            <form id="reset-pw-{{ $staff->staff_id }}" method="POST" action="{{ route('staff.resetPassword', $staff->staff_id) }}" style="display:none">@csrf</form>
            <a href="{{ route('staff.edit', $staff->staff_id) }}" class="ios-btn btn-ghost btn-sm-ios"><i class="bi bi-pencil-fill me-1"></i>Edit</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 text-center">
            <div style="width:88px;height:88px;border-radius:50%;margin:0 auto 12px;overflow:hidden;background:linear-gradient(135deg,var(--blue),#5856d6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:30px;font-weight:700">
                @if($staff->profile_image)
                    <img src="{{ Storage::url($staff->profile_image) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    {{ strtoupper(substr($staff->full_name,0,2)) }}
                @endif
            </div>
            <div style="font-size:20px;font-weight:700">{{ $staff->full_name }}</div>
            <span class="pill {{ $staff->role==='admin'?'pill-orange':'pill-green' }}">{{ $staff->role }}</span>
        </div>
        <div class="col-md-8">
            <div class="grouped">
                <div class="grouped-row"><span class="row-label">Username</span><span class="row-val">{{ '@'.$staff->username }}</span></div>
                <div class="grouped-row"><span class="row-label">Gender</span><span class="row-val">{{ ucfirst($staff->gender??'N/A') }}</span></div>
                <div class="grouped-row"><span class="row-label">Phone</span><span class="row-val">{{ $staff->phone_number }}</span></div>
                <div class="grouped-row"><span class="row-label">Date of Birth</span><span class="row-val">{{ $staff->date_of_birth }}</span></div>
                <div class="grouped-row"><span class="row-label">Staff ID</span><span class="row-val">S{{ str_pad($staff->staff_id,3,'0',STR_PAD_LEFT) }}</span></div>
                <div class="grouped-row"><span class="row-label">Status</span><span class="pill {{ $staff->status==='active'?'pill-green':'pill-gray' }}">{{ $staff->status }}</span></div>
                <div class="grouped-row"><span class="row-label">Joined</span><span class="row-val">{{ \Carbon\Carbon::parse($staff->created_at)->format('Y-m-d') }}</span></div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([
            ['bi-arrow-down-right-circle-fill','Check-Ins',$checkIns,'var(--green)'],
            ['bi-arrow-up-right-circle-fill','Check-Outs',$checkOuts,'var(--orange)'],
            ['bi-banknote','Revenue Collected','$'.number_format($revenue,2),'var(--blue)'],
        ] as [$icon,$lbl,$val,$clr])
            <div class="col-md-4">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div style="width:40px;height:40px;border-radius:11px;background:{{ $clr }}15;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi {{ $icon }}" style="color:{{ $clr }};font-size:18px"></i>
                    </div>
                    <div><div class="stat-label">{{ $lbl }}</div><div class="stat-val" style="font-size:22px">{{ $val }}</div></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card-ios card-ios-p">
        <div style="font-size:16px;font-weight:700;margin-bottom:16px">Recent Activity</div>
        @forelse ($recentActivity as $ticket)
            <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--gray5)!important">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $ticket->status==='active'?'rgba(52,199,89,0.1)':'rgba(0,122,255,0.1)' }};display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-{{ $ticket->status==='active'?'arrow-down-right-circle-fill':'arrow-up-right-circle-fill' }}" style="color:{{ $ticket->status==='active'?'var(--green)':'var(--blue)' }};font-size:15px"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:600">{{ $ticket->vehicle->plate_number ?? 'No plate' }}
                            <span class="pill {{ $ticket->status==='active'?'pill-green':($ticket->status==='completed'?'pill-blue':'pill-gray') }} ms-1">{{ ucfirst($ticket->status) }}</span>
                        </div>
                        <div style="font-size:12px;color:var(--gray)">{{ \Carbon\Carbon::parse($ticket->entry_time)->format('M d, g:i A') }} · Slot {{ $ticket->slot->slot_number??'N/A' }}</div>
                    </div>
                </div>
                @if($ticket->payment)<span style="font-weight:700">${{ number_format($ticket->payment->total_fee,2) }}</span>@endif
            </div>
        @empty
            <div class="text-center py-4" style="color:var(--gray)">
                <i class="bi bi-clock-history d-block mb-2" style="font-size:24px;color:var(--gray2)"></i>
                No activity yet.
            </div>
        @endforelse
    </div>
</x-layout>
