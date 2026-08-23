<?php

namespace App\Http\Livewire\UserDetails;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use Livewire\WithPagination;

class UserOrder extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $user_id;

    public function mount($user_id){
        $this->user_id = $user_id;
    }

    public function render()
    {   
        $orders = Order::whereNotNull('user_id')->where('user_id', $this->user_id)->where('user_type', 'customer')->latest()->paginate(10);
        $user = User::find($this->user_id)->name;
        return view('livewire.user-details.user-order', [
            'orders' => $orders,
            'total' => $orders->total(),
            'user' => $user
        ]);
    }
}
