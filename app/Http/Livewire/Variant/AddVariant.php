<?php

namespace App\Http\Livewire\Variant;
use App\Models\Attribute;
use Illuminate\Support\Facades\Cache;

use Livewire\Component;

class AddVariant extends Component
{

    public $attr_id;
    public $attr_name;
    public $edit_mode = false;

    // Event listeners
    protected $listeners = [
        'update_attr'      => 'updateAttr',
        'delete_attr'      => 'delete',
        'open_add_modal'   => 'openAddModal',
    ];

    // Handle form submission
    public function submit()
    {
        // Validation rules
        $rules = [
            'attr_name'  => 'required|unique:attributes,attr_name',
        ];

        // validation message
        $message = [
            'attr_name.required' => 'Attribute name filed is required',
            'attr_name.unique'   => 'Attribute name has already been taken.',
        ];

        if ($this->edit_mode) {
            $rules['attr_name'] = 'required|unique:attributes,attr_name,' . $this->attr_id;
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
        $attrData = [
            'attr_name' => ucfirst($this->attr_name),
        ];

        // Create the attr
        Attribute::create($attrData);

        // Emit success message
        $this->emit('success', __('Attribute created successfully.'));
        $this->emit('refreshList');

        // Reset form fields
        $this->resetForm();
    }

    // update the attr
    public function updateAttr($id)
    {   
        $attr = Attribute::findOrFail($id);
        $this->edit_mode = true;
        $this->attr_id   = $attr->id;
        $this->fill($attr->toArray());
    }

    // Update an existing attr
    private function updateExistingAttr()
    {
        $attrValue = Attribute::findOrFail($this->attr_id);
        $attrValue->attr_name  = ucfirst($this->attr_name);
 
        $attrValue->save();
        $this->emit('success', __('Attribute updated successfully.'));
        $this->emit('refreshList');
    }

     // Delete a attr
     public function delete($id)
     {
         // Find the brand by ID
         $attr = Attribute::findOrFail($id);
         // Delete the brand
         $attr->delete();
 
         // Emit success message and reset the form
         $this->emit('info', __('Attribute was deleted.'));
         $this->resetForm();
         $this->emit('refreshList');
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
        $this->reset(['attr_name']);
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.variant.add-variant');
    }
}
