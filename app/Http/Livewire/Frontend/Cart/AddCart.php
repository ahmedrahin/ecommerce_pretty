<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AddCart extends Component
{
    public $productId;
    public $quantity = 1;
    public $selectedAttributes = [];
    public $attributeErrors = [];

    protected $listeners = ['updateQuantity', 'selectAttribute'];

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function updateQuantity($quantity)
    {
        $this->quantity = intval($quantity);
    }

    public function selectAttribute($attributeName, $value)
    {
        $this->selectedAttributes[$attributeName] = $value;
        unset($this->attributeErrors[$attributeName]);
    }

    public function addToCart()
    {
        $product = Product::with('productStock.attributeOptions.attribute')->find($this->productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        // QUANTITY VALIDATION
        if ($this->quantity <= 0 || !is_numeric($this->quantity)) {
            $this->emit('error', 'Invalid product quantity.');
            return;
        }

        $requiredAttributes = [];

        if ($product->productStock->count() > 0) {
            foreach ($product->productStock as $stock) {
                foreach ($stock->attributeOptions as $option) {
                    $requiredAttributes[$option->attribute->attr_name] = true;
                }
            }

            foreach (array_keys($requiredAttributes) as $attrName) {
                if (empty($this->selectedAttributes[$attrName])) {
                    $this->attributeErrors[$attrName] = "Please select $attrName";
                }
            }

            if (!empty($this->attributeErrors)) {
                $this->emit('error', 'Please select all required options.');
                return;
            }
        }

        $selectedStock = null;

        if ($product->productStock->count() > 0) {
            $selectedStock = $this->resolveStock($product);

            if (!$selectedStock) {
                $this->emit('error', 'Invalid attribute combination.');
                return;
            }
        }

        $cart = session()->get('cart', []);

        $cartKey = "{$this->productId}";
        foreach ($this->selectedAttributes as $key => $value) {
            $cartKey .= "-{$key}:{$value}";
        }

        $existingQuantity = $cart[$cartKey]['quantity'] ?? 0;
        $newTotalQuantity = $existingQuantity + $this->quantity;

        if ($newTotalQuantity > $product->quantity) {
            $this->emit(
                'error',
                "You have exceeded available stock for {$product->name}. Only {$product->quantity} available."
            );
            return;
        }

        $cart[$cartKey] = [
            'product_id' => $this->productId,
            'quantity'   => $newTotalQuantity,
            'stock_id'   => $selectedStock?->id, 
            'attributes' => $this->selectedAttributes,
            'added_at'   => now(),
        ];

        session()->put('cart', $cart);
        session()->forget('applied_coupon');

        $this->emit('success', 'Product added to cart.');
        $this->emit('cartUpdated');
        $this->emit('cartAdded');
    }

    public function directCheckout()
    {
        $product = Product::with('productStock.attributeOptions.attribute')
            ->find($this->productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        // ================= QUANTITY =================
        if ($this->quantity <= 0 || !is_numeric($this->quantity)) {
            $this->emit('error', 'Invalid product quantity.');
            return;
        }

        // ================= ATTRIBUTE VALIDATION (ONLY IF VARIANT EXISTS) =================
        $requiredAttributes = [];

        if ($product->productStock->count() > 0) {
            foreach ($product->productStock as $stock) {
                foreach ($stock->attributeOptions as $option) {
                    $requiredAttributes[$option->attribute->attr_name] = true;
                }
            }

            foreach (array_keys($requiredAttributes) as $attrName) {
                if (empty($this->selectedAttributes[$attrName])) {
                    $this->attributeErrors[$attrName] = "Please select $attrName";
                }
            }

            if (!empty($this->attributeErrors)) {
                $this->emit('error', 'Please select all required options.');
                return;
            }
        }

        // ================= STOCK MATCH (ONLY IF VARIANT EXISTS) =================
        $selectedStock = null;

        if ($product->productStock->count() > 0) {
            $selectedStock = $this->resolveStock($product);

            if (!$selectedStock) {
                $this->emit('error', 'Invalid attribute combination.');
                return;
            }
        }

        // ================= PRODUCT STOCK ONLY =================
        if ($this->quantity > $product->quantity) {
            $this->emit(
                'error',
                "You have exceeded available stock for {$product->name}. Only {$product->quantity} available."
            );
            return;
        }

        // ================= SAVE DIRECT CHECKOUT =================
        session()->put('direct_checkout', [
            'product_id' => $this->productId,
            'stock_id'   => $selectedStock?->id,
            'quantity'   => $this->quantity,
            'attributes' => $this->selectedAttributes,
            'product_details' => [
                'name'        => $product->name,
                'slug'        => $product->slug,
                'offer_price' => $product->offer_price,
                'base_price'  => $product->base_price,
                'image_url'   => $product->thumb_image,
            ],
            'is_direct_checkout' => true,
            'added_at' => now(),
        ]);

        // dd('Direct checkout data:', session('direct_checkout'));

        session()->forget('applied_coupon');

        return redirect()->route('checkout');
    }



    private function resolveStock(Product $product)
    {
        if ($product->productStock->isEmpty()) {
            return null;
        }

        $allAttributeValues = AttributeValue::all()->keyBy('id');

        foreach ($product->productStock as $stock) {
            $matches = true;

            foreach ($stock->attributeOptions as $option) {
                $attrName = $option->attribute->attr_name;

                $dbValue = $allAttributeValues[$option->attribute_value_id]->attr_value ?? null;
                $selectedValue = $this->selectedAttributes[$attrName] ?? null;

                if ($dbValue !== $selectedValue) {
                    $matches = false;
                    break;
                }
            }

            if ($matches) {
                return $stock;
            }
        }

        return null;
    }

    public function render()
    {
        $product = Product::with([
            'productStock.attributeOptions:id,product_stock_id,attribute_id,attribute_value_id'
        ])->find($this->productId);

        $attributes = Attribute::all();
        $attributesValues = AttributeValue::all();
        return view('livewire.frontend.cart.add-cart', compact('product', 'attributes', 'attributesValues'));
    }
}
