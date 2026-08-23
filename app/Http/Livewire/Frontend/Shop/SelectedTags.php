<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subsubcategory;

class SelectedTags extends Component
{
    public $selectedCategoryIds = [];
    public $selectedSubcategoryIds = [];
    public $selectedSubsubcategoryIds = [];
    public $selectedCategoryNames = [];

    protected $listeners = [
        'filterUpdated' => 'updateCategoryTags',
        'subcategoryFilterUpdated' => 'updateSubcategoryTags',
        'subsubcategoryFilterUpdated' => 'updateSubsubcategoryTags'
    ];

    public function mount($initialCategories = [], $initialSubcategories = [], $initialSubsubcategories = [])
    {
        $this->selectedCategoryIds = $initialCategories;
        $this->selectedSubcategoryIds = $initialSubcategories;
        $this->selectedSubsubcategoryIds = $initialSubsubcategories;
        $this->updateTags();
    }

    public function updateTags()
    {
        // Initialize selectedCategoryNames as an empty array
        $this->selectedCategoryNames = [];
    
        // Fetch categories, subcategories, and their sub-subcategories
        $categoriesWithRelations = Category::whereIn('id', $this->selectedCategoryIds)
            ->with(['subcategories' => function ($query) {
                $query->whereIn('id', $this->selectedSubcategoryIds)
                    ->with(['subsubcategories' => function ($query) {
                        $query->whereIn('id', $this->selectedSubsubcategoryIds);
                    }]);
            }])
            ->get();
    
        // Add tags for categories, subcategories, and sub-subcategories
        foreach ($categoriesWithRelations as $category) {
            // Add the category itself if selected
            if (in_array($category->id, $this->selectedCategoryIds)) {
                $this->selectedCategoryNames[] = $category->name;
            }
    
            foreach ($category->subcategories as $subcategory) {
                // Add subcategory tag
                if (in_array($subcategory->id, $this->selectedSubcategoryIds)) {
                    $this->selectedCategoryNames[] = $category->name . ' > ' . $subcategory->name;
                }
    
                foreach ($subcategory->subsubcategories as $subsubcategory) {
                    // Add sub-subcategory tag
                    if (in_array($subsubcategory->id, $this->selectedSubsubcategoryIds)) {
                        $this->selectedCategoryNames[] = $category->name . ' > ' . $subcategory->name . ' > ' . $subsubcategory->name;
                    }
                }
            }
        }
    
        // Handle subcategories selected without parent categories
        $standaloneSubcategories = Subcategory::whereIn('id', $this->selectedSubcategoryIds)
            ->whereDoesntHave('category', function ($query) {
                $query->whereIn('id', $this->selectedCategoryIds);
            })
            ->with(['subsubcategories' => function ($query) {
                $query->whereIn('id', $this->selectedSubsubcategoryIds);
            }])
            ->get();
    
        foreach ($standaloneSubcategories as $subcategory) {
            $this->selectedCategoryNames[] = $subcategory->category->name . ' > ' . $subcategory->name;
    
            foreach ($subcategory->subsubcategories as $subsubcategory) {
                if (in_array($subsubcategory->id, $this->selectedSubsubcategoryIds)) {
                    $this->selectedCategoryNames[] = $subcategory->category->name . ' > ' . $subcategory->name . ' > ' . $subsubcategory->name;
                }
            }
        }
    
        // Handle sub-subcategories selected without parent subcategories
        $standaloneSubsubcategories = Subsubcategory::whereIn('id', $this->selectedSubsubcategoryIds)
            ->whereDoesntHave('subcategory', function ($query) {
                $query->whereIn('id', $this->selectedSubcategoryIds);
            })
            ->get();
    
        foreach ($standaloneSubsubcategories as $subsubcategory) {
            $this->selectedCategoryNames[] = $subsubcategory->subcategory->category->name . ' > ' . $subsubcategory->subcategory->name . ' > ' . $subsubcategory->name;
        }
    
        // Remove duplicates
        $this->selectedCategoryNames = array_unique($this->selectedCategoryNames);
    }
    

    public function updateCategoryTags($categories)
    {
        $this->selectedCategoryIds = $categories;
        $this->updateTags();
    }

    public function updateSubcategoryTags($subcategories)
    {
        $this->selectedSubcategoryIds = $subcategories;
        $this->updateTags();
    }

    public function updateSubsubcategoryTags($subsubcategories)
    {
        $this->selectedSubsubcategoryIds = $subsubcategories;
        $this->updateTags();
    }

    public function removeCategory($name)
    {
        $levels = explode(' > ', $name);

        if (count($levels) === 3) {
            // Handle sub-subcategory removal
            $subsubcategory = Subsubcategory::where('name', $levels[2])
                ->whereHas('subcategory', function ($query) use ($levels) {
                    $query->where('name', $levels[1])
                        ->whereHas('category', function ($query) use ($levels) {
                            $query->where('name', $levels[0]);
                        });
                })->first();

            if ($subsubcategory) {
                $this->selectedSubsubcategoryIds = array_diff($this->selectedSubsubcategoryIds, [$subsubcategory->id]);
                $this->emit('subsubcategoryTagRemoved', $subsubcategory->id);
            }
        } elseif (count($levels) === 2) {
            // Handle subcategory removal
            $subcategory = Subcategory::where('name', $levels[1])
                ->whereHas('category', function ($query) use ($levels) {
                    $query->where('name', $levels[0]);
                })->first();

            if ($subcategory) {
                $this->selectedSubcategoryIds = array_diff($this->selectedSubcategoryIds, [$subcategory->id]);
                $this->emit('subcategoryTagRemoved', $subcategory->id);
            }
        } else {
            // Handle category removal
            $category = Category::where('name', $name)->first();
            if ($category) {
                $this->selectedCategoryIds = array_diff($this->selectedCategoryIds, [$category->id]);
                $this->emit('categoryTagRemoved', $category->id);
            }
        }

        $this->updateTags();
    }


    public function render()
    {
        return view('livewire.frontend.shop.selected-tags');
    }
}
