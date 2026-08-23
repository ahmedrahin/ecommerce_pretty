<?php

namespace App\Http\Livewire\Frontend\User;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChangePassword extends Component
{
    public $user_id;
    public $user;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $listeners = ['open_add_modal' => 'openAddModal',];

    public function mount($user_id){
        $this->user_id = $user_id;
        $this->user = User::find($this->user_id);
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
            $this->addError('current_password', 'Current password is incorrect.');
             $this->emit('error', 'Current password is incorrect.');
             return;
         }

         if (Hash::check($this->new_password, $this->user->password)) {
             $this->addError('current_password', 'Current password is incorrect.');
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
        $this->openAddModal();
     }

     public function openAddModal()
    {
        $this->resetValidation();
        $this->reset('current_password', 'new_password', 'new_password_confirmation');
    }

    public function render()
    {
        return view('livewire.frontend.user.change-password');
    }
}
