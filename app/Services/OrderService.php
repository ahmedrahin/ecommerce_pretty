<?php
namespace App\Services;

use App\Models\{
    Order, Product, Notification, OrderHistory, ProductStockManage, ProductStock
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Jobs\OrderSent;
use Illuminate\Support\Facades\DB;

class OrderService
{

    public function placeOrder($context, array $cart, string $paymentType = 'cod', $paidAmount = null)
    {
        return DB::transaction(function () use ($context, $cart, $paymentType, $paidAmount) {

            $orderData = $this->prepareOrderData($context, $paymentType, $paidAmount);

            $order = Order::create($orderData);

            $this->saveOrderItems($order, $cart);
            $this->afterOrderPlaced($order);

            return $order;
        });
    }

    private function prepareOrderData($c, string $paymentType = 'cod', $paidAmount): array
    {
        return [
            'order_id' => Str::upper(Str::random(4)) . rand(1000, 9999),
            'user_id' => Auth::id() ?? null,
            'user_type' => 'customer',

            'name' => $c->name,
            'email' => $c->email,
            'phone' => $c->phone,

            'district_id' => $c->district_id,
            'shipping_address' => $c->shipping_address,
            'zip_code' => $c->zip_code,
            // 'city' => $c->city,

            'payment_type' => $paymentType,
            'shipping_method' => $c->selectedShippingMethodId,
            'shipping_cost' => $c->selectedShippingCharge,

            'order_date' => now(),
            'note' => $c->note,

            'paid_amount' => $paidAmount ?? 0,
            'grand_total' => $c->grandTotal(),
            'subtotal' => $c->getTotalAmount(),

            'cupon_code' => $c->appliedCoupon['code'] ?? null,
            'coupon_discount' => $c->appliedCoupon['discount'] ?? 0,
            'order_source' => 'website',
        ];
    }

    private function saveOrderItems(Order $order, array $cart)
    {
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                continue;
            }

            $stockIds = $item['stock_ids'] ?? ($item['stock_id'] ? [$item['stock_id']] : []);
            $stocks   = !empty($stockIds)
                ? ProductStock::with('attributeOptions.attribute', 'attributeOptions.attributeValue')
                    ->whereIn('id', $stockIds)
                    ->get()
                : collect();

            if ($stocks->isNotEmpty()) {
                foreach ($stocks as $stock) {
                    if ($stock->quantity < $item['quantity']) {
                        continue 2;
                    }

                    $stock->decrement('quantity', $item['quantity']);

                    if ($stock->fresh()->quantity === 0) {
                        $label = $stock->attributeOptions->map(function ($opt) {
                            return $opt->attribute->attr_name . ': ' . $opt->attributeValue->attr_value;
                        })->implode(' / ');

                        ProductStockManage::create([
                            'product_id'       => $product->id,
                            'product_stock_id' => $stock->id,
                            'variation_label'  => $label,
                            'stock'            => 'out_of_stock',
                            'quantity'         => 0,
                            'stocked_at'       => now(),
                        ]);
                    }
                }

                $product->update([
                    'quantity' => ProductStock::where('product_id', $product->id)->sum('quantity')
                ]);

            } else {
                if ($product->quantity < $item['quantity']) {
                    continue;
                }

                $product->decrement('quantity', $item['quantity']);

                if ($product->fresh()->quantity === 0) {
                    ProductStockManage::create([
                        'product_id'       => $product->id,
                        'product_stock_id' => null,
                        'variation_label'  => null,
                        'stock'            => 'out_of_stock',
                        'quantity'         => 0,
                        'stocked_at'       => now(),
                    ]);
                }
            }

            // Order item create
            $orderItem = $order->orderItems()->create([
                'product_id'       => $product->id,
                'quantity'         => $item['quantity'],
                'price'            => $item['offer_price'] ?? $item['price'] ?? 0,
                'product_stock_id' => $stocks->first()->id ?? null,
            ]);

            foreach ($item['attributes'] ?? [] as $name => $value) {
                $orderItem->orderItemVariations()->create([
                    'attribute_name'  => $name,
                    'attribute_value' => $value,
                ]);
            }
        }
    }

    private function afterOrderPlaced(Order $order)
    {
        Notification::create([
            'type' => 'order',
            'order_id' => $order->id,
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'note' => 'Order placed, waiting for processing.',
        ]);

        if ($this->mailIsConfigured()) {
            if(config('app.email')){
                // OrderSent::dispatch($order);
            }
        }

        session()->forget('cart');
        session()->forget('direct_checkout');
        session()->forget('applied_coupon');
    }

    private function mailIsConfigured(): bool
    {
        $required = [
            'mail.default',
            'mail.mailers.smtp.host',
            'mail.mailers.smtp.port',
            'mail.mailers.smtp.username',
            'mail.mailers.smtp.password',
        ];

        foreach ($required as $key) {
            if (empty(config($key))) {
                return false;
            }
        }

        return true;
    }

}
