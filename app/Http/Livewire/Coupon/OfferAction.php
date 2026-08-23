<?php

namespace App\Http\Livewire\Coupon;

use Livewire\Component;
use App\Models\Offer;
use Illuminate\Support\Facades\Storage;

class OfferAction extends Component
{
     protected $listeners = [
        'delete'             => 'delete',
        'update_status'             => 'updateStatus'
    ];

     public function updateStatus($id, $status)
    {
        $coupon = Offer::findOrFail($id);
        $coupon->update(['status' => $status]);

        $message = $status == 0 ? "Offer is inactive" : "Offer is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
    }

    public function delete($id)
    {
        // Find the coupon by ID
        $data = Offer::findOrFail($id);
        $data->delete();
        if ($data->image) {
            Storage::disk('real_public')->delete($data->image);
        }
        $this->emit('info', __('Offer was deleted.'));
    }
    
    public function render()
    {
        return view('livewire.coupon.offer-action');
    }
}
