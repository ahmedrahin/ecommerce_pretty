<?php

namespace App\Http\Livewire\Frontend\Home;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpecialProduct extends Component
{
    public $take = 12;

    public function render()
    {
        $offerProduct = Product::activeProducts()
                        ->orderBy('id', 'desc')
                        ->where('discount_option', '!=', 1)
                        ->take($this->take)
                        ->get();

        $featuredProducts = Product::activeProducts()
                                ->orderBy('id', 'desc')
                                ->where('is_featured', 1)
                                ->take($this->take)
                                ->get();

        return view('livewire.frontend.home.special-product', compact('offerProduct', 'featuredProducts'));
    }
}
