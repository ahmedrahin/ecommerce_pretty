<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Category;

class ProductFilter extends Component
{
    public $selectedCategories = [];
    public $selectedSubCategories = [];
    public $selectedSubSubCategories = []; // New property for subsubcategories
    public $selectedCollections = [];
    public $searchQuery = '';

    protected $queryString = [
        'selectedCategories' => ['as' => 'categories', 'except' => []],
        'selectedSubCategories' => ['as' => 'subcategories', 'except' => []],
        'selectedSubSubCategories' => ['as' => 'subsubcategories', 'except' => []],
        'selectedCollections' => ['as' => 'collections', 'except' => []],
        'searchQuery' => ['as' => 'query', 'except' => ''],
    ];

    protected $listeners = [
        'categoryTagRemoved' => 'removeFromSelectedCategories',
        'subcategoryTagRemoved' => 'removeFromSelectedSubCategories',
        'subsubcategoryTagRemoved' => 'removeFromSelectedSubSubCategories',
        'collectionTagRemoved' => 'removeFromSelectedCollections',
    ];

    public function updatedSelectedCategories()
    {
        $this->emit('filterUpdated', $this->selectedCategories);
    }

    public function updatedSelectedSubCategories()
    {
        $this->emit('subcategoryFilterUpdated', $this->selectedSubCategories);
    }

    public function updatedSelectedSubSubCategories() // Handle subsubcategories
    {
        $this->emit('subsubcategoryFilterUpdated', $this->selectedSubSubCategories);
    }

    public function updatedSearchQuery()
    {
        $this->emit('searchUpdated', $this->searchQuery);
    }

    public function updatedSelectedCollections()
    {
        $this->emit('collectionFilterUpdated', $this->selectedCollections);
    }

    public function removeFromSelectedCategories($categoryId)
    {
        $this->selectedCategories = array_values(
            array_diff($this->selectedCategories, [$categoryId])
        );
        $this->emit('filterUpdated', $this->selectedCategories);
    }

    public function removeFromSelectedSubCategories($subcategoryId)
    {
        $this->selectedSubCategories = array_values(
            array_diff($this->selectedSubCategories, [$subcategoryId])
        );
        $this->emit('subcategoryFilterUpdated', $this->selectedSubCategories);
    }

    public function removeFromSelectedSubSubCategories($subsubcategoryId) // Remove subsubcategories
    {
        $this->selectedSubSubCategories = array_values(
            array_diff($this->selectedSubSubCategories, [$subsubcategoryId])
        );
        $this->emit('subsubcategoryFilterUpdated', $this->selectedSubSubCategories);
    }

    public function removeFromSelectedCollections($collectionId)
    {
        $this->selectedCollections = array_values(
            array_diff($this->selectedCollections, [$collectionId])
        );
        $this->emit('collectionFilterUpdated', $this->selectedCollections);
    }

    public function render()
    {
        $categories = Category::where('status', 1)
            ->with([
                'subcategories' => function ($query) {
                    $query->where('status', 1)
                          ->with([
                              'subsubcategories' => function ($query) {
                                  $query->where('status', 1); // Load subsubcategories
                              }
                          ]);
                }
            ])
            ->orderBy('id', 'asc')
            ->get();

        return view('livewire.frontend.shop.product-filter', compact('categories'));
    }
}
