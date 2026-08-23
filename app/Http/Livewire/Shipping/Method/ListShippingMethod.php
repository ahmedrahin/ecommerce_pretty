<?php

namespace App\Http\Livewire\Shipping\Method;

use Livewire\Component;
use App\Models\ShippingMethod;
use Livewire\WithPagination;

class ListShippingMethod extends Component
{
    use WithPagination;

    protected $paginationCount = 5;
    protected $paginationTheme = 'bootstrap'; 

    // Change base_methods to protected
    protected $base_methods; // Protected property

    public function mount()
    {
        // Initialize the paginated methods when the component is mounted
        $this->refreshShipping();
    }

    protected $listeners = ['refreshShipping' => 'refreshShipping'];

    public function refreshShipping()
    {
        // Refresh base_methods with new paginated results
        $this->base_methods = ShippingMethod::latest()->whereNotNull('base_id')->paginate($this->paginationCount);
    }

    // Update status method
    public function updateStatus($id, $status)
    {
        $shipping = ShippingMethod::findOrFail($id);
        $shipping->update(['status' => $status]);

        $message = $status == 0 ? "The shipping method is inactive" : "The shipping method is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
        $this->refreshShipping(); // Refresh the list after status update
    }

    public function render()
    {
        // Fetch paginated results for base_methods before rendering the view
        $this->base_methods = ShippingMethod::latest()->whereNotNull('base_id')->paginate($this->paginationCount);

        return view('livewire.shipping.method.list-shipping-method', [
            'base_methods'     => $this->base_methods,
            'totalBaseMethods' => $this->base_methods->total(), // Now this will work
        ]);
    }
}
