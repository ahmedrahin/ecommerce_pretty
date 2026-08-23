<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;

class QuickView extends Component
{
    public $on = false ;
    public $productId;
    public $quantity = 1;

    public $selectedSize = null;
    public $selectedColor = null;
    public $sizeError;
    public $colorError;

    protected $listeners = ['updateQuantity', 'selectSize', 'selectColor', 'get_productId', 'open_add_modal'];

    public function open_add_modal(){
        $this->quantity = 1;
        $this->selectedSize = null;
        $this->selectedColor = null;
        $this->sizeError = '';
        $this->colorError = '';
    }

    public function get_productId($id)
    {
        $this->reset();
        $this->productId = $id;
        if( $this->productId ){
            $this->on = true;
            $this->open_add_modal();
        }
    }

    public function updateQuantity($quantity)
    {
         $this->quantity = intval($quantity);
    }

    public function selectSize($size)
    {
        $this->selectedSize = $size;
        $this->sizeError = null;
    }

    public function selectColor($color)
    {
        $this->selectedColor = $color;
        $this->colorError = null;
    }

    public function addToCart()
    {
        $product = Product::with('productStock')->find($this->productId);

        if ($product) {
            // Ensure size and color are selected if the product has variations
            if ($product->productStock->isNotEmpty()) {
                 // Initialize flags to check for attributes
                $hasSizeAttribute = false;
                $hasColorAttribute = false;

                // Loop through each stock item to find if Size or Color is required
                foreach ($product->productStock as $stock) {
                    foreach ($stock->attributeOptions as $option) {
                        if ($option->attribute->attr_name === 'Size') {
                            $hasSizeAttribute = true;
                        }
                        if ($option->attribute->attr_name === 'Color') {
                            $hasColorAttribute = true;
                        }
                    }
                }

                if (($hasSizeAttribute && !$this->selectedSize) && ($hasColorAttribute && !$this->selectedColor)) {
                    $this->sizeError = 'Please select a size';
                    $this->colorError = 'Please select a color';
                    $this->emit('error', 'Please select a size & color');
                    return;
                }

                // If size is required but not selected, show an error
                if ($hasSizeAttribute && !$this->selectedSize) {
                    $this->sizeError = 'Please select a size';
                    $this->emit('error', 'Please select a size');
                    return;
                }

                // If color is required but not selected, show an error
                if ($hasColorAttribute && !$this->selectedColor) {
                    $this->colorError = 'Please select a color';
                    $this->emit('error', 'Please select a color');
                    return;
                }
            }

            // Validate the quantity before attempting to add to the cart
            if ($this->quantity == 0 || empty($this->quantity)) {
                $this->emit('error', 'Please select a quantity');
                return;
            } elseif (!is_numeric($this->quantity) || $this->quantity <= 0) {
                $this->emit('error', 'Invalid product quantity. Please enter a valid positive quantity');
                return;
            }

            // Retrieve the current cart and generate a unique key for this item based on variations
            $cart = session()->get('cart', []);
            $cartKey = "{$this->productId}";

            if ($this->selectedSize) {
                $cartKey .= "-size:{$this->selectedSize}";
            }

            if ($this->selectedColor) {
                $cartKey .= "-color:{$this->selectedColor}";
            }

            // Check the existing quantity in the cart for this variation
            $existingQuantity = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
            $newTotalQuantity = $existingQuantity + $this->quantity;

            // Validate if the new quantity exceeds stock
            if ($newTotalQuantity > $product->quantity) {
                $this->emit('error', "You have exceeded the available stock for {$product->name}. Only {$product->quantity} items are available.");
                return;
            }

            // Add new item to the cart or update the existing quantity
            if (!isset($cart[$cartKey])) {
                // If this combination does not exist in the cart, add it as a new item
                $cart[$cartKey] = [
                    'product_id' => $this->productId,
                    'quantity' => $this->quantity,
                    'size' => $this->selectedSize,
                    'color' => $this->selectedColor,
                    'added_at' => now(),
                ];
                $this->emit('success', 'The product added to your cart.');
            } else {
                // If the combination exists, update its quantity
                $cart[$cartKey]['quantity'] = $newTotalQuantity;
                $this->emit('success', 'The product quantity updated.');
            }

            // Save the updated cart in session
            session()->put('cart', $cart);
            session()->forget('applied_coupon');
            $this->emit('cartUpdated');
            $this->emit('cartAdded');
            $this->emit('btnClose');
        }
    }

    public function render()
    {
       $product = Product::with([
                    'productStock.attributeOptions:id,product_stock_id,attribute_id,attribute_value_id'
                ])->find($this->productId);
        $attributes = Attribute::all();
        $attributesValues = AttributeValue::all();
        return view('livewire.frontend.cart.quick-view', ['product' => $product, 'attributes' => $attributes, 'attributesValues' => $attributesValues]);
    }
}
