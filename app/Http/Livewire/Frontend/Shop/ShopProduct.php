<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class ShopProduct extends Component
{
    public $productId;
    public $quantity = 1;
    public $selectedAttributes = [];

    public $isInWishlist = false;
     protected $listeners = [
        'wishlistUpdated' => 'checkWishlistStatus',
    ];


    public function mount($productId)
    {
        $this->productId = $productId;
        $this->checkWishlistStatus();

        Product::where('status', 3)
        ->where('publish_at', '<=', now())
        ->update(['status' => 1, 'publish_at' => null]);
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

    public function addToCart()
    {
        $product = Product::with('productStock.attributeOptions.attribute')->find($this->productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        // Validate required attributes based on stock
        $requiredAttributes = [];

        foreach ($product->productStock as $stock) {
            foreach ($stock->attributeOptions as $option) {
                $attrName = $option->attribute->attr_name;
                $requiredAttributes[$attrName] = true;
            }
        }

        // Check if all required attributes are selected
        foreach (array_keys($requiredAttributes) as $attrName) {
            if (empty($this->selectedAttributes[$attrName])) {
                $this->attributeErrors[$attrName] = "Please select $attrName";
            }
        }

        if (!empty($this->attributeErrors)) {
            $this->emit('error', 'Please select all required options.');
            return;
        }

        // Validate quantity
        if ($this->quantity <= 0 || !is_numeric($this->quantity)) {
            $this->emit('error', 'Invalid product quantity.');
            return;
        }

        // Build cart key dynamically
        $cart = session()->get('cart', []);

        $cartKey = "{$this->productId}";
        foreach ($this->selectedAttributes as $key => $value) {
            $cartKey .= "-{$key}:{$value}";
        }

        $existingQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newTotalQuantity = $existingQuantity + $this->quantity;

        if ($newTotalQuantity > $product->quantity) {
            $this->emit('error', "You have exceeded available stock for {$product->name}. Only {$product->quantity} available.");
            return;
        }

        // Store in cart
        $cart[$cartKey] = [
            'product_id' => $this->productId,
            'quantity' => $newTotalQuantity,
            'attributes' => $this->selectedAttributes,
            'added_at' => now(),
        ];

        session()->put('cart', $cart);
        session()->forget('applied_coupon');
        $this->emit('success', 'Product added to cart.');
        $this->emit('cartUpdated');
        $this->emit('cartAdded');
        // dd($cart);
    }

    public function toggleWishlist($productId)
    {
        $query = Wishlist::query();

        if (Auth::check()) {
            $query->where('user_id', Auth::id())
                ->where('product_id', $productId);
        } else {
            $query->where('session_id', session()->getId())
                ->where('product_id', $productId);
        }

        $wishlistItem = $query->first();

        if ($wishlistItem) {
            // Remove from wishlist
            $wishlistItem->delete();
            $this->emit('info', 'Item removed from your wishlist.');
        } else {
            // Add to wishlist
            if (Auth::check()) {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                ]);
            } else {
                Wishlist::create([
                    'session_id' => session()->getId(),
                    'product_id' => $productId,
                ]);
            }
            $this->emit('success', 'Item successfully added to your wishlist!');
        }

        $this->emit('wishlistUpdated');
    }


    public function render()
    {
        $product = Product::with('productStock')->find($this->productId);
        return view('livewire.frontend.shop.shop-product', compact('product'));
    }

}
