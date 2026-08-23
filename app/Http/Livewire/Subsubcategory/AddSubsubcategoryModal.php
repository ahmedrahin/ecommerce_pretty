<?php

namespace App\Http\Livewire\Subsubcategory;

use Livewire\Component;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class AddSubsubcategoryModal extends Component
{
    public $id;
    public $name;
    public $subcategories;
    public $subcategory_id;
    public $edit_mode = false;
    public $status = 1;

    private $cacheKey;

    protected $listeners = [
        'modal.show.subsubcategoryId' => 'setSubcategoryId',
        'update_subsubcategory' => 'updateSubsubcategory',
        'delete_subsubcategory' => 'delete',
        'open_add_modal' => 'openAddModal',
        'update_status' => 'updateStatus',
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.subsubcategory');
    }

    public function mount()
    {
        $this->subcategories = Subcategory::with('category')->orderBy('name', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.subsubcategory.add-subsubcategory-modal');
    }

    public function setSubcategoryId($subcategoryId)
    {
        $this->subcategory_id = $subcategoryId;
    }
    
    public function submit()
    {
        // Validation rules
        $rules = [
            'name' => 'required',
            'subcategory_id' => 'required|exists:subcategories,id',
        ];

        // If editing an existing subsubcategory, exclude the current subsubcategory from the unique check
        if ($this->edit_mode) {
            $rules['name'] = [
                'required',
                Rule::unique('subsubcategories')->where(function ($query) {
                    return $query->where('name', $this->name)
                                ->where('subcategory_id', $this->subcategory_id)
                                ->where('id', '!=', $this->id);
                })
            ];
        } else {
            $rules['name'] = [
                'required',
                Rule::unique('subsubcategories')->where(function ($query) {
                    return $query->where('name', $this->name)
                                ->where('subcategory_id', $this->subcategory_id);
                })
            ];
        }
        
        // Validate form input
        $this->validate($rules);
        
        if ($this->edit_mode) {
            $this->updateExistingSubsubcategory();
        } else {
            $this->createNewSubsubcategory();
        }

        $this->resetForm();
    }

    private function updateExistingSubsubcategory()
    {
        $subsubcategory = Subsubcategory::findOrFail($this->id);

        $subsubcategory->update([
            'name' => $this->name,
            'subcategory_id' => $this->subcategory_id,
            'status' => $this->status,
        ]);

        $this->emit('success', __('Subsubcategory updated successfully.'));
        $this->refreshCache();
    }

    private function createNewSubsubcategory()
    {
        Subsubcategory::create([
            'name' => $this->name,
            'subcategory_id' => $this->subcategory_id,
            'status' => $this->status,
        ]);

        $this->emit('success', __('Subsubcategory created successfully.'));
        $this->refreshCache();
    }

    public function updateSubsubcategory($id)
    {
        $subsubcategory = Subsubcategory::findOrFail($id);
        $this->edit_mode = true;
        $this->id = $subsubcategory->id;
        $this->subcategory_id = $subsubcategory->subcategory_id;
        $this->fill($subsubcategory->toArray());

        $this->refreshCache();
    }

    public function delete($id)
    {
        Subsubcategory::destroy($id);

        $this->emit('success', __('Subsubcategory deleted successfully.'));
        $this->resetForm();

        $this->refreshCache();
    }

     //update status
     public function updateStatus($id, $status)
     {
         $category = Subsubcategory::findOrFail($id);
         $category->update(['status' => $status]);
 
         $message = $status == 0 ? "{$category->name} is inactive" : "{$category->name} is active";
         $type = $status == 0 ? 'info' : 'success';
 
         // Emit success message
         $this->emit($type, $message);
         $this->refreshCache();
     }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function resetForm()
    {
        $this->edit_mode = false;
        $this->reset(['name', 'id', 'subcategory_id', 'status']);
    }

    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Subsubcategory::orderBy('id', 'desc')->get();
        });
    }

    public function openAddModal()
    {
        $this->resetForm();
    }
}