<?php

namespace App\Http\Livewire\Filter;

use Livewire\Component;
use App\Models\FilterOption as Filter;
use App\Models\FilterOptionValue;
use Illuminate\Validation\Rule;

class FilterValue extends Component
{
    public $value_id;
    public $attr_id;
    public $attr_value;
    public $option;
    public $edit_mode = false;
    public $attributeValues;

    public function mount()
    {
        $this->loadAttributesValues();
    }

    protected $listeners = [
        'refreshList'       => 'loadAttributesValues',
        'open_value_modal'  => 'openAddModal',
        'delete_attrVal'    => 'deleteValue',
        'update_attrValue'  => 'updateAttrValue',
    ];

    // Method to load attribute values
    public function loadAttributesValues()
    {
        $this->attributeValues = Filter::with(['values' => function($query) {
            $query->orderBy('id', 'asc');
        }])->orderBy('id', 'asc')->get();
    }

    public function openAddModal($attrId)
    {
        $this->attr_id = $attrId;
        $this->resetForm();
    }

    // Handle form submission
    public function submit()
    {
       // Validation rules
       $rules = [
            'attr_value' => [
                'required',
                Rule::unique('filter_option_values', 'option_value')->where(function ($query) {
                    return $query->where('filter_option_id', $this->attr_id);
                }),
            ],
        ];

        // Validation messages
        $messages = [
            'attr_value.required' => 'Attribute value field is required',
            'attr_value.unique' => 'This attribute value already exists for the selected attribute.',
        ];

        if ($this->edit_mode) {
           $rules = [
                'attr_value' => [
                    'required',
                    Rule::unique('filter_option_values', 'option_value')->where(function ($query) {
                        return $query->where('filter_option_id', $this->attr_id);
                    })->ignore($this->value_id),
                ],
            ];
        }

        // Validate the form
        $this->validate($rules, $messages);

        // Check if we are in edit mode
        if ($this->edit_mode) {
            $this->updateExistingAttrValue();
        } else {
            $this->createNewAttrValue();
        }

    }

    // Create a new attribute value
    public function createNewAttrValue()
    {
        // Prepare the attr value data
        $attrValueData = [
            'option_value'    => ucfirst($this->attr_value),
            'filter_option_id'       => $this->attr_id,
        ];

        // create attr values
        FilterOptionValue::create($attrValueData);

        $this->emit('success', 'Filter option created successfully.');
        $this->emit('close_modal', $this->attr_id);
        $this->emit('refreshList');

        // Reset form fields
        $this->resetForm();
        $this->loadAttributesValues() ;
    }

    // update the attr value
    public function updateAttrValue($id)
    {
        $attrValue = FilterOptionValue::findOrFail($id);
        $this->edit_mode = true;
        $this->value_id  = $attrValue->id;
        $this->attr_value = $attrValue->option_value;
        $this->fill($attrValue->toArray());

    }

    // Update an existing attr value
    private function updateExistingAttrValue()
    {
        $attrValue = FilterOptionValue::findOrFail($this->value_id);
        $attrValue->option_value  = ucfirst($this->attr_value);

        $attrValue->save();
        $this->emit('success', 'Filter option updated successfully.');
        $this->emit('close_edit_modal', $this->value_id);

        $this->loadAttributesValues() ;
    }

    // Delete a attr value
    public function deleteValue($id)
    {
        $attrValue = FilterOptionValue::findOrFail($id);
        // Delete the brand
        $attrValue->delete();

        // Emit success message and reset the form
        $this->emit('info', 'Filter option was deleted.');
        $this->emit('refreshList');
        $this->resetForm();

        $this->loadAttributesValues() ;

    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['attr_value']);
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.filter.filter-value');
    }
}
