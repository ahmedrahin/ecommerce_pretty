<?php

namespace App\Http\Livewire\Shipping;
use App\Models\District;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class AddDistrict extends Component
{
    // Properties for storing district data
    public $district_id;
    public $name;
    public $status = 1;
    public $edit_mode = false;

    // Cache key for districts
    private $cacheKey;

    // Event listeners
    protected $listeners = [
        'update_district'      => 'updatedistrict',
        'delete_district'      => 'delete',
        'open_add_modal'       => 'openAddModal',
        'update_status'        => 'updateStatus',
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.shipping_district');
    }

    // Handle form submission
    public function submit()
    {
        // Validation rules
        $rules = [
            'name'  => 'required|unique:districts,name',
        ];

        if ($this->edit_mode) {
            $rules['name'] = 'required|unique:districts,name,' . $this->district_id;
        }

        // Validate form input
        $this->validate($rules);

        // Check if we are in edit mode
        if ($this->edit_mode) {
            $this->updateExistingdistrict();
        } else {
            $this->createNewdistrict();
        }

        // Reset the form
        $this->resetForm();
    }

    // Create a new district
    public function createNewdistrict()
    {
        // Prepare the district data
        $districtData = [
            'name' => $this->name,
            'status' => $this->status,
        ];

        // Create the district
        District::create($districtData);

        // Emit success message
        $this->emit('success', __('District created successfully.'));
        $this->refreshCache();

        // Reset form fields
        $this->resetForm();
    }

    // update the district
    public function updatedistrict($id)
    {
        $district = district::findOrFail($id);

        $this->edit_mode = true;
        $this->district_id = $district->id;
        $this->fill($district->toArray());

        $this->refreshCache();
    }

    // Update an existing district
    private function updateExistingdistrict()
    {
        $district = District::findOrFail($this->district_id);

        $district->name = $this->name;
        $district->status = $this->status;

        $district->save();

        $this->emit('success', __('District updated successfully.'));
        $this->refreshCache();
    }

    //update status
    public function updateStatus($id, $status)
    {
        $district = District::findOrFail($id);
        $district->update(['status' => $status]);

        $message = $status == 0 ? "{$district->name} is inactive" : "{$district->name} is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);
        $this->refreshCache();
    }

    // Delete a district
    public function delete($id)
    {
        // Find the district by ID
        $district = District::findOrFail($id);

        // Delete the district
        $district->delete();

        // Emit success message and reset the form
        $this->emit('info', __('District was deleted.'));
        $this->resetForm();

        $this->refreshCache();
    }

    // Handle component hydration
    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    // Reset form fields
    private function resetForm()
    {
        // Reset edit mode and form fields
        $this->edit_mode = false;
        $this->reset(['name', 'district_id', 'status']);
    }

    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return district::orderBy('id', 'desc')->get();
        });
    }

    // Method to open the add modal and reset the form
    public function openAddModal()
    {
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.shipping.add-district');
    }
}
