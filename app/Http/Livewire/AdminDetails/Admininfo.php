<?php

namespace App\Http\Livewire\AdminDetails;

use Livewire\Component;
use App\Models\User;
use App\Models\District;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Admininfo extends Component
{
    use WithFileUploads;
    public $user_id;
    public $user;

    public $name;
    public $email;
    public $phone;
    public $image;
    public $address_line1;
    public $address_line2;
    public $division_id;
    public $district_id;
    public $zipCode;
    public $last_login_at;
    public $current_image;

    protected $listeners = ['updated' => 'refreshUser'];

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->user = User::find($this->user_id);

        if ($this->user) {
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->phone = $this->user->phone;
            $this->current_image = $this->user->avatar;
            $this->address_line1 = $this->user->address_line1;
            $this->address_line2 = $this->user->address_line2;
            $this->division_id = $this->user->division_id;
            $this->district_id = $this->user->district_id;
            $this->zipCode = $this->user->zipCode;
            $this->last_login_at = $this->user->last_login_at;
        }
    }

    public function refreshUser($user_id)
    {
        if ($this->user_id == $user_id) {
            // Reload user data if the updated user matches the current user
            $this->loadUserData();
        }
    }

    public function updateData(){
        
        // Validate the data
        $validatedData = $this->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'phone' => 'nullable|string|min:11|max:11',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'division_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'zipCode' => 'nullable|string|max:10',
        ]);

         // Handle the image update logic
        if ($this->image) {
            if ($this->user->avatar) {
                // Delete old image if exists
                Storage::disk('real_public')->delete($this->user->avatar);
            }
            // Store new image
            $this->user->avatar = $this->image->store('uploads/admin', 'real_public');
        } elseif ($this->current_image === null && $this->user->avatar) {
            // Remove avatar if current_image is null and user has an existing avatar
            Storage::disk('real_public')->delete($this->user->avatar);
            $this->user->avatar = null;
        }

        // Update the user record
        $this->user->update($validatedData);

        // Optionally, emit an event or return a success message
       $this->emit('success', 'Admin details updated successfully.');
    }

    public function removeImage()
    {
        $this->image = null;
        if ($this->current_image) {
            $this->current_image = null;
        }
    }

     public function render()
    {   
        $created = User::where('id', $this->user->created_by)->first();
        $districts = District::orderBy('name', 'asc')->get();
        return view('livewire.admin-details.admininfo', [
            'user' => $this->user,
            'districts' => $districts,
            'created' => $created
        ]);
    }
}
