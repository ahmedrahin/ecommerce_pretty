<?php

namespace App\Http\Livewire\Frontend\Wishlist;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class AddWishlist extends Component
{
    public $productId;

    protected $listeners = [
        'get_id' , 'removeFromWishlist'
    ];

    public function get_id($productId)
    {
        $this->productId = $productId;
        $this->addWishlist();
    }

    public function addWishlist()
    {
        if (Auth::check()) {
            Wishlist::firstOrCreate([
                'user_id' => Auth::id(),
                'product_id' => $this->productId,
            ]);
        } else {
            $sessionId = session()->getId();
            Wishlist::firstOrCreate([
                'session_id' => $sessionId,
                'product_id' => $this->productId,
            ]);
        }

        $this->emit('success', 'Item Successfully added in your wishlist.!');
        $this->emit('wishlistUpdated');
    }

    public function removeFromWishlist($productId)
    {
        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $sessionId = session()->getId();
            Wishlist::where('session_id', $sessionId)
                ->where('product_id', $productId)
                ->delete();
        }

        $this->emit('info', 'Item removed from your wishlist.');
        $this->emit('wishlistUpdated');
    }

    public function checkIfWishlisted()
    {
        if (Auth::check()) {
            $this->isWishlisted = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $this->productId)
                ->exists();
        } else {
            $sessionId = session()->getId();
            $this->isWishlisted = Wishlist::where('session_id', $sessionId)
                ->where('product_id', $this->productId)
                ->exists();
        }
    }

    public function render()
    {
        return view('livewire.frontend.wishlist.add-wishlist');
    }
}
