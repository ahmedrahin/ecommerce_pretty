<?php

namespace App\Http\Livewire\Shipping\Method;

use Livewire\Component;
use App\Models\District;
use App\Models\ShippingMethod;

class AddBaseShipping extends Component
{
    public $shipping_id;
    public $base_id;
    public $base_charge;
    public $edit_mode = false;
    public $districts;

    // Event listeners
    protected $listeners = [
        'update_base_shipping'      => 'updateShipping',
        'delete_base_shipping'      => 'delete',
        'open_add_modal'            => 'openAddModal',
    ];

    public function mount(){
        $this->districts = District::orderBy('name', 'asc')->get();
    }

    // Handle form submission
    public function submit()
    {
        // Validation rules
        $rules = [
            'base_id'  => 'required|unique:shipping_methods,base_id',
            'base_charge' => 'required|numeric'
        ];

        // validation message
        $message = [
            'base_id.required'   => 'Please select a district',
            'base_charge.required'   => 'Please enter a charge amount',
        ];

        if ($this->edit_mode) {
            $rules['base_id'] = 'required|unique:shipping_methods,base_id,' . $this->shipping_id;
        }

        // Validate form input
        $this->validate($rules, $message);

        // Check if we are in edit mode
        if ($this->edit_mode) {
           $this->updateExisting();
        } else {
            $this->createNew();
        }

        // Reset the form
        $this->resetForm();
    }

    // Create a new base shipping
    public function createNew()
    {
        // Prepare the attr data
        $shippingData = [
            'base_id' => $this->base_id,
            'base_charge' => $this->base_charge
        ];

        // Create the attr
        ShippingMethod::create($shippingData);

        // Emit success message
        $this->emit('success', __('Base shipping method created successfully.'));
        $this->emit('refreshShipping');
        // Reset form fields
        $this->resetForm();
    }

    // update the  base shipping
    public function updateShipping($id)
    {   
        $shipping = ShippingMethod::findOrFail($id);
        $this->edit_mode = true;
        $this->shipping_id   = $shipping->id;
        $this->fill($shipping->toArray());
    }

    // Update an  base shipping
    private function updateExisting()
    {
        $shipping = ShippingMethod::findOrFail($this->shipping_id);
        $shipping->base_id  = $this->base_id;
        $shipping->base_charge  = $this->base_charge;
 
        $shipping->save();
        $this->emit('success', __('Base shipping method updated successfully.'));
        $this->emit('refreshShipping');
    }

     // Delete a attr
     public function delete($id)
     {
         // Find the brand by ID
         $method = ShippingMethod::findOrFail($id);
         // Delete the brand
         $method->delete();
 
         // Emit success message and reset the form
         $this->emit('info', __('Base shipping method was deleted.'));
         $this->resetForm();
         $this->emit('refreshShipping');
     }

    // Method to open the add modal and reset the form
    public function openAddModal()
    {
        $this->resetForm();
    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['base_id', 'base_charge']);
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.shipping.method.add-base-shipping');
    }
}
