<?php

namespace App\Http\Livewire\Frontend\Order;

use Livewire\Component;
use App\Models\{
    Order, Product, State, ProductStock
};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Services\OrderService;
use App\Services\StripeService;

class Checkout extends Component
{
    public $name;
    public $email;
    public $phone;
    public $shipping_address;
    public $zip_code;
    public $city;
    public $district_id;
    public $state_id;
    public $note;
    public $payment_type;
    public $selectedShippingMethodId;
    public $selectedShippingMethodType;
    public $selectedShippingCharge = 0;

    public $cart = [];
    public $quantities = [];
    public $shippingMethods = [];
    public $couponCode;
    public $discountAmount = 0;
    public $appliedCoupon;
    private $cacheKey;

    // shipping
    public $country_code = 'US';
    public $state_code;
    public $states = [];

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
        $this->payment_type = 'cod';

        $this->appliedCoupon = session()->get('applied_coupon', null);

        // state list
        $this->states = State::where('status', 1)->get();

        if (Auth::check()) {
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
            $this->phone = Auth::user()->phone;
            $this->shipping_address = Auth::user()->address_line1;
            $this->zip_code = Auth::user()->zipCode;
            $this->city = Auth::user()->city;
        }
    }

    public function loadCart()
    {
        // Retrieve the cart from the session
        $sessionCart = session()->get('cart', []);

        $validCart = [];

        foreach ($sessionCart as $cartKey => $item) {
            $productId = explode('-', $cartKey)[0];
            $product = Product::find($productId);

            if ($product && ($product->status == 1 || $product->status == 3) && $product->quantity > 0) {
                $validCart[$cartKey] = $item;
                $validCart[$cartKey]['name'] = $product->name;
                $validCart[$cartKey]['slug'] = $product->slug;
                $validCart[$cartKey]['offer_price'] = $product->offer_price;
                $validCart[$cartKey]['price'] = $product->base_price;
                $validCart[$cartKey]['image_url'] = $product->thumb_image;
                $validCart[$cartKey]['available_quantity'] = $product->quantity;
                $validCart[$cartKey]['discount_option'] = $product->discount_option;
                $validCart[$cartKey]['quantity'] = $item['quantity'] ?? 1;
            }
        }

        // Assign valid items to the cart
        $this->cart = $validCart;

        // Check for direct checkout first
        $directCheckout = session()->get('direct_checkout');

        // dd($directCheckout);

        if ($directCheckout && $directCheckout['is_direct_checkout']) {

            $product = Product::find($directCheckout['product_id']);

            if ($product && ($product->status == 1 || $product->status == 3) && $product->quantity > 0) {

                // Create cart key with attributes
                $cartKey = "{$product->id}";
                foreach ($directCheckout['attributes'] as $key => $value) {
                    $cartKey .= "-{$key}:{$value}";
                }

                // Convert attributes to view-friendly format
                $attributesInfo = [];
                foreach ($directCheckout['attributes'] as $key => $value) {
                    $attributesInfo[] = [
                        'name' => ucfirst($key),
                        'value' => $value,
                    ];
                }

                // Final direct checkout cart override
                $this->cart = [
                    $cartKey => [
                        'product_id' => $product->id,
                        'quantity' => $directCheckout['quantity'],
                        'attributes' => $directCheckout['attributes'],
                        'attributes_info' => $attributesInfo,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'offer_price' => $product->offer_price,
                        'price' => $product->base_price,
                        'image_url' => $product->thumb_image,
                        'available_quantity' => $product->quantity,
                        'discount_option' => $product->discount_option,
                        'stock_id'   => $directCheckout['stock_id'] ?? null,
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
        foreach ($this->cart as $item) {
            $product = Product::find($item['product_id'] ?? explode('-', $item['id'])[0]);
            if (!$product)
                continue;

            // If coupon has categories, only count products inside them
            if (empty($categoryIds) || $product->category()->whereIn('categories.id', $categoryIds)->exists()) {
                $eligibleTotal += ($product->offer_price ?? $product->base_price) * $item['quantity'];
            }
        }

        if ($eligibleTotal <= 0) {
            $this->emit('error', 'This coupon is not applicable to your selected products.');
            $this->couponCode = '';
            return;
        }

        if ($coupon->min_purchase_amount && ($coupon->min_purchase_amount > $this->getTotalAmount())) {
            $this->emit('error', 'You need to minimum purchase ' . $coupon->min_purchase_amount . 'tk for use this coupon');
            $this->couponCode = '';
            return;
        }

        // Check usage limit
        $usage = $coupon->orders()->count();
        if ($coupon->usage_limit && ($usage >= $coupon->usage_limit)) {
            $this->emit('error', 'The coupon usage limit has been reached.');
            $this->couponCode = '';
            return;
        }

        // Apply the discount based on coupon type
        if ($coupon->discount_type == 'percentage') {
            $this->discountAmount = $eligibleTotal * ($coupon->discount_amount / 100);
        } else {
            $this->discountAmount = min($coupon->discount_amount, $eligibleTotal);
        }


        // Store the coupon and discount amount
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

        // $this->emit('info', 'Coupon removed.');
    }

    public function updatedStateId($value){
        if (empty($value)) {
            $this->shippingMethods = [];
            $this->selectedShippingMethodId = null;
            $this->selectedShippingCharge = 0;
            return;
        }
        
        $state = State::find($value);

        if (!$state) {
            return;
        }

        $this->state_code = $state->code;
        $this->city = $state->name;

        $this->fetchShippingRates($state->code);
    }

    public function fetchShippingRates($code)
    {
        if (!$this->state_id) {
            return;
        }

        $items = [];

        foreach ($this->cart as $item) {

            $stock = ProductStock::find($item['stock_id'] ?? null);

            if ($stock && $stock->printful_variant_id) {
                $items[] = [
                    'variant_id' => (int) $stock->printful_variant_id,
                    'quantity'   => (int) $item['quantity'],
                ];
            }
        }

        if (empty($items)) {
            return;
        }

        $address = [
            'country_code' => 'US',
            'state_code'   => $code,
        ];

        try {
            $rates = app(\App\Services\PrintfulService::class)->getShippingRates($items, $address);
            if (!$rates) {
                throw new \Exception('Shipping rates not available');
            }
        } catch (\Exception $e) {
            throw new \Exception('Shipping rate load failed. Please try again.');
        }

        $this->shippingMethods = $rates;

        if (!empty($rates)) {
            $this->selectedShippingMethodType = $rates[0]['id'];
            $this->selectedShippingCharge   = $rates[0]['rate'];
        }
    }

    public function updatedSelectedShippingMethodType($value)
    {
        $method = collect($this->shippingMethods)->firstWhere('id', $value);

        if ($method) {
            $this->selectedShippingCharge = (float) $method['rate'];
        }
    }

    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'phone' => 'required|numeric',
        'shipping_address' => 'required',
        'city' => 'required',
        'zip_code' => 'required',
        'state_id' => 'required',
    ];

    protected $messages = [
        'state_id.required' => 'Please select a state'
    ];

    public function order(OrderService $orderService, StripeService $stripeService, Request $request)
    {
         $this->validate();

        try {
            if (empty($this->cart)) {
                throw new \Exception('Your cart is empty');
            }

            // if (!$this->selectedShippingMethodId) {
            //     throw new \Exception('Select a shipping method');
            // }

            if (!$this->payment_type) {
                throw new \Exception('Select a payment method');
            }

            if ($this->payment_type === 'cod') {
                $order = $orderService->placeOrder($this, $this->cart, 'cod');
                return redirect()->route('success.order', ['order_id' => $order->order_id])->with('success', 'Order placed successfully!');
            }


            if (!preg_match('/^\d{5}$/', $this->zip_code)) {
                throw new \Exception('Please enter a valid 5-digit ZIP code');
            }
            
            // Printful validation (without creating order)
            $printfulService = app(\App\Services\PrintfulService::class);
            
            // Prepare items for validation
            $items = [];
            foreach ($this->cart as $item) {
                $stock = ProductStock::find($item['stock_id'] ?? null);
                if ($stock && $stock->printful_variant_id) {
                    $items[] = [
                        'variant_id' => (int) $stock->printful_variant_id,
                        'quantity'   => (int) $item['quantity'],
                    ];
                }
            }
            
            // Validate address with Printful
            if (!empty($items)) {
                $address = [
                    'country_code' => 'US',
                    'state_code'   => $this->state_code,
                    'zip'          => $this->zip_code,
                    'city'         => $this->city,
                ];
                
                // This will throw exception if validation fails
                $rates = $printfulService->getShippingRates($items, $address);
                
                if (empty($rates)) {
                    throw new \Exception('Shipping not available for this address');
                }
            }

            if ($this->payment_type === 'stripe') {
                $orderData = [
                    'cart' => $this->cart,
                    'form_data' => [
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'shipping_address' => $this->shipping_address,
                        'zip_code' => $this->zip_code,
                        'city' => $this->city,
                        'state_id' => $this->state_id,
                        'state_code' => $this->state_code,
                        // 'selectedShippingMethodId' => $this->selectedShippingMethodId ?? null,
                        'selectedShippingCharge' => $this->selectedShippingCharge ?? 0,
                        'selectedShippingMethodType' => $this->selectedShippingMethodType ?? null,
                        'note' => $this->note,
                        'appliedCoupon' => $this->appliedCoupon ?? [],
                        'grandTotal' => $this->grandTotal(),
                        'subtotal' => $this->getTotalAmount(),
                    ]
                ];

                $stripeSessionId = 'stripe_order_' . time() . '_' . rand(1000, 9999);
                session([$stripeSessionId => $orderData]);
                $userId = auth()->check() ? auth()->id() : null;

                $session = $stripeService->createCheckout(
                    $this->cart,
                    $this->grandTotal(),
                    $userId,
                    $stripeSessionId
                );

                return redirect()->away($session->url);
            }
        } catch(\Exception $e) {
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
        $total = $this->getTotalAmount() + $this->selectedShippingCharge - $discount;
        return number_format($total, 2);
    }


    public function hydrate()
    {
        // Reset error bag and validation
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
        //$districts = District::orderBy('name', 'asc')->where('status', 1)->get();
        return view('livewire.frontend.order.checkout');
    }

}
