<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;

class OrderDelete extends Component
{
     protected $listeners = [
        'delete_order'  => 'delete',
        'delete_permanent' => 'delete_permanent',
        'restore' => 'restore',
    ];

    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        $this->emit('info', ('Order was deleted.'));
    }

    public function delete_permanent($id){
        $order = Order::withTrashed()->find($id);
        $order->forceDelete();
        $this->emit('info', ('Order has been permanent deleted.'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        $this->emit('success', ('Order restored successfully.'));
    }

    public function render()
    {
        return view('livewire.order.order-delete');
    }
}
