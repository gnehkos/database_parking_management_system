<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::orderBy('created_at', 'asc');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('full_name', 'like', '%'.$s.'%')->orWhere('username', 'like', '%'.$s.'%'));
        }
        $staffMembers = $query->get();
        $activeCount  = Staff::where('status', 'active')->count();
        return view('staff.index', compact('staffMembers', 'activeCount'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'      => ['required', 'string', 'max:50', 'unique:staff,username'],
            'full_name'     => ['required', 'string', 'max:255'],
            'gender'        => ['required', 'in:male,female'],
            'role'          => ['required', 'in:admin,staff'],
            'phone_number'  => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'username'      => $request->username,
            'password_hash' => Hash::make('password'),
            'full_name'     => $request->full_name,
            'gender'        => $request->gender,
            'role'          => $request->role,
            'phone_number'  => $request->phone_number,
            'status'        => 'active',
            'date_of_birth' => $request->date_of_birth,
        ];

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        Staff::create($data);
        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    public function show(Staff $staff)
    {
        $checkIns  = Ticket::where('staff_id', $staff->staff_id)->count();
        $checkOuts = Payment::where('staff_id', $staff->staff_id)->count();
        $revenue   = Payment::where('staff_id', $staff->staff_id)->sum('total_fee');

        $recentActivity = Ticket::where('staff_id', $staff->staff_id)
            ->with('vehicle', 'slot', 'payment')
            ->orderBy('entry_time', 'desc')
            ->take(10)
            ->get();

        return view('staff.show', compact('staff', 'checkIns', 'checkOuts', 'revenue', 'recentActivity'));
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'username'      => ['required', 'string', 'max:50', Rule::unique('staff', 'username')->ignore($staff->staff_id, 'staff_id')],
            'full_name'     => ['required', 'string', 'max:255'],
            'gender'        => ['required', 'in:male,female'],
            'role'          => ['required', 'in:admin,staff'],
            'phone_number'  => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'profile_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = $request->only('username', 'full_name', 'gender', 'role', 'phone_number', 'date_of_birth');

        if ($request->hasFile('profile_image')) {
            if ($staff->profile_image) Storage::disk('public')->delete($staff->profile_image);
            $data['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        $staff->update($data);
        return redirect()->route('staff.show', $staff)->with('success', 'Staff updated.');
    }

    public function resetPassword(Staff $staff)
    {
        $staff->update(['password_hash' => Hash::make('password')]);
        return redirect()->route('staff.show', $staff)->with('success', $staff->full_name . "'s password reset to \"password\".");
    }

    public function destroy(Staff $staff)
    {
        if ($staff->staff_id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete your own account.');
        }
        if ($staff->profile_image) {
            Storage::disk('public')->delete($staff->profile_image);
        }
        $staff->delete();
        return redirect()->route('staff.index')->with('success', $staff->full_name . ' has been permanently removed.');
    }

    public function toggleStatus(Staff $staff)
    {
        $newStatus = $staff->status === 'active' ? 'deactivated' : 'active';
        $staff->update(['status' => $newStatus]);
        return redirect()->route('staff.index')
            ->with('success', $staff->full_name . ($newStatus === 'active' ? ' activated.' : ' deactivated.'));
    }
}
