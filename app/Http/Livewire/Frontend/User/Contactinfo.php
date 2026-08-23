<?php

namespace App\Http\Livewire\Frontend\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class Contactinfo extends Component
{
    use WithFileUploads;

    public $user_id;
    public $user;
    public $profilePhoto;

    protected $listeners = ['profileUpdated'];

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $this->profileUpdated();
    }

    public function profileUpdated()
    {
        $this->user = User::find($this->user_id);
    }

    public function updatedProfilePhoto()
    {
        // Validate file
        $validated = $this->validate([
            'profilePhoto' => 'image', 
        ]);

        if ($validated) {
            if ($this->user->avatar) {
                Storage::disk('real_public')->delete($this->user->avatar);
            }

            $path = $this->profilePhoto->store('uploads/user', 'real_public');
            $this->user->update(['avatar' => $path]);

            $this->profileUpdated();
            // Emit success toast message
            $this->emit('success', 'Profile photo updated successfully!');
        } else {
            // Emit error toast message
            $this->emit('error', 'Failed to upload profile photo.');
        }

        $this->profilePhoto = null; 
    }

    public function removePhoto()
    {
        if ($this->user->avatar) {
            Storage::disk('real_public')->delete($this->user->avatar);
            $this->user->update(['avatar' => null]);
            $this->profileUpdated();
            $this->emit('info', 'Profile has been deleted.');
        }
    }

    public function render()
    {
        return view('livewire.frontend.user.contactinfo');
    }
}

