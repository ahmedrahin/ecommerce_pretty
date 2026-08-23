<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrintfulService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.printful.base_url');
        $this->apiKey  = config('services.printful.key');
    }

    /**
     * Send order to Printful
     */
    public function createOrder(Order $order): bool
    {
        try {
            $payload = $this->buildOrderPayload($order);

            // normal order, no variant not an error
            if (empty($payload['items'])) {
                return true;
            }

            $response = Http::withToken($this->apiKey)->acceptJson()->post($this->baseUrl . '/orders', $payload);

            if (!$response->successful()) {
                $error = $response->json();
                $errorMessage = $error['error']['message'] ?? 'Unknown Printful error';
                
                throw new \Exception($errorMessage);
            }

            $result = $response->json('result');

            // Save Printful reference
            $order->update([
                'printful_order_id'   => $result['id'] ?? null,
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('Printful API exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Build payload for Printful API
     */
    protected function buildOrderPayload(Order $order): array
    {
        $items = [];

        foreach ($order->orderItems as $item) {
            if (!$item->stock?->printful_variant_id) {
                continue;
            }

            $items[] = [
                'variant_id' => (int) $item->stock->printful_variant_id,
                'quantity'   => (int) $item->quantity,
                'name'   => $item->product->name ?? 'Unknown Product',
                'price'  => (float) $item->price,
                'retail_price'  => (float) $item->price,

                'files' => [
                    [
                       'type' => 'default',
                    //    'url' => $item->product->p_logo ? asset($item->product->p_logo) : asset('p_logo.png'),
                       'url' => 'https://extropy.com.bd/uploads/media/1772133143_in.png',
                    ]
                ],

                'options' => [
                    [
                        'id' => 'thread_colors',
                        'value' => ['#000000', '#CC3333']
                    ]
                ]
            ];

        }

        return [
            'external_id' => $order->order_id,
            'recipient' => [
                'name'    => $order->name,
                'address1'=> $order->shipping_address,
                'city'    => $order->city ?? null,
                'state_code' => $order->state_code,
                'zip'     => $order->zip_code,
                'country_code' => 'US',
                'phone'   => $order->phone,
                'email'   => $order->email ?? null,
            ],

            'shipping' => $order->shipping_type,

            'retail_costs' => [
                'currency'    => 'USD',
                'subtotal'    => (float) $order->subtotal,
                'discount'    => (float) $order->coupon_discount,
                'shipping'    => (float) $order->shipping_cost,
                'tax'         => 0,
                'total'       => (float) $order->grand_total,
            ],

            'items' => $items,

            'packing_slip' => [
                ''
            ]
        ];
    }

    /**
     * Get shipping rates (for checkout)
     */
    public function getShippingRates(array $items, array $address)
    {
        $payload = [
            'recipient' => $address,
            'items' => $items,
        ];

        $response = Http::withToken($this->apiKey)
            ->acceptJson()
            ->post($this->baseUrl . '/shipping/rates', $payload);

        return $response->json('result') ?? [];
    }
}
