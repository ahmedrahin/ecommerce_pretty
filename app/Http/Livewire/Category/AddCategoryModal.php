<?php

namespace App\Http\Livewire\Category;

use App\Http\Controllers\Apps\SubsubcategoryController;
use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;
use Illuminate\Support\Facades\Cache;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class AddCategoryModal extends Component
{
    use WithFileUploads;

    // Properties for storing category data
    public $category_id;
    public $name;
    public $status = 1;
    public $image;
    public $description;
    public $current_image;
    public $edit_mode = false;

    // Cache key for categories
    private $cacheKey;

    // Event listeners
    protected $listeners = [
        'update_category'       => 'updateCategory',
        'delete_category'       => 'delete',
        'open_add_modal'        => 'openAddModal',
        'update_status'         => 'updateStatus',
        'delete_subcategory'    => 'deleteSubcategory',
        'update_featured' => 'updateFeatured'
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.category');
    }

    public function updateFeatured($id, $value)
    {
        $value = $value == 1 ? 1 : 0;

        $category = Category::find($id);

        if ($category) {
            $category->featured = $value;
            $category->save();

            session()->flash('success', 'Featured status updated.');
        } else {
            session()->flash('error', 'Category not found.');
        }

        $this->refreshCache();
    }


    // Render the component view
    public function render()
    {
        return view('livewire.category.add-category-modal');
    }

    // Handle form submission
    public function submit()
    {
        // Validation rules
        $rules = [
            'name' => 'required|unique:categories,name',
            'description' => 'nullable',
            'image'  => 'nullable|mimes:jpeg,png|max:2048'
        ];

        // Custom messages
        $messages = [
            'image.mimes' => 'The image must be a file of type: jpeg, png.',
            'image.max'   => 'The image size must not exceed 2MB.'
        ];

        // If editing an existing category, exclude the current category from the unique check
        if ($this->edit_mode) {
            $rules['name'] = 'required|unique:categories,name,' . $this->category_id;
        }

        // Validate form input
        $this->validate($rules, $messages);

        // Check if we are in edit mode
        if ($this->edit_mode) {
            $this->updateExistingCategory();
        } else {
            $this->createNewCategory();
        }

        // Reset the form
        $this->resetForm();
    }

    // Update an existing category
    private function updateExistingCategory()
    {
        // Find the category by ID
        $category = Category::findOrFail($this->category_id);

        $category->name   = $this->name;
        $category->description   = $this->description;
        $category->status = $this->status;

        if ($this->image) {
            // Delete the old image if it exists
            if ($category->image) {
                Storage::disk('real_public')->delete($category->image);
            }
            $category->image = $this->image->store('uploads/categorize', 'real_public');
        } elseif ($this->current_image === null) {
            if ($category->image) {
                Storage::disk('real_public')->delete($category->image);
            }
            $category->image = null;
        }

        $category->save();

        // Emit success message
        $this->emit('success', __('Category updated successfully.'));
        $this->refreshCache();
    }

    // Create a new category
    private function createNewCategory()
    {
        // Prepare the category data
        $categoryData = [
            'name'   => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ];

        // upload image
        if ($this->image) {
            $thisImage = $this->image;
            $imageName = time() . '_' . $thisImage->getClientOriginalName();
            $path = $thisImage->storeAs('uploads/categorize', $imageName, 'real_public');

            // Store the path in the database
            $categoryData['image'] = 'uploads/categorize/' . $imageName;
        }

        // Create a new category with the provided details
        Category::create($categoryData);

        // Emit success message
        $this->emit('success', __('Category created successfully.'));
        $this->refreshCache();

        // Reset form fields
        $this->resetForm();
    }

    // Load category data for editing
    public function updateCategory($id)
    {
        // Find the category by ID
        $category = Category::findOrFail($id);

        // Set edit mode and populate form fields
        $this->edit_mode = true;
        $this->name = $category->name;
        $this->category_id = $category->id;
        $this->description = $category->description;
        $this->current_image = $category->image;
        $this->status = $category->status;
    }

    //update status
    public function updateStatus($id, $status)
    {
        $category = Category::findOrFail($id);
        $category->update(['status' => $status]);
        $subCategories = Subcategory::where('category_id', $category->id)->get();

        //change status also the subcategories and subsubcategories
        foreach ($subCategories as $subCategory) {
            $subCategory->update(['status' => $status]);

            $sub_subCategories = Subsubcategory::where('subcategory_id', $subCategory->id)->get();
            foreach ($sub_subCategories as $sub_subCategory) {
                $sub_subCategory->update(['status' => $status]);
            }
        }


        $message = $status == 0 ? "{$category->name} is inactive" : "{$category->name} is active";
        $type = $status == 0 ? 'info' : 'success';

        // Emit success message
        $this->emit($type, $message);

        $this->refreshCache();
    }

    // Delete a category
    public function delete($id)
    {
        // Delete the category by ID
        $category = Category::find($id);

        if ($category->image) {
            Storage::disk('real_public')->delete($category->image);
            $category->update(['image' => null]);
        }
        
        // Now delete the category
        $category->delete();

        // Emit success message and reset the form
        $this->emit('info', __('Category was deleted.'));
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
        $this->reset(['name', 'category_id', 'description', 'status', 'image', 'current_image']);
    }

    // Method to remove the image
    public function removeImage()
    {
        $this->image   = null;
        if ($this->current_image) {
            $this->current_image = null;
        }
    }

    // Refresh the cache
    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Category::orderBy('id', 'desc')->get();
        });

        Cache::forget(config('dbcachekey.menu_category'));
        Cache::rememberForever(config('dbcachekey.menu_category'), function () {
            return Category::orderBy('id', 'desc')->where('status', 1)->where('featured',1)->get();
        });
    }

    // Method to open the add modal and reset the form
    public function openAddModal()
    {
        $this->resetForm();
    }

    // delete subcategory
    public function deleteSubcategory($id)
    {
        $getSubCategory = Subcategory::find($id);
        $getSubCategory->delete();
        // Emit success message and reset the form
        $this->emit('delSucCat', __('SubCategory was deleted.'));
    }
}
