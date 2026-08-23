<?php

namespace App\Http\Livewire\Filter;
use App\Models\FilterOption;
use App\Models\CategoryFilter;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

use Livewire\Component;

class AddOption extends Component
{

    public $attr_id;
    public $attr_name;
    public $edit_mode = false;
    public $categories = [];
    public $category_ids = [];


    // Event listeners
    protected $listeners = [
        'update_attr'      => 'updateAttr',
        'delete_attr'      => 'delete',
        'open_add_modal'   => 'openAddModal',
    ];

    public function mount()
    {
        $this->categories = Category::where('status',1)->get();
    }

    public function submit()
    {
        $rules = [
            'attr_name'  => 'required',
        ];

        // validation message
        $message = [
            'attr_name.required' => 'Attribute name filed is required',
            'attr_name.unique'   => 'Attribute name has already been taken.',
        ];

        if ($this->edit_mode) {
            $rules['attr_name'] = 'required';
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
        $attrData = [
            'option_name' => ucfirst($this->attr_name),
        ];

        // Create the attr
        $attribute = FilterOption::create($attrData);

        foreach ($this->category_ids as $catId) {
            CategoryFilter::create([
                'filter_option_id' => $attribute->id,
                'category_id'      => $catId,
            ]);
        }

        // Emit success message
        $this->emit('success', __('Attribute created successfully.'));
        $this->emit('refreshList');

        // Reset form fields
        $this->resetForm();
    }

    // update the attr
    public function updateAttr($id)
    {
        $attr = FilterOption::findOrFail($id);
        $this->edit_mode = true;
        $this->attr_id   = $attr->id;
        $this->attr_name   = $attr->option_name;
        $this->category_ids = $attr->categories->pluck('id')->toArray();
        $this->emit('refreshSelect2');
    }

    // Update an existing attr
    private function updateExistingAttr()
    {
        $attrValue = FilterOption::findOrFail($this->attr_id);
        $attrValue->option_name  = ucfirst($this->attr_name);

        $attrValue->categories()->sync($this->category_ids);

        $attrValue->save();
        $this->emit('success', __('Attribute updated successfully.'));
        $this->emit('refreshList');
    }

     // Delete a attr
     public function delete($id)
     {
         // Find the brand by ID
         $attr = FilterOption::findOrFail($id);
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
         $this->emit('refreshSelect2');
    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['attr_name', 'category_ids']);
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.filter.add-option');
    }
}
