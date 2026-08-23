<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;

class SearchBoxMobile extends Component
{
    public $searchQuery = ''; 
    public $products = [];

    public function updatedSearchQuery()
    {
        $this->products = Product::whereIn('status', [1, 3])
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>', Carbon::now());
            })
            ->when($this->searchQuery, function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhereHas('tags', function ($q) { 
                        $q->where('name', 'like', '%' . $this->searchQuery . '%');
                    });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        
        $this->emit('serchUpdate');
    }
    
    public function search()
    {
        return redirect()->to('/shop?query=' . urlencode($this->searchQuery));
    }

    public function render()
    {
        return view('livewire.frontend.shop.search-box-mobile');
    }
}
