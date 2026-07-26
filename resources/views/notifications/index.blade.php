<x-layout title="Notifications">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div><div class="page-title">Notifications</div><div class="page-subtitle">{{ $notifications->count() }} alerts</div></div>
        <form method="POST" action="{{ route('notifications.clear') }}">@csrf<button class="ios-btn ios-btn-danger ios-btn-sm"><i class="bi bi-trash-fill me-1"></i>Clear all</button></form>
    </div>
    <div class="ios-card" style="padding:4px 0">
        @forelse ($notifications as $notif)
            @php $icons=['alert'=>'bi-exclamation-triangle-fill text-ios-orange','check_out'=>'bi-check-circle-fill text-ios-green','check_in'=>'bi-arrow-down-right-circle-fill text-ios-blue','system'=>'bi-gear-fill text-ios-gray']; $icon=$icons[$notif->type]??'bi-bell-fill text-ios-gray'; @endphp
            <div class="d-flex gap-3 py-3 px-4 {{ !$loop->last?'border-bottom':'' }}" style="border-color:var(--ios-gray5)!important;opacity:{{ $notif->is_read?'0.5':'1' }}">
                <div style="width:40px;height:40px;border-radius:12px;background:var(--ios-gray6);display:flex;align-items:center;justify-content:center;flex-shrink:0"><i class="bi {{ $icon }}" style="font-size:18px"></i></div>
                <div>
                    <div style="font-size:14px;font-weight:500">{{ $notif->message }}</div>
                    <div style="font-size:12px;color:var(--ios-gray);margin-top:2px">{{ \Carbon\Carbon::parse($notif->created_at)->format('M d, g:i A') }}</div>
                </div>
            </div>
        @empty
            <div class="text-center py-5" style="color:var(--ios-gray)">No notifications.</div>
        @endforelse
    </div>
</x-layout>
