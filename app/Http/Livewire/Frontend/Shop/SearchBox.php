<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class SearchBox extends Component
{
    public $searchQuery = '';
    public $activeTab = 'products';
    public $products = [];
    public $filteredCategories = [];

    protected $queryString = [
        'searchQuery' => ['except' => '']
    ];


    public function updatedSearchQuery()
    {
        $this->search();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->search();
    }

    public function search()
    {
        if (empty($this->searchQuery)) {
            $this->products = [];
            $this->filteredCategories = [];
            return;
        }

        // Always search both
        $this->searchProducts();
        $this->searchCategories();
    }


    public function searchProducts()
    {
        $this->products = Product::activeProducts()
            ->where(function($query) {
                $query->where('name', 'like', '%'.$this->searchQuery.'%')->orWhere('sku_code', 'like', '%'.$this->searchQuery.'%')
                      ->orWhereHas('tags', function($q) {
                          $q->where('name', 'like', '%'.$this->searchQuery.'%');
                      });
            })
            ->orderBy('name')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function searchCategories()
    {
        $this->filteredCategories = Category::where('status', 1)
            ->where('name', 'like', '%'.$this->searchQuery.'%')
            ->withCount(['product' => function($query) {
                $query->whereIn('status', [1, 3])
                    ->where(function($q) {
                        $q->whereNull('publish_at')
                          ->orWhere('publish_at', '<=', Carbon::now());
                    })
                    ->where(function($q) {
                        $q->whereNull('expire_date')
                          ->orWhere('expire_date', '>', Carbon::now());
                    });
            }])
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    public function performSearch()
    {
        if (empty($this->searchQuery)) return;

        return redirect()->route('search.products', ['query' => $this->searchQuery]);
    }

    public function render()
    {
        return view('livewire.frontend.shop.search-box');
    }
}
