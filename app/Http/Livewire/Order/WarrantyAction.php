<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Warenty;

class WarrantyAction extends Component
{
    protected $listeners = [
        'delete'  => 'delete',
    ];

    public function delete($id)
    {
        // Find the coupon by ID
        $data = Warenty::findOrFail($id);
        $data->delete();

        $this->emit('info', __('Warranty record has been deleted.'));
    }

    public function render()
    {
        return view('livewire.order.warranty-action');
    }
}
