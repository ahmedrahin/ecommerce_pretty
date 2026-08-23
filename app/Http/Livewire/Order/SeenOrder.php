<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use Auth;

class SeenOrder extends Component
{
    public $orders;


    public function mount(){
        $this->orders = Order::where('is_seen', 0)->update(['is_seen' => 1]);
    }

    public function render()
    {
        return view('livewire.order.seen-order');
    }

}
