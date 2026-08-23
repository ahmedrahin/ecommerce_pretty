<?php

namespace App\Http\Livewire\AdminDetails;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecurityUpdate extends Component
{
    public $user_id;
    public $user;

    public $email;
    public $selected_role;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $listeners = [
        'open_add_modal'    => 'openAddModal',
    ];

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $this->user = User::find($this->user_id);

        if ($this->user) {
            $this->email = $this->user->email;
            $this->selected_role = $this->user->roles->first()?->id ?? null;
        }
    }

    // update admin email
    public function updateEmail(){
        $validatedData = $this->validate([
            'email' => 'required|email|unique:users,email,' . $this->user_id,
        ]);

        if( $this->user->email == $this->email ){
            $this->emit('warning', 'Please select a new email');
            return;
        }

         // Update the user email
         $this->user->update($validatedData);

         // Optionally, emit an event or return a success message
        $this->emit('success', 'Admin email updated successfully.');
        $this->emit('updated', $this->user->id);
    }

    // update or new password
    public function ChnagePassword()
    {
        $passwordMinLength = config('auth.password_min_length');

        // Validate passwords
        $validatedData = $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:' . $passwordMinLength . '|confirmed',
            'new_password_confirmation' => 'required_with:new_password|same:new_password',
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => "New password must be at least {$passwordMinLength} characters.",
            'new_password_confirmation.required_with' => 'Please confirm your new password.',
            'new_password_confirmation.same' => 'The confirmation password does not match.',
        ]);

        // Check if current password matches
        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->emit('error', 'Current password is incorrect.');
            return;
        }

        if (Hash::check($this->new_password, $this->user->password)) {
            $this->emit('error', 'New password cannot be the same as the current password.');
            return;
        }        

        // Update the password
        $this->user->update([
            'password' => Hash::make($this->new_password),
        ]);

        if($this->user->id !== Auth::id()){
            DB::table('sessions')
            ->where('user_id', $this->user->id)
            ->delete();
        }

        $this->emit('success', 'Password changed successfully.');
        $this->resetForm();
    }


    // update admin role
    public function updateRole()
    {
        $validatedData = $this->validate([
            'selected_role' => 'required|exists:roles,id', 
        ],[
            'selected_role.required' => 'Please select a role first.'
        ]);

        if( $this->user->roles->first()?->id == $this->selected_role ){
            $this->emit('warning', 'Please select a new role');
            return;
        }

        // Find the selected role
        $role = Role::find($validatedData['selected_role']);

        if ($role) {
            $this->user->syncRoles($role->name);
            $this->emit('success', 'Role updated successfully.');
            $this->emit('updated', $this->user->id);
        }
    }

    private function resetForm()
    {
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    }

    public function openAddModal()
    {
        $this->resetForm();
    }


    public function render()
    {   
        $roles = Role::orderBy('name', 'asc')->get();
        return view('livewire.admin-details.security-update', [
            'user' => $this->user,
            'roles' => $roles
        ]);
    }
}
