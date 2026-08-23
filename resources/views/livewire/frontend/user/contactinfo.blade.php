<div >
    <div class="profile-contain">
        <div class="upload-box">
            <div class="profile-image">
                @if ($user->avatar)
                    <img class="img-fluid" src="{{ asset($user->avatar) }}" alt="Profile Image">
                    <i class="fa fa-trash" aria-hidden="true" wire:click="removePhoto" style="z-index: 12;"></i>
                @else
                    <img class="img-fluid" src="{{ asset('uploads/user.png') }}" alt="Default Profile Image">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                @endif
                <input type="file" wire:model="profilePhoto" class="form-control" accept=".jpg,.jpeg,.png,.gif">
            </div>
        </div>
        
        {{-- <div wire:loading wire:target="profilePhoto" class="text-primary mt-2">Uploading...</div> --}}
        
        <div class="profile-name"> 
        <h4>{{$user->name}}</h4>
        <h6>{{$user->email}}</h6><span data-bs-toggle="modal" data-bs-target="#edit-box" title="Quick View" tabindex="0">Edit Profile</span>
        </div>
    </div>

</div>
