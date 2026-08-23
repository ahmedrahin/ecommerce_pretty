<?php

namespace App\Http\Controllers\Apps\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PrintfulProductController extends Controller
{
    public function printful()
    {
        $response = Http::withToken(config('services.printful.key'))->get('https://api.printful.com/store/products');

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Printful API error',
                'error' => $response->body()
            ], 500);
        }

        $products = collect($response->json('result'))->map(function ($item) {
            return [
                'store_product_id' => $item['id'],
                'name'             => $item['name'],
                'thumbnail'        => $item['thumbnail_url'],
                'variants_count'   => $item['variants'],
                'synced'           => (bool) $item['synced'],
            ];
        });

        return view('pages.apps.product.printful.printful', compact('products'));
    }

    public function printfulProductDetails($storeProductId)
    {
        $response = Http::withToken(config('services.printful.key'))
            ->get("https://api.printful.com/store/products/{$storeProductId}");

        if (!$response->successful()) {
            abort(404, 'Printful product not found');
        }

        $product = $response->json('result.sync_product');
        $variants = collect($response->json('result.sync_variants'))->map(function ($v) {
            return [
                'printful_variant_id' => $v['variant_id'],
                'name'  => $v['name'],
                'size'  => $v['size'] ?? null,
                'color' => $v['color'] ?? null,
                'price' => $v['retail_price'],
                'sku'   => $v['sku'],
                'thread_colors' => collect($v['options'])->firstWhere('id','thread_colors')['value'] ?? null,
                'embroidery_type' => collect($v['options'])->firstWhere('id','embroidery_type')['value'] ?? null,
            ];
        });

        return view('pages.apps.product.printful.show', compact('product','variants'));
    }

}
