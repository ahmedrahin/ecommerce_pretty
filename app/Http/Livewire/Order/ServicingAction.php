<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Services;

class ServicingAction extends Component
{
     protected $listeners = [
        'delete'  => 'delete',
    ];

    public function delete($id)
    {
        // Find the coupon by ID
        $data = Services::findOrFail($id);
        $data->delete();

        $this->emit('info', __('Servicing record has been deleted.'));
    }

    public function render()
    {
        return view('livewire.order.servicing-action');
    }
}
