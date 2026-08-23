<?php

namespace App\Http\Livewire\Frontend\Wishlist;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class CountWishlist extends Component
{
    public $wishlistCount = 0;

    public function mount()
    {
        $this->updateWishlistCount();
    }

    protected $listeners = [
        'wishlistUpdated' => 'updateWishlistCount',
    ];

    public function updateWishlistCount()
    {
        if (Auth::check()) {
            $this->wishlistCount = Wishlist::where('user_id', Auth::id())->count();
        } else {
            $sessionId = session()->getId();
            $this->wishlistCount = Wishlist::where('session_id', $sessionId)->count();
        }
    }

    public function render()
    {
        return view('livewire.frontend.wishlist.count-wishlist');
    }
}
