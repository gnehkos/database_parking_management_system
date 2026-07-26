<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('settings.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only('full_name', 'phone_number');

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function changePassword()
    {
        return view('settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->getAuthPassword())) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password_hash' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated.');
    }

    public function systemSettings()
    {
        $settings = SystemSetting::all()->keyBy('setting_key');
        return view('settings.system', compact('settings'));
    }

    public function updateSystemSettings(Request $request)
    {
        $keys = ['facility_name', 'facility_address', 'currency', 'timezone', 'max_parking_hours', 'receipt_footer_message', 'khr_exchange_rate'];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                SystemSetting::where('setting_key', $key)->update([
                    'setting_value' => $request->input($key),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'System settings updated.');
    }
}
