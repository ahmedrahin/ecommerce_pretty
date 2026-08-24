<?php

namespace App\Http\Livewire\Frontend\Order;

use Livewire\Component;
use App\Models\ShippingMethod;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use App\Services\OrderService;

class Checkout extends Component
{
    public $name;
    public $email;
    public $phone;
    public $shipping_address;
    public $district_id;
    public $zip_code;
    public $note;
    public $payment_type;
    public $selectedShippingMethodId;
    public $selectedShippingMethodType;
    public $selectedShippingCharge = 0;

    public $cart = [];
    public $quantities = [];
    public $shippingMethods;
    public $couponCode;
    public $discountAmount = 0;
    public $appliedCoupon;
    public $showCouponForm = false;
    private $cacheKey;

    protected $listeners = [
        'cartUpdated' => 'refreshCart',
    ];

    public function __construct()
    {
        $this->cacheKey = config('dbcachekey.order');
    }

    public function mount()
    {
        $this->loadCart();
        $this->loadShippingMethods();
        $this->payment_type = 'cod';

        $this->appliedCoupon = session()->get('applied_coupon', null);

        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone;
            $this->shipping_address = Auth::user()->address_line1;
        }
    }

    public function loadCart()
    {
        $sessionCart = session()->get('cart', []);
        $validCart = [];

        foreach ($sessionCart as $cartKey => $item) {
            $productId = explode('-', $cartKey)[0];
            $product = Product::find($productId);

            if ($product && ($product->status == 1 || $product->status == 3) && $product->quantity > 0) {
                $validCart[$cartKey] = $item;
                $validCart[$cartKey]['name']               = $product->name;
                $validCart[$cartKey]['slug']               = $product->slug;
                $validCart[$cartKey]['offer_price']        = $item['price'] ?? $product->offer_price;
                $validCart[$cartKey]['price']              = $product->base_price;
                $validCart[$cartKey]['image_url']          = $product->thumb_image;
                $validCart[$cartKey]['available_quantity'] = $product->quantity;
                $validCart[$cartKey]['discount_option']    = $product->discount_option;
                $validCart[$cartKey]['quantity']           = $item['quantity'] ?? 1;
                $validCart[$cartKey]['stock_ids']          = $item['stock_ids'] ?? [];
            }
        }

        $this->cart = $validCart;

        // Direct checkout
        $directCheckout = session()->get('direct_checkout');

        if ($directCheckout && $directCheckout['is_direct_checkout']) {
            $product = Product::find($directCheckout['product_id']);

            if ($product && ($product->status == 1 || $product->status == 3) && $product->quantity > 0) {

                $cartKey = "{$product->id}";
                foreach ($directCheckout['attributes'] as $key => $value) {
                    $cartKey .= "-{$key}:{$value}";
                }

                $attributesInfo = [];
                foreach ($directCheckout['attributes'] as $key => $value) {
                    $attributesInfo[] = [
                        'name'  => ucfirst($key),
                        'value' => $value,
                    ];
                }

                $this->cart = [
                    $cartKey => [
                        'product_id'         => $product->id,
                        'quantity'           => $directCheckout['quantity'],
                        'attributes'         => $directCheckout['attributes'],
                        'attributes_info'    => $attributesInfo,
                        'name'               => $product->name,
                        'slug'               => $product->slug,
                        'offer_price'        => $directCheckout['price'] ?? $product->offer_price,
                        'price'              => $product->base_price,
                        'image_url'          => $product->thumb_image,
                        'available_quantity' => $product->quantity,
                        'discount_option'    => $product->discount_option,
                        'stock_ids'          => $directCheckout['stock_ids'] ?? [],
                    ]
                ];

                return;
            }
        }
    }

    public function applyCoupon()
    {
        if ($this->couponCode == null) {
            $this->emit('error', 'please enter your coupon code.');
            return;
        }

        $coupon = Coupon::whereRaw('BINARY code = ?', [$this->couponCode])
            ->where('status', 1)
            ->whereDate('start_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhereDate('expire_date', '>=', now());
            })
            ->first();

        if (!$coupon) {
            $this->emit('error', 'Invalid or expired coupon code!');
            $this->couponCode = '';
            return;
        }

        $categoryIds = $coupon->categories()->pluck('categories.id')->toArray();

        $eligibleTotal = 0;
        $eligibleQty = 0;
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id'] ?? explode('-', $item['id'])[0]);
            if (!$product) continue;

            if (empty($categoryIds) || $product->category()->whereIn('categories.id', $categoryIds)->exists()) {
                $eligibleTotal += ($product->offer_price ?? $product->base_price) * $item['quantity'];
                $eligibleQty += $item['quantity'];
            }
        }

        if ($eligibleTotal <= 0) {
            $this->emit('error', 'This coupon is not applicable to your selected products.');
            $this->couponCode = '';
            return;
        }

        if ($coupon->min_qty && $eligibleQty < $coupon->min_qty) {
            $this->emit('error', 'You need to purchase at least ' . $coupon->min_qty . ' items to use this coupon.');
            $this->couponCode = '';
            return;
        }

        if ($coupon->max_qty && $eligibleQty > $coupon->max_qty) {
            $this->emit('error', 'This coupon can only be applied to a maximum of ' . $coupon->max_qty . ' items.');
            $this->couponCode = '';
            return;
        }

        if ($coupon->min_purchase_amount && ($coupon->min_purchase_amount > $this->getTotalAmount())) {
            $this->emit('error', 'You need to minimum purchase ' . $coupon->min_purchase_amount . 'tk for use this coupon');
            $this->couponCode = '';
            return;
        }

        $usage = $coupon->orders()->count();
        if ($coupon->usage_limit && ($usage >= $coupon->usage_limit)) {
            $this->emit('error', 'The coupon usage limit has been reached.');
            $this->couponCode = '';
            return;
        }

        if ($coupon->discount_type == 'percentage') {
            $this->discountAmount = $eligibleTotal * ($coupon->discount_amount / 100);
        } else {
            $this->discountAmount = min($coupon->discount_amount, $eligibleTotal);
        }

        session()->put('applied_coupon', [
            'code' => $this->couponCode,
            'discount' => $this->discountAmount,
        ]);
        $this->appliedCoupon = session()->get('applied_coupon');
        $this->emit('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        $this->couponCode = null;
        $this->discountAmount = 0;
        $this->appliedCoupon = [];
        session()->forget('applied_coupon');
    }

    public function updatedDistrictId($value)
    {
        $methods = ShippingMethod::where('status', 1)->where('base_id', $value)->first();

        if ($methods) {
            $this->selectedShippingMethodId = $methods->id;
            $this->selectedShippingCharge = $methods->base_charge;
            $this->shippingMethods = collect();
        } else {
            $this->loadShippingMethods();
        }
    }

    public function loadShippingMethods()
    {
        $this->shippingMethods = ShippingMethod::where('status', 1)
            ->where('base_id', null)
            ->get();

        if ($this->shippingMethods->count() === 1) {
            $singleMethod = $this->shippingMethods->first();
            $this->selectedShippingMethodId = $singleMethod->id;
            $this->selectedShippingCharge = $singleMethod->provider_charge;
        } elseif ($this->shippingMethods->count() > 1) {
            $this->selectedShippingMethodId = null;
            $this->selectedShippingCharge = 0;
        }
    }

    public function updatedSelectedShippingMethodId()
    {
        $shippingMethod = ShippingMethod::where('id', $this->selectedShippingMethodId)->first();

        if ($shippingMethod) {
            $this->selectedShippingCharge = $shippingMethod->provider_charge;
        } else {
            $this->selectedShippingCharge = 0;
        }
    }

    public function updatedSelectedShippingMethodType($value)
    {
        $this->selectedShippingMethodId = $value;
        $shippingMethod = ShippingMethod::where('id', $value)->first();

        if ($shippingMethod) {
            $this->selectedShippingCharge = $shippingMethod->provider_charge;
        } else {
            $this->selectedShippingCharge = 0;
        }
    }

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|numeric',
        'shipping_address' => 'required',
        'district_id' => 'required',
    ];

    protected $messages = [
        'district_id.required' => 'Please select a city.',
    ];

    public function order(OrderService $orderService)
    {
        $this->validate();

        try {
            if (empty($this->cart)) {
                throw new \Exception('Your cart is empty');
            }

            if (!$this->selectedShippingMethodId) {
                throw new \Exception('Select a shipping method');
            }

            if (!$this->payment_type) {
                throw new \Exception('Select a payment method');
            }

            if ($this->payment_type === 'cod') {
                $order = $orderService->placeOrder($this, $this->cart, 'cod');
                return redirect()->route('success.order', ['order_id' => $order->order_id])->with('success', 'Order placed successfully!');
            }
        } catch (\Exception $e) {
            $this->emit('error', $e->getMessage());
        }
    }

    public function refreshCart()
    {
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

    public function grandTotal()
    {
        $discount = $this->appliedCoupon ? ($this->appliedCoupon['discount'] ?? 0) : 0;
        return $this->getTotalAmount() + $this->selectedShippingCharge - $discount;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function refreshCache()
    {
        Cache::forget($this->cacheKey);
        Cache::rememberForever($this->cacheKey, function () {
            return Order::orderBy('id', 'desc')->get();
        });
    }

    public function render()
    {
        $districts = District::orderBy('name', 'asc')->where('status', 1)->get();
        return view('livewire.frontend.order.checkout', compact('districts'));
    }
}
