<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class StripeService
{
    public function createCheckout(array $cart, float $grandTotal, ?int $userId = null, ?string $orderSessionId = null)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [
            [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order Payment',
                    ],
                    'unit_amount' => (int) round($grandTotal * 100),
                ],
                'quantity' => 1,
            ]
        ];

        $metadata = [
            'user_id' => $userId,
        ];

        if ($orderSessionId) {
            $metadata['order_session_id'] = $orderSessionId;
        }

        return CheckoutSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'metadata' => $metadata,
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);
    }
}