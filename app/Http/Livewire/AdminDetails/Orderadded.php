<?php

namespace App\Http\Livewire\AdminDetails;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;

class Orderadded extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $user_id;

    public function mount($user_id){
        $this->user_id = $user_id;
    }

    public function render()
    {
        $orders = Order::whereNotNull('user_id')->where('user_id', $this->user_id)->where('user_type', 'author')->latest()->paginate(10);
        return view('livewire.admin-details.orderadded', [
            'orders' => $orders,
            'total' => $orders->total()
        ]);
    }
}
