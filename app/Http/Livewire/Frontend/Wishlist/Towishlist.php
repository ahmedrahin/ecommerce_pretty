<?php

namespace App\Http\Livewire\Frontend\Wishlist;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class Towishlist extends Component
{   
    public $productId;
    public $isInWishlist = false;

    protected $listeners = [
        'wishlistUpdated' => 'checkWishlistStatus',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkWishlistStatus();
    }

    public function checkWishlistStatus()
    {
        $query = Wishlist::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        $this->isInWishlist = $query->where('product_id', $this->productId)->exists();
    }

    public function render()
    {
        return view('livewire.frontend.wishlist.towishlist');
    }
}
