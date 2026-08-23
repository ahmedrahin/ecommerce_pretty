<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
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

}
