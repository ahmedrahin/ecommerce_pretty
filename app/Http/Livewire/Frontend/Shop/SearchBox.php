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

    // protected $queryString = [
    //     'searchQuery' => ['except' => '']
    // ];


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
        $query = $this->searchQuery;

        $this->products = Product::activeProducts()
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('sku_code', 'LIKE', "%{$query}%")
                ->orWhereHas('tags', function ($t) use ($query) {
                    $t->where('name', 'LIKE', "%{$query}%");
                });
            })
            ->select('id', 'name', 'sku_code', 'thumb_image', 'slug', 'base_price', 'offer_price', 'discount_option')
            ->orderBy('name')
            ->limit(10)
            ->get(); 
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
        $topCategories = Category::withCount('product')
                        ->orderBy('product_count', 'desc')
                        ->where('status',1)
                        ->take(10)
                        ->get();

        return view('livewire.frontend.shop.search-box', compact('topCategories'));
    }
}
