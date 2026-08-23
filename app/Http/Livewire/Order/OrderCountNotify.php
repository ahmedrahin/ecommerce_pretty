<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;

class OrderCountNotify extends Component
{
    public $orders;

    public function mount(){
        $this->orders = Order::where('is_seen', 0)->count();
    }

    public function render()
    {
        return view('livewire.order.order-count-notify');
    }
}
