<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\OrderItems;

class ProductDetailsService
{
    public function getProductDetails($id)
    {
        $product = Product::with([
            'category:id,name',
            'brand:id,name',
            'galleryImages:id,product_id,image',
            'tags:id,product_id,name',
            'productStock:id,product_id',
            'productStock.attributeOptions:id,product_stock_id,attribute_id,attribute_value_id',
            'reviews:id,product_id,rating',
            'orderItemsWithOrder.order:id,delivery_status',
            'stockHistories'
        ])->find($id);

        if (!$product) {
            return null;
        }

        $deliveryStats = $product->orderItems()
            ->whereNull('orders.deleted_at')
            ->selectRaw('
                orders.delivery_status   as status,
                COUNT(*)                 as total_orders,
                COALESCE(SUM(order_items.quantity), 0) as total_qty,
                COALESCE(SUM(order_items.price * order_items.quantity), 0)  as total_amount
            ')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->groupBy('orders.delivery_status')
            ->get()
            ->keyBy('status');

        // ৩️⃣ Total calculation
        $totalQty    = $deliveryStats->sum('total_qty');
        $totalAmount = $deliveryStats->sum('total_amount');

        // ৪️⃣ Extra info (attributes, values, last order)
        $attributes        = Attribute::all();
        $attributesValues  = AttributeValue::all();
        $lastOrder         = OrderItems::where('product_id', $product->id)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.created_at', 'desc')
            ->select('orders.*')
            ->first();

        // ৫️⃣ সব data return করা
        return [
            'product'          => $product,
            'deliveryStats'    => $deliveryStats,
            'totalQty'         => $totalQty,
            'totalAmount'      => $totalAmount,
            'attributes'       => $attributes,
            'attributesValues' => $attributesValues,
            'lastOrder'        => $lastOrder,
        ];
    }
}
