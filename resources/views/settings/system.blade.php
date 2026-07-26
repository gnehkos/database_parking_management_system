<x-layout title="System Settings">
    <div class="page-header"><div class="page-title" style="font-size:22px">System Settings</div></div>
    <div class="card-ios card-ios-p" style="max-width:540px">
        <form method="POST" action="{{ route('settings.updateSystem') }}">@csrf
            <div class="section-hdr">Facility</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Facility Name</label><input type="text" name="facility_name" class="ios-input" value="{{ $settings['facility_name']->setting_value??'' }}"></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Address</label><input type="text" name="facility_address" class="ios-input" value="{{ $settings['facility_address']->setting_value??'' }}"></div>
            <div class="section-hdr mt-4">Localization</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Currency</label><input type="text" name="currency" class="ios-input" value="{{ $settings['currency']->setting_value??'USD' }}"></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Timezone</label><input type="text" name="timezone" class="ios-input" value="{{ $settings['timezone']->setting_value??'Asia/Phnom_Penh' }}"></div>
            <div class="section-hdr mt-4">Rules</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Max Parking Hours</label><input type="number" name="max_parking_hours" class="ios-input" value="{{ $settings['max_parking_hours']->setting_value??'24' }}"></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Receipt Footer</label><input type="text" name="receipt_footer_message" class="ios-input" value="{{ $settings['receipt_footer_message']->setting_value??'' }}"></div>
            <button type="submit" class="ios-btn btn-primary-ios w-100 mt-4">Save Settings</button>
        </form>
    </div>
</x-layout>
