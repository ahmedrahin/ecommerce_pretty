<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Carbon;

class ShoppingCart extends Component
{
    public $cart = [];
    public $quantities = [];

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
        'updateQuantities'
    ];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []);
        // dd($this->cart);
        $now = now();
        $cartUpdated = false;

        // Remove expired items
        foreach ($this->cart as $cartKey => $item) {
            if (!empty($item['added_at']) && $now->diffInHours(Carbon::parse($item['added_at'])) > config('website_settings.cart_session')) {
                unset($this->cart[$cartKey]);
                $cartUpdated = true;
            }
        }

        if ($cartUpdated) {
            session()->put('cart', $this->cart);
            $this->emit('cartUpdated');
        }

        // Filter and enrich valid cart items
        $validCart = [];

        foreach ($this->cart as $cartKey => $item) {
            $productId = explode('-', $cartKey)[0];
            $product = Product::activeProducts()->where('id', $productId)->first();

            if ($product && $product->quantity > 0) {
                $validCart[$cartKey] = $item;

                // Add product info
                $validCart[$cartKey]['name'] = $product->name;
                $validCart[$cartKey]['slug'] = $product->slug;
                $validCart[$cartKey]['offer_price'] = $product->offer_price;
                $validCart[$cartKey]['price'] = $product->base_price;
                $validCart[$cartKey]['image_url'] = $product->thumb_image;
                $validCart[$cartKey]['available_quantity'] = $product->quantity;
                $validCart[$cartKey]['discount_option'] = $product->discount_option;

                $this->quantities[$cartKey] = $item['quantity'] ?? 1;

                // Load attribute info (if exists)
                $validCart[$cartKey]['attributes_info'] = [];

                if (!empty($item['attributes']) && is_array($item['attributes'])) {
                    foreach ($item['attributes'] as $attrName => $attrValue) {
                        $validCart[$cartKey]['attributes_info'][] = [
                            'name' => $attrName,
                            'value' => $attrValue,
                        ];
                    }
                }

            }
        }

        // Update session and component cart
        session()->put('cart', $validCart);
        $this->cart = $validCart;
    }

    public function updateQuantities($cartKey, $quantity)
    {
        // Basic validation of quantity
        if (!$cartKey || !$quantity || !is_numeric($quantity) || $quantity <= 0) {
            $this->emit('error', 'Invalid product quantity. Please enter a valid positive quantity.');
            return;
        }

        // Check if this item exists in the cart
        $cart = session()->get('cart', []);
        if (isset($cart[$cartKey])) {
            $productId = explode('-', $cartKey)[0];
            $product = Product::find($productId);

            // Validate stock availability
            if ($product && $quantity > $product->quantity) {
                $this->emit('error', "We don't have enough stock for {$product->name}");
                return;
            }

            // Update quantity and refresh cart
            $cart[$cartKey]['quantity'] = (int) $quantity;
            session()->put('cart', $cart);
            $this->emit('success', 'Product quantity updated.');
            $this->loadCart();
            $this->emit('cartUpdated');
        } else {
            $this->emit('error', 'Product not found in the cart.');
        }
    }

    public function removeItem($cartKey)
    {
        $cart = session()->get('cart', []);
        unset($cart[$cartKey]);
        session()->put('cart', $cart);
        $this->emit('info', 'The product remove from your cart.');
        $this->emit('cartUpdated');
        session()->forget('applied_coupon');
        $this->loadCart();
    }

    public function getTotalAmount()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['quantity'] * $item['offer_price'];
        }
        return $total;
    }

    public function refreshCart()
    {
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.frontend.cart.shopping-cart');
    }
}
