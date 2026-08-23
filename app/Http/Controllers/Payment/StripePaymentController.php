<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\User;
use App\Services\OrderService;

class StripePaymentController extends Controller
{
    public function handleStripeSuccess(Request $request, OrderService $orderService)
    {
        $stripeSessionId = $request->get('session_id');
        
        if (!$stripeSessionId) {
            return redirect()->route('checkout.cancel')->with('error', 'Session ID is missing.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        
        try {
            $stripeSession = CheckoutSession::retrieve($stripeSessionId);
            $orderSessionId = $stripeSession->metadata->order_session_id ?? null;
            
            if (!$orderSessionId) {
                throw new \Exception('Order data not found in session.');
            }
            
            $tempOrderData = session($orderSessionId);
            
            if (!$tempOrderData) {
                throw new \Exception('Order session expired. Please try again.');
            }
            
            $cart = $tempOrderData['cart'] ?? [];
            $formData = $tempOrderData['form_data'] ?? [];
            
            if (empty($cart)) {
                throw new \Exception('Cart data is empty.');
            }
            
            $orderContext = $this->createOrderContext($formData);
        
            $order = $orderService->placeOrder(
                $orderContext, 
                $cart, 
                'stripe', 
                $stripeSession->amount_total / 100
            );

            session()->forget($orderSessionId);
            
            return redirect()->route('success.order', ['order_id' => $order->order_id])->with('success', 'Payment successful and order placed!');
            
        } catch (\Exception $e) {
            return redirect()->route('checkout.cancel')->with('error', $e->getMessage());
        }
    }

    private function createOrderContext(array $formData)
    {
        return new class($formData) {
            private $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function __get($name)
            {
                return $this->data[$name] ?? null;
            }
            
            public function grandTotal()
            {
                return $this->data['grandTotal'] ?? 0;
            }

            public function getTotalAmount()
            {
                return $this->data['subtotal'] ?? 0;
            }

            public function getAppliedCouponAttribute()
            {
                return $this->data['appliedCoupon'] ?? [];
            }
        };
    }

    public function handleStripeCancel(Request $request)
    {
        $stripeSessionId = $request->get('session_id');
        
        if ($stripeSessionId) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $stripeSession = CheckoutSession::retrieve($stripeSessionId);        
                $orderSessionId = $stripeSession->metadata->order_session_id ?? null;
                
                if ($orderSessionId) {
                    session()->forget($orderSessionId);
                }
            } catch (\Exception $e) {
                
            }
        }
        
        return view('frontend.pages.payment.cancel')->with('error', 'Payment was cancelled.');
    }

}
