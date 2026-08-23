<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedCustomer;

class PrintfulWebhookController extends Controller
{
    public function shipping_deliverd(Request $request)
    {
        $data = $request->all();

        if (($data['type'] ?? null) === 'package_shipped') {

            $printfulOrderId = $data['data']['order']['id'] ?? null;

            $order = Order::where('printful_order_id', $printfulOrderId)->first();

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $shipment = $data['data']['shipment'] ?? [];

            $order->update([
                'tracking_url' => $shipment['tracking_url'] ?? null,
                'tracking_number' => $shipment['tracking_number'] ?? null,
            ]);

            Mail::to($order->email)->send(new OrderPlacedCustomer($order));
        }

        return response()->json(['status' => 'ok']);
    }
}