<x-layout title="My Profile">
    <div class="page-header"><div class="page-title">My Profile</div></div>
    <div style="max-width:500px">
        <div class="text-center mb-4">
            <div id="avatarWrap" style="width:88px;height:88px;border-radius:50%;margin:0 auto 12px;overflow:hidden;background:linear-gradient(135deg,var(--blue),#5856d6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:30px;font-weight:700;cursor:pointer" onclick="document.getElementById('photoInput').click()">
                @if($user->profile_image)
                    <img src="{{ Storage::url($user->profile_image) }}" style="width:100%;height:100%;object-fit:cover">
                @else
                    <span id="initials">{{ strtoupper(substr($user->full_name,0,2)) }}</span>
                    <img id="preview" src="" style="display:none;width:100%;height:100%;object-fit:cover">
                @endif
            </div>
            <div style="font-size:20px;font-weight:700">{{ $user->full_name }}</div>
            <span class="pill {{ $user->role==='admin'?'pill-orange':'pill-green' }}">{{ ucfirst($user->role) }}</span>
        </div>

        <form method="POST" action="{{ route('settings.updateProfile') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" id="photoInput" name="profile_image" accept="image/*" style="display:none" onchange="previewPhoto(this)">

            <div class="section-hdr">Account Info</div>
            <div class="grouped mb-4">
                <div class="grouped-row"><span class="row-label">Username</span><span class="row-val">{{ '@'.$user->username }}</span></div>
                <div class="grouped-row"><span class="row-label">Staff ID</span><span class="row-val">S{{ str_pad($user->staff_id,3,'0',STR_PAD_LEFT) }}</span></div>
                <div class="grouped-row"><span class="row-label">Role</span><span class="row-val">{{ ucfirst($user->role) }}</span></div>
            </div>

            <div class="section-hdr">Edit Info</div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Full Name</label><input type="text" name="full_name" class="ios-input" value="{{ old('full_name',$user->full_name) }}" required></div>
            <div class="mb-3"><label style="font-size:12px;font-weight:700;color:var(--gray);display:block;margin-bottom:6px;text-transform:uppercase">Phone Number</label><input type="text" name="phone_number" class="ios-input" value="{{ old('phone_number',$user->phone_number) }}" required></div>

            @if($errors->any())<div class="alert-ios alert-danger-ios mb-3">{{ $errors->first() }}</div>@endif

            <div class="d-flex gap-3 mt-2">
                <label class="ios-btn btn-ghost flex-fill text-center" style="cursor:pointer">
                    <i class="bi bi-camera me-1"></i>Change Photo
                    <input type="file" name="profile_image" accept="image/*" style="display:none" onchange="previewPhoto(this)">
                </label>
                <button type="submit" class="ios-btn btn-primary-ios flex-fill">Save Changes</button>
            </div>
        </form>
    </div>
    <x-slot:scripts>
        <script>
            function previewPhoto(input) {
                if(input.files&&input.files[0]){
                    const reader=new FileReader();
                    reader.onload=e=>{
                        const wrap=document.getElementById('avatarWrap');
                        wrap.innerHTML='<img src="'+e.target.result+'" style="width:100%;height:100%;object-fit:cover">';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    </x-slot:scripts>
</x-layout>
