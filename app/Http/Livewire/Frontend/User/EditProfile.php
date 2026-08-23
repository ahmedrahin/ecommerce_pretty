<?php

namespace App\Http\Livewire\Frontend\User;

use Livewire\Component;
use App\Models\User;

class EditProfile extends Component
{
    public $user_id;
    public $user;

    public $name;
    public $email;
    public $phone;
    public $avatar;
    public $address_line1;

    protected $listeners = [
        'open_add_modal' => 'openAddModal',
    ];

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->user = User::findOrFail($this->user_id);
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->address_line1 = $this->user->address_line1;
    }

    public function submit()
    {
        $rules = [
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'phone' => 'nullable|numeric',
            'address_line1' => 'nullable'
        ];

        // Validate form input
        $validatedData = $this->validate($rules);

        // Update the user
        $this->user->update($validatedData);

        $this->emit('success', 'Profile updated successfully!');
        $this->emit('profileUpdated');
    }

    public function openAddModal()
    {
        $this->resetValidation();
        $this->loadUserData();
    }

    public function render()
    {
        return view('livewire.frontend.user.edit-profile');
    }
}
