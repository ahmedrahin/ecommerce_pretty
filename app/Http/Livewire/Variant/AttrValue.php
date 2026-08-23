<?php

namespace App\Http\Livewire\Variant;

use Livewire\Component;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Validation\Rule;

class AttrValue extends Component
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
        $this->attributeValues = Attribute::with(['attributeValue' => function($query) {
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
                Rule::unique('attribute_values')->where(function ($query) {
                    return $query->where('attr_id', $this->attr_id);
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
                    Rule::unique('attribute_values')->where(function ($query) {
                        return $query->where('attr_id', $this->attr_id);
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
            'attr_value'    => ucfirst($this->attr_value),
            'attr_id'       => $this->attr_id,
            'option'        => $this->option,
        ];

        // create attr values
        AttributeValue::create($attrValueData);

        $this->emit('success', __('Attribute value created successfully.'));
        $this->emit('close_modal', $this->attr_id);
        $this->emit('refreshList');

        // Reset form fields
        $this->resetForm();
        $this->loadAttributesValues() ;
    }

    // update the attr value
    public function updateAttrValue($id)
    {   
        $attrValue = AttributeValue::findOrFail($id);
        $this->edit_mode = true;
        $this->value_id  = $attrValue->id;
        $this->fill($attrValue->toArray());
        
    }

    // Update an existing attr value
    private function updateExistingAttrValue()
    {
        $attrValue = AttributeValue::findOrFail($this->value_id);
        $attrValue->attr_value  = ucfirst($this->attr_value);
        $attrValue->option      = $this->option;
 
        $attrValue->save();
        $this->emit('success', __('Attribute value updated successfully.'));
        $this->emit('close_edit_modal', $this->value_id);

        $this->loadAttributesValues() ;
    }

    // Delete a attr value
    public function deleteValue($id)
    {
        // Find the brand by ID
        $attrValue = AttributeValue::findOrFail($id);
        // Delete the brand
        $attrValue->delete();

        // Emit success message and reset the form
        $this->emit('info', __('Attribute value was deleted.'));
        $this->emit('refreshList');
        $this->resetForm();

        $this->loadAttributesValues() ;
       
    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['attr_value', 'option']);
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    { 
        return view('livewire.variant.attr-value');
    }
}