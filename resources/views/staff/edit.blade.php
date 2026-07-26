<x-layout title="Edit Staff">
    <div class="page-header"><div class="page-title" style="font-size:22px">Edit Staff</div></div>
    <div class="card-ios card-ios-p" style="max-width:540px">
        <form method="POST" action="{{ route('staff.update',$staff->staff_id) }}" enctype="multipart/form-data">@csrf @method('PATCH')
            <div class="text-center mb-4">
                <div id="previewWrap" style="width:80px;height:80px;border-radius:50%;margin:0 auto 12px;overflow:hidden;background:linear-gradient(135deg,var(--blue),#5856d6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:26px;font-weight:700;cursor:pointer" onclick="document.getElementById('photoInput').click()">
                    @if($staff->profile_image)
                        <img src="{{ Storage::url($staff->profile_image) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <span id="initials">{{ strtoupper(substr($staff->full_name,0,2)) }}</span>
                        <img id="preview" src="" style="display:none;width:100%;height:100%;object-fit:cover">
                    @endif
                </div>
                <label class="ios-btn btn-ghost btn-sm-ios" style="cursor:pointer">
                    <i class="bi bi-camera me-1"></i>Change Photo
                    <input type="file" id="photoInput" name="profile_image" accept="image/*" style="display:none" onchange="previewPhoto(this)">
                </label>
            </div>

            <div class="section-hdr">Account</div>
            <div class="mb-3">
                <label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Username</label>
                <div style="position:relative">
                    <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--gray);font-size:15px">@</span>
                    <input type="text" name="username" class="ios-input" style="padding-left:32px" value="{{ old('username',$staff->username) }}" required>
                </div>
            </div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Role</label><select name="role" class="ios-input"><option value="staff" {{ $staff->role==='staff'?'selected':'' }}>Staff</option><option value="admin" {{ $staff->role==='admin'?'selected':'' }}>Admin</option></select></div>

            <div class="section-hdr mt-3">Personal Info</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Full Name</label><input type="text" name="full_name" class="ios-input" value="{{ old('full_name',$staff->full_name) }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Gender</label><select name="gender" class="ios-input"><option value="male" {{ $staff->gender==='male'?'selected':'' }}>Male</option><option value="female" {{ $staff->gender==='female'?'selected':'' }}>Female</option></select></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Phone</label><input type="text" name="phone_number" class="ios-input" value="{{ old('phone_number',$staff->phone_number) }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Date of Birth</label><input type="date" name="date_of_birth" class="ios-input" value="{{ old('date_of_birth',$staff->date_of_birth) }}" required></div>

            @if($errors->any())<div class="alert-ios alert-danger-ios">{{ $errors->first() }}</div>@endif
            <div class="d-flex gap-3 mt-4"><a href="{{ route('staff.show',$staff->staff_id) }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a><button type="submit" class="ios-btn btn-primary-ios flex-fill">Save Changes</button></div>
        </form>
    </div>
    <x-slot:scripts>
        <script>
            function previewPhoto(input) {
                if(input.files&&input.files[0]){
                    const reader=new FileReader();
                    reader.onload=e=>{
                        document.getElementById('preview').src=e.target.result;
                        document.getElementById('preview').style.display='block';
                        const init=document.getElementById('initials');
                        if(init)init.style.display='none';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
