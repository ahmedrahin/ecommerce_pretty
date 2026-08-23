<?php

namespace App\Http\Livewire\Shipping\Method;

use Livewire\Component;
use App\Models\ShippingMethod;
use Livewire\WithPagination;

class ListRegularShippingMethod extends Component
{
    use WithPagination;

    protected $paginationCount = 5;
    protected $provider_methods;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshShipping' => 'refreshShipping'];

    public function mount()
    {
        $this->provider_methods = ShippingMethod::latest()->whereNull('base_id')->paginate($this->paginationCount);
    }

    public function refreshShipping()
    {
        // Refresh provider_methods
        $this->provider_methods = ShippingMethod::latest()->whereNull('base_id')->paginate($this->paginationCount);
    }

    // Update status
    public function updateStatus($id, $status)
    {
        $shipping = ShippingMethod::findOrFail($id);
        $shipping->update(['status' => $status]);

        $message = $status == 0 ? "The shipping method is inactive" : "The shipping method is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
        
        // Refresh the shipping methods after updating the status
        $this->refreshShipping();
    }

    public function render()
    {
        $this->provider_methods = ShippingMethod::latest()->whereNull('base_id')->paginate($this->paginationCount);
        // Always return the paginated provider methods
        return view('livewire.shipping.method.list-regular-shipping-method', [
            'provider_methods' => $this->provider_methods, 
            'totalMethods'     => $this->provider_methods->total(),
        ]);
    }
}
