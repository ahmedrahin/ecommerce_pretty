<?php

namespace App\Http\Livewire\Frontend\Wishlist;

use Livewire\Component;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;

class WishlistList extends Component
{
    public $wishlistItems = [];

    public function mount()
    {
        $this->loadWishlist();
    }

    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Wishlist::with(['product' => function ($query) {
                $query->whereIn('status', [1, 3])
                    ->where(function ($q) {
                        $q->whereNull('publish_at')
                          ->orWhere('publish_at', '<=', Carbon::now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expire_date')
                          ->orWhere('expire_date', '>', Carbon::now());
                    });
            }])
            ->where('user_id', Auth::id())
            ->get();
        } else {
            $sessionId = session()->getId();
            $this->wishlistItems = Wishlist::with(['product' => function ($query) {
                $query->whereIn('status', [1, 3])
                    ->where(function ($q) {
                        $q->whereNull('publish_at')
                          ->orWhere('publish_at', '<=', Carbon::now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expire_date')
                          ->orWhere('expire_date', '>', Carbon::now());
                    });
            }])
            ->where('session_id', $sessionId)
            ->get();
        }
    }    

    public function removeFromWishlist($wishlistId)
    {
        Wishlist::find($wishlistId)->delete();
        $this->loadWishlist(); 
        $this->emit('wishlistUpdated');
        $this->emit('info', 'The item removed from you wishlist.');
    }

   public function addToCart($productId)
{
    $product = Product::find($productId);

    if (!$product) {
        $this->emit('error', 'Product not found.');
        return;
    }

    // Default quantity (or change if you allow custom qty)
    $quantity = 1;

    // Get existing cart
    $cart = session()->get('cart', []);

    // Use productId as key since there are no variations
    $cartKey = (string) $productId;

    // If product already in cart, increase quantity
    $existingQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
    $newTotalQuantity = $existingQuantity + $quantity;

    // Stock check
    if ($newTotalQuantity > $product->quantity) {
        $this->emit('error', "You have exceeded available stock for {$product->name}. Only {$product->quantity} available.");
        return;
    }

    // Save/Update cart item
    $cart[$cartKey] = [
        'product_id' => $productId,
        'quantity'   => $newTotalQuantity,
        'added_at'   => now(),
    ];

    session()->put('cart', $cart);

    $this->emit('success', 'Product added to cart.');
    $this->emit('cartUpdated');
    $this->emit('cartAdded');
}


    public function render()
    {
        return view('livewire.frontend.wishlist.wishlist-list', [
            'wishlistItems' => $this->wishlistItems,
        ]);
    }
}
