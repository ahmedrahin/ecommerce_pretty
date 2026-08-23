<?php

namespace App\Http\Livewire\Shipping\Method;

use Livewire\Component;
use App\Models\ShippingMethod;

class AddRegularShipping extends Component
{
    public $shipping_id;
    public $provider_name;
    public $provider_charge;
    public $edit_mode = false;

    // Event listeners
    protected $listeners = [
        'update_regular_shipping'      => 'updateShipping',
        'delete_regular_shipping'      => 'delete',
        'open_add_modal'               => 'openAddModal',
    ];

    // Handle form submission
    public function submit()
    {
        // Validation rules
        $rules = [
            'provider_name'  => 'required|unique:shipping_methods,provider_name',
            'provider_charge' => 'required|numeric'
        ];

        // validation message
        $message = [
            'base_id.required'   => 'Please enter a provier name',
            'base_charge.required'   => 'Please enter a charge amount',
        ];

        if ($this->edit_mode) {
            $rules['provider_name'] = 'required|unique:shipping_methods,provider_name,' . $this->shipping_id;
        }

        // Validate form input
        $this->validate($rules, $message);

        // Check if we are in edit mode
        if ($this->edit_mode) {
           $this->updateExistingAttr();
        } else {
            $this->createNewAttr();
        }

        // Reset the form
        $this->resetForm();
    }

    // Create a new attribute
    public function createNewAttr()
    {
        // Prepare the attr data
        $shippingData = [
            'provider_name' => $this->provider_name,
            'provider_charge' => $this->provider_charge
        ];

        // Create the attr
        ShippingMethod::create($shippingData);

        // Emit success message
        $this->emit('success', __('Shipping method created successfully.'));
        $this->emit('refreshShipping');

    }

     // update the attr
     public function updateShipping($id)
     {   
         $shipping = ShippingMethod::findOrFail($id);
         $this->edit_mode = true;
         $this->shipping_id   = $shipping->id;
         $this->fill($shipping->toArray());
     }
 
     // Update an existing attr
     private function updateExistingAttr()
     {
         $shipping = ShippingMethod::findOrFail($this->shipping_id);
         $shipping->provider_name  = $this->provider_name;
         $shipping->provider_charge  = $this->provider_charge;
  
         $shipping->save();
         $this->emit('success', __('Shipping method updated successfully.'));
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
          $this->emit('info', __('Shipping method was deleted.'));
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
         $this->reset(['provider_name', 'provider_charge']);
     }
 
     public function hydrate()
     {
         // Reset error bag and validation
         $this->resetErrorBag();
         $this->resetValidation();
     }


    public function render()
    {
        return view('livewire.shipping.method.add-regular-shipping');
    }
}
