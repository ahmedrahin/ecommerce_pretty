<?php

namespace App\Http\Livewire\Frontend\Product;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;


class RelatedProduct extends Component
{
    public $productId;
     public $wishlist = [];
      public $quantity = 1;


      protected $listeners = [
        'wishlistUpdated' => 'loadWishlist',
    ];

    public function mount($id){
        $this->productId = $id;
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

    public function addToCart($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        if ($product->quantity < 1) {
            $this->emit('error', 'This product is out of stock.');
            return;
        }

        if ($this->quantity == 0 || empty($this->quantity)) {
            $this->emit('error', 'Please select a quantity');
            return;
        } elseif (!is_numeric($this->quantity) || $this->quantity <= 0) {
            $this->emit('error', 'Invalid product quantity. Please enter a valid positive quantity');
            return;
        }

        $cart = session()->get('cart', []);
        $cartKey = (string) $productId;

        $existingQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newTotalQuantity = $existingQuantity + $this->quantity;

        if ($newTotalQuantity > $product->quantity) {
            $this->emit('error', "Only {$product->quantity} items available in stock.");
            return;
        }

        if (!isset($cart[$cartKey])) {
            $cart[$cartKey] = [
                'product_id' => $productId,
                'quantity' => $this->quantity,
                'added_at' => now(),
            ];
            $this->emit('success', 'Product added to cart.');
        } else {
            $cart[$cartKey]['quantity'] = $newTotalQuantity;
            $this->emit('success', 'Product quantity updated.');
        }

        session()->put('cart', $cart);
        $this->emit('cartUpdated');
        $this->emit('cartAdded');
    }

    public function render()
    {
        $product = Product::find($this->productId);
        $products = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->whereIn('status', [1, 3])
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '>', Carbon::now());
            })
            ->get();

        return view('livewire.frontend.product.related-product', compact('products'));
    }
}
