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
    public $wishlist = [];
    public $take = 10;

    protected $listeners = [
        'wishlistUpdated' => 'loadWishlist'
    ];

    public function mount(){
        $this->loadWishlist();
    }

    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlist = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        } else {
            $sessionId = session()->getId();
            $this->wishlist = Wishlist::where('session_id', $sessionId)->pluck('product_id')->toArray();
        }
    }

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
