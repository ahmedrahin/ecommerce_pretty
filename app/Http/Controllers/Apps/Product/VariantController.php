<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\{ProductStock,Product};
use Illuminate\Http\Request;


class VariantController extends Controller
{
    public function index()
    {
        $attributes = Attribute::orderBy('id', 'desc')->get();
        return view('pages.apps.product.variation.list', compact('attributes'));
    }

    //attrbute value
    public function getAttributeValue($attribute_id) {
        $attributeValues = AttributeValue::where('attr_id', $attribute_id)->get();
        return response()->json($attributeValues);
    }

    public function productVariants(Product $product)
    {
        $stocks = ProductStock::where('product_id', $product->id)
            ->with('attributeOptions.attribute', 'attributeOptions.attributeValue')
            ->get()
            ->map(function ($stock) {
                $label = $stock->attributeOptions->map(function ($opt) {
                    return $opt->attribute->attr_name . ': ' . $opt->attributeValue->attr_value;
                })->implode(' / ');

                return [
                    'id'              => $stock->id,
                    'variation_label' => $label ?: 'Default',
                    'quantity'        => $stock->quantity,
                ];
            });

        return response()->json($stocks);
    }

    public function toggleVariant(Request $request, $stockId)
    {
        $stock = ProductStock::findOrFail($stockId);
        $stock->update([
            'is_disabled' => $stock->is_disabled ? 0 : 1
        ]);

        return response()->json([
            'success'     => true,
            'is_disabled' => $stock->is_disabled,
            'message'     => $stock->is_disabled ? 'Variant disabled' : 'Variant enabled',
        ]);
    }

}
