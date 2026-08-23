<?php

namespace App\Http\Livewire\UserDetails;

use Livewire\Component;
use App\Models\User;

class Userinfo extends Component
{
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
     public $total_orders;
    public $total_buying;
    public $status;


    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->user = User::find($this->user_id);

        if ($this->user) {
            $this->status  = $this->user->status;
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

    public function updateStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => $this->status]);

        if ($this->status == 0) {
            $this->emit('info', "{$user->name}'s account has been disabled.");
        } else {
            $this->emit('success', "{$user->name}'s account has been enabled.");
        }
    }

    public function render()
    {
        return view('livewire.user-details.userinfo');
    }
}
