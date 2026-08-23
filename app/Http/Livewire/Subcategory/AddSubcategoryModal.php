<?php

namespace App\Http\Livewire\Subcategory;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AddSubcategoryModal extends Component
{
    use WithFileUploads;

    public $name;
    public $categories;
    public $category_id;
    public $subcategory_id;
    public $status = 1;
    public $image;
    public $description;
    public $current_image;
    public $edit_mode = false;

    private $cacheKey;

    protected $listeners = [
        'update_subcategory' => 'updateSubcategory',
        'categoryId' => 'setCategoryId',
        'delete_subcategory' => 'delete',
        'open_add_modal' => 'openAddModal',
        'update_status'         => 'updateStatus',
        'update_featured' => 'updateFeatured'
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.subcategory');
    }

     public function updateFeatured($id, $value)
    {
        $value = $value == 1 ? 1 : 0;

        $category = Subcategory::find($id);

        if ($category) {
            $category->featured = $value;
            $category->save();

            session()->flash('success', 'Featured status updated.');
        } else {
            session()->flash('error', 'Category not found.');
        }
    }

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.subcategory.add-subcategory-modal');
    }

    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;
    }
    
    public function submit()
    {
        // Validation rules
        $rules = [
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
        ];

        // If editing an existing subcategory, exclude the current subcategory from the unique check
        if ($this->edit_mode) {
            $rules['name'] = [
                'required',
                Rule::unique('subcategories')->where(function ($query) {
                    return $query->where('name', $this->name)
                                ->where('category_id', $this->category_id)
                                ->where('id', '!=', $this->subcategory_id);
                })
            ];
        } else {
            $rules['name'] = [
                'required',
                Rule::unique('subcategories')->where(function ($query) {
                    return $query->where('name', $this->name)
                                ->where('category_id', $this->category_id);
                })
            ];
        }
        
        // Validate form input
        $this->validate($rules);

        if ($this->edit_mode) {
            $this->updateExistingSubcategory();
        } else {
            $this->createNewSubcategory();
        }

        $this->resetForm();
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The category field is required.',
            'category_id.exists' => 'The selected category is invalid.',
        ];
    }

    private function updateExistingSubcategory()
    {
        $subcategory = Subcategory::findOrFail($this->subcategory_id);

        $subcategory->name          = $this->name;
        $subcategory->description   = $this->description;
        $subcategory->status        = $this->status;
        $subcategory->category_id   = $this->category_id;

        if ($this->image) {
            // Delete the old image if it exists
            if ($subcategory->image) {
                Storage::disk('real_public')->delete($subcategory->image);
            }
            $subcategory->image = $this->image->store('uploads/categorize', 'real_public');
        } elseif ($this->current_image === null) {
            if ($subcategory->image) {
                Storage::disk('real_public')->delete($subcategory->image);
            }
            $subcategory->image = null;
        }

        $subcategory->save();

        $this->emit('success', __('Subcategory updated successfully.'));
        $this->refreshCache();
    }

    private function createNewSubcategory()
    {
        // Prepare the category data
        $categoryData = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'category_id' => $this->category_id,
        ];

        // upload image
        if( $this->image ){
            $thisImage = $this->image;
            $imageName = time() . '_' . $thisImage->getClientOriginalName();
            $path = $thisImage->storeAs('uploads/categorize', $imageName, 'real_public');
        
            // Store the path in the database
            $categoryData['image'] = 'uploads/categorize/' . $imageName;
        }

        // Create a new category with the provided details
        Subcategory::create($categoryData);

        $this->emit('success', __('Subcategory created successfully.'));
        $this->refreshCache();
    }

    public function updateSubcategory($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $this->edit_mode = true;
        $this->subcategory_id = $subcategory->id;
        $this->category_id = $subcategory->category_id;
        $this->description = $subcategory->description;
        $this->current_image = $subcategory->image ?? null;
        $this->fill($subcategory->toArray());
        $this->image = '';
        $this->refreshCache();
    }

     //update status
     public function updateStatus($id, $status)
     {
         $category = Subcategory::findOrFail($id);
         $category->update(['status' => $status]);
         $subCategories = Subsubcategory::where('subcategory_id', $category->id)->get();
 
         //change status also subsubcategories
         foreach( $subCategories as $subCategory ){
             $subCategory->update(['status' => $status]);
         }
 
         $message = $status == 0 ? "{$category->name} is inactive" : "{$category->name} is active";
         $type = $status == 0 ? 'info' : 'success';
 
         // Emit success message
         $this->emit($type, $message);
         $this->refreshCache();
     }

    public function delete($id)
    {
        $subcategory = Subcategory::find($id);

        // Delete the category image if it exists
        // if ($subcategory && $subcategory->image) {
        //     Storage::disk('real_public')->delete($subcategory->image);
        //     $subcategory->update(['image' => null]);
        // }

        Subcategory::destroy($id);

        $this->emit('info', __('Subcategory was deleted.'));
        $this->resetForm();

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
        $this->reset(['name', 'subcategory_id', 'category_id', 'status', 'image', 'description', 'current_image']);
    }

    // Method to remove the image
    public function removeImage()
    {
        $this->image   = null;
        if ($this->current_image) {
            $this->current_image = null;
        }
    }

    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Subcategory::orderBy('id', 'desc')->get();
        });
    }

    public function openAddModal()
    {
        $this->resetForm();
    }
}