<x-layout title="Add Staff">
    <div class="page-header"><div class="page-title" style="font-size:22px">Add Staff Member</div></div>
    <div class="card-ios card-ios-p" style="max-width:540px">
        <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">@csrf
            <div class="text-center mb-4">
                <div id="previewWrap" style="width:80px;height:80px;border-radius:50%;background:var(--gray6);margin:0 auto 12px;display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:pointer" onclick="document.getElementById('photoInput').click()">
                    <img id="preview" src="" style="width:100%;height:100%;object-fit:cover;display:none">
                    <i class="bi bi-camera-fill" style="font-size:24px;color:var(--gray)"></i>
                </div>
                <label class="ios-btn btn-ghost btn-sm-ios" style="cursor:pointer">
                    <i class="bi bi-camera me-1"></i>Upload Photo
                    <input type="file" id="photoInput" name="profile_image" accept="image/*" style="display:none" onchange="previewPhoto(this)">
                </label>
            </div>
            <div class="section-hdr">Account</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Username</label><input type="text" name="username" class="ios-input" value="{{ old('username') }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Role</label><select name="role" class="ios-input"><option value="staff">Staff</option><option value="admin">Admin</option></select></div>
            <div class="section-hdr mt-4">Personal Info</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Full Name</label><input type="text" name="full_name" class="ios-input" value="{{ old('full_name') }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Gender</label><select name="gender" class="ios-input"><option value="male">Male</option><option value="female">Female</option></select></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Phone Number</label><input type="text" name="phone_number" class="ios-input" value="{{ old('phone_number') }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Date of Birth</label><input type="date" name="date_of_birth" class="ios-input" value="{{ old('date_of_birth') }}" required></div>
            <div style="background:rgba(255,149,0,0.1);border-radius:12px;padding:12px 16px;font-size:13px;color:var(--orange)"><i class="bi bi-info-circle me-1"></i>Default password: <strong>password</strong></div>
            @if($errors->any())<div class="alert-ios alert-danger-ios mt-3">{{ $errors->first() }}</div>@endif
            <div class="d-flex gap-3 mt-4"><a href="{{ route('staff.index') }}" class="ios-btn btn-ghost flex-fill text-center">Cancel</a><button type="submit" class="ios-btn btn-primary-ios flex-fill">Add Staff Member</button></div>
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
                        document.getElementById('previewWrap').querySelector('i').style.display='none';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
