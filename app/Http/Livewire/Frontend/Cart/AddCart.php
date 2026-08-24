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
    public $selectedStockId = null;
    public $selectedStockQty = 0;
    public $selectedStockPrice = null;

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
        $this->resolveSelectedStock();
    }

    private function resolveSelectedStock()
    {
        $product = Product::with([
            'productStock.attributeOptions.attribute',
            'productStock.attributeOptions.attributeValue',
        ])->find($this->productId);

        if (!$product || $product->productStock->isEmpty()) {
            $this->selectedStockId    = null;
            $this->selectedStockQty   = $product->quantity ?? 0;
            $this->selectedStockPrice = null;
            return;
        }

        $matchedStocks = []; 

        foreach ($this->selectedAttributes as $attrName => $attrValue) {
            foreach ($product->productStock as $stock) {
                if ($stock->is_disabled) {
                    continue;
                }
                foreach ($stock->attributeOptions as $option) {
                    $dbAttrName  = $option->attribute->attr_name ?? null;
                    $dbAttrValue = $option->attributeValue->attr_value ?? null;

                    if ($dbAttrName === $attrName && $dbAttrValue === $attrValue) {
                        $matchedStocks[] = [
                            'stock_id' => $stock->id,
                            'quantity' => (int) $stock->quantity,
                            'price'    => $stock->price,
                        ];
                        break 2;
                    }
                }
            }
        }

        if (!empty($matchedStocks)) {
            $minQty = min(array_column($matchedStocks, 'quantity'));

            $this->selectedStockId    = collect($matchedStocks)->pluck('stock_id')->toArray(); 
            $this->selectedStockQty   = $minQty;
            $this->selectedStockPrice = $matchedStocks[0]['price'] ?? null;
            return;
        }

        $this->selectedStockId    = null;
        $this->selectedStockQty   = 0;
        $this->selectedStockPrice = null;
    }

    public function addToCart()
    {
        $product = Product::with([
            'productStock.attributeOptions.attribute',
            'productStock.attributeOptions.attributeValue',
        ])->find($this->productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        // Required attributes validate
        $requiredAttributes = $this->getRequiredAttributes($product);

        foreach (array_keys($requiredAttributes) as $attrName) {
            if (empty($this->selectedAttributes[$attrName])) {
                $this->attributeErrors[$attrName] = "Please select $attrName";
            }
        }

        if (!empty($this->attributeErrors)) {
            $this->emit('error', 'Please select all required options.');
            return;
        }

        if ($this->quantity <= 0 || !is_numeric($this->quantity)) {
            $this->emit('error', 'Invalid product quantity.');
            return;
        }

        // Variant check & stock validate
        $hasVariant = $product->productStock->count() > 0;

        if ($hasVariant) {
            $this->resolveSelectedStock();

            if ($this->selectedStockQty <= 0) {
                $this->emit('error', 'Selected variant is out of stock.');
                return;
            }

            if ($this->quantity > $this->selectedStockQty) {
                $this->emit('error', "Only {$this->selectedStockQty} available for this variant.");
                return;
            }
        } else {
            if ($this->quantity > $product->quantity) {
                $this->emit('error', "Only {$product->quantity} available.");
                return;
            }
        }

        $cart    = session()->get('cart', []);
        $cartKey = "{$this->productId}";
        foreach ($this->selectedAttributes as $key => $value) {
            $cartKey .= "-{$key}:{$value}";
        }

        $existingQty      = $cart[$cartKey]['quantity'] ?? 0;
        $newTotalQuantity = $existingQty + $this->quantity;
        $maxQty           = $hasVariant ? $this->selectedStockQty : $product->quantity;

        if ($newTotalQuantity > $maxQty) {
            $this->emit('error', "Only {$maxQty} available.");
            return;
        }

        $price = $this->selectedStockPrice ?? $product->offer_price ?? $product->base_price;

        $cart[$cartKey] = [
            'product_id' => $this->productId,
            'stock_ids'   => is_array($this->selectedStockId)  ? $this->selectedStockId : ($this->selectedStockId ? [$this->selectedStockId] : []),
            'quantity'   => $newTotalQuantity,
            'price'      => $price,
            'attributes' => $this->selectedAttributes,
            'added_at'   => now(),
        ];

        session()->put('cart', $cart);
        session()->forget('applied_coupon');
        session()->forget('direct_checkout');

        if (method_exists($this, 'dispatchBrowserEvent')) {
            $this->dispatchBrowserEvent('addToCartDataLayer', [
                'item_id' => $product->id,
                'item_name' => $product->name,
                'price' => (float)$price,
                'quantity' => (int)$this->quantity,
                'category' => $product->category->name ?? '',
                'variant' => implode(', ', $this->selectedAttributes)
            ]);
        }

        $this->emit('success', 'Product added to cart.');
        $this->emit('cartUpdated');
        $this->emit('cartAdded');
    }

    public function directCheckout()
    {
        $product = Product::with([
            'productStock.attributeOptions.attribute',
            'productStock.attributeOptions.attributeValue',
        ])->find($this->productId);

        if (!$product) {
            $this->emit('error', 'Product not found.');
            return;
        }

        if ($this->quantity <= 0 || !is_numeric($this->quantity)) {
            $this->emit('error', 'Invalid product quantity.');
            return;
        }

        $requiredAttributes = $this->getRequiredAttributes($product);

        foreach (array_keys($requiredAttributes) as $attrName) {
            if (empty($this->selectedAttributes[$attrName])) {
                $this->attributeErrors[$attrName] = "Please select $attrName";
            }
        }

        if (!empty($this->attributeErrors)) {
            $this->emit('error', 'Please select all required options.');
            return;
        }

        $hasVariant = $product->productStock->count() > 0;

        if ($hasVariant) {
            $this->resolveSelectedStock();

            if ($this->selectedStockQty <= 0) {
                $this->emit('error', 'Selected variant is out of stock.');
                return;
            }

            if ($this->quantity > $this->selectedStockQty) {
                $this->emit('error', "Only {$this->selectedStockQty} available for this variant.");
                return;
            }
        } else {
            if ($this->quantity > $product->quantity) {
                $this->emit('error', "Only {$product->quantity} available.");
                return;
            }
        }

        $price = $this->selectedStockPrice ?? $product->offer_price ?? $product->base_price;

        session()->put('direct_checkout', [
            'product_id'         => $this->productId,
            'stock_ids'   => is_array($this->selectedStockId)  ? $this->selectedStockId : ($this->selectedStockId ? [$this->selectedStockId] : []),
            'quantity'           => $this->quantity,
            'price'              => $price,
            'attributes'         => $this->selectedAttributes,
            'product_details'    => [
                'name'        => $product->name,
                'slug'        => $product->slug,
                'offer_price' => $price,
                'base_price'  => $product->base_price,
                'image_url'   => $product->thumb_image,
            ],
            'is_direct_checkout' => true,
            'added_at'           => now(),
        ]);

        session()->forget('applied_coupon');

        session()->flash('dataLayer_add_to_cart', [
            'value' => (float)($price * $this->quantity),
            'item' => [
                'item_id' => (string)$product->id,
                'item_name' => $product->name,
                'price' => (float)$price,
                'quantity' => (int)$this->quantity,
                'item_category' => $product->category->name ?? '',
                'item_variant' => implode(', ', $this->selectedAttributes)
            ]
        ]);

        return redirect()->route('checkout');
    }

    private function getRequiredAttributes(Product $product): array
    {
        $required = [];
        foreach ($product->productStock as $stock) {
            if ($stock->is_disabled) {
                continue;
            }
            foreach ($stock->attributeOptions as $option) {
                if ($option->attribute) {
                    $required[$option->attribute->attr_name] = true;
                }
            }
        }
        return $required;
    }

    public function render()
    {
        $product = Product::with([
            'productStock.attributeOptions.attribute',
            'productStock.attributeOptions.attributeValue',
        ])->find($this->productId);

        $attributes       = Attribute::all();
        $attributesValues = AttributeValue::all();

        return view('livewire.frontend.cart.add-cart', compact('product', 'attributes', 'attributesValues'));
    }
}
