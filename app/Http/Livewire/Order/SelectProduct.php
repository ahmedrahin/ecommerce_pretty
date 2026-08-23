<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;

class SelectProduct extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $paginationCount = 5;

    public $selectedProducts = [];
    public $quantities = [];
    public $totalQuantity = 0;
    public $totalCost = 0;
    public $currentUrl;
    public $search;
    public $categories;
    public $selectedCategory = null;

    // Load attribute data for filters
    public $allAttributes = [];
    public $allAttributeValues = [];

    public function mount()
    {
        $this->categories = Category::orderBy('name', 'asc')->where('status', 1)->get();

        $this->allAttributes = Attribute::all();
        $this->allAttributeValues = AttributeValue::all();

        $this->selectedProducts = session()->get('selectedProducts', []);
        $this->quantities = session()->get('quantities', []);
        $this->totalQuantity = session()->get('totalQuantity', 0);
        $this->totalCost = session()->get('totalCost', 0);

        $this->currentUrl = url()->current();
        session()->put('lastVisitedUrl', $this->currentUrl);

        $this->listeners = [
            'clearSessionDataOnLeave' => 'clearSessionDataOnLeave'
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedProducts()
    {
        $selectedProductIds = array_flip($this->selectedProducts);

        // Remove quantities for unchecked products
        foreach (session()->get('selectedProducts', []) as $productId) {
            if (!isset($selectedProductIds[$productId])) {
                unset($this->quantities[$productId]);
                session()->forget("quantities.$productId");
            }
        }

        // Add default quantity for newly selected products
        foreach ($this->selectedProducts as $productId) {
            if (!isset($this->quantities[$productId])) {
                $this->quantities[$productId] = 1;
            }
        }

        $this->updateSession();
    }

    public function updatedQuantities($value, $productId)
    {
        if (in_array($productId, $this->selectedProducts)) {
            $this->updateSession();
            $this->emit('success', 'Quantity has been updated.');
        } else {
            $this->quantities[$productId] = 1;
            $this->emit('warning', 'Please select the product first.');
        }
    }

    public function updateSession()
    {
        $productData = [];
        $this->totalQuantity = 0;
        $this->totalCost = 0;

        foreach ($this->selectedProducts as $productId) {
            $quantity = $this->quantities[$productId] ?? 1;
            $productData[$productId] = $quantity;

            $product = Product::find($productId);
            if ($product) {
                $this->totalQuantity += $quantity;

                $price = ($product->discount_option != 1) ? $product->offer_price : $product->base_price;
                $this->totalCost += $price * $quantity;
            }
        }

        session()->put('selectedProducts', $this->selectedProducts);
        session()->put('quantities', $this->quantities);
        session()->put('totalQuantity', $this->totalQuantity);
        session()->put('totalCost', $this->totalCost);
    }

    public function removeProduct($productId)
    {
        $this->selectedProducts = array_filter($this->selectedProducts, function ($id) use ($productId) {
            return $id != $productId;
        });

        unset($this->quantities[$productId]);

        session()->put('selectedProducts', $this->selectedProducts);
        session()->put('quantities', $this->quantities);

        $this->updateSession();
        $this->emit('info', 'The product has been removed from cart.');
    }

    public function clearSessionData()
    {
        session()->forget('selectedProducts');
        session()->forget('quantities');
        session()->forget('totalQuantity');
        session()->forget('totalCost');
    }

    public function clearSessionDataOnLeave()
    {
        $this->clearSessionData();
    }

    public function render()
    {
        $products = Product::activeProducts()
            ->with([
                'productStock:id,product_id',
                'productStock.attributeOptions:id,product_stock_id,attribute_id,attribute_value_id'
            ])
            ->when($this->selectedCategory && $this->selectedCategory !== 'all', function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                             ->orWhere('sku_code', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->paginationCount);

        // Ensure every loaded product has a default quantity
        foreach ($products as $product) {
            if (!isset($this->quantities[$product->id])) {
                $this->quantities[$product->id] = 1;
            }
        }

        return view('livewire.order.select-product', [
            'products' => $products
        ]);
    }
}
