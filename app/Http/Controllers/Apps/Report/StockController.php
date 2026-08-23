<?php

namespace App\Http\Controllers\Apps\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Report\StockOutDataTable;
use App\DataTables\Report\LowStockDataTable;
use App\DataTables\Report\StockInDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\{ProductStockManage, ProductStock};
use App\Models\Product;

class StockController extends Controller
{
    public function stockOut(StockOutDataTable $dataTable){
      return $dataTable->render('pages.apps.report.stock.stockout');
    }

    public function lowStock(LowStockDataTable $dataTable){
      return $dataTable->render('pages.apps.report.stock.lowstock');
    }

    public function stockin(StockInDataTable $dataTable, $year = null, $month = null){
          $year = $year ?? Carbon::now()->year;
          $month = $month ?? Carbon::now()->month;
          $getMonth = ucfirst($month) . "-" . $year;

          $isMonthExists = DB::table('product_stock_manages')
                        ->whereYear('stocked_at', $year)
                        ->whereMonth('stocked_at', $month)
                        ->exists();

          $allYear = DB::table('product_stock_manages')->where('stock', 'stock_in')
                         ->selectRaw('DISTINCT YEAR(stocked_at) as year')
                         ->orderBy('year', 'desc')
                         ->pluck('year');

          $monthis = Carbon::parse("1 $month")->month;
          $data = ProductStockManage::whereYear('stocked_at', $year)
                       ->whereMonth('stocked_at', $monthis)
                       ->where('stock', 'stock_in')
                       ->get();


          return $dataTable->render('pages.apps.report.stock.stockin', compact('data', 'month', 'getMonth', 'isMonthExists', 'allYear', 'year'));
    }

    public function addStock(){
         return view('pages.apps.report.stock.add-stock');
    }

    public function storeStock(Request $request)
    {
         
        $hasVariants = !empty($request->variants);

        $request->validate([
            'product_id'          => 'required|exists:products,id',
            'date'                => 'required|date',
            'wholesale_price'     => 'nullable|numeric|min:0',
            'variants'            => $hasVariants ? 'required|array|min:1' : 'nullable',
            'variants.*.id'       => $hasVariants ? 'required|exists:product_stocks,id' : 'nullable',
            'variants.*.quantity' => $hasVariants ? 'required|numeric|min:1' : 'nullable',
            'quantity'            => !$hasVariants ? 'required|numeric|min:1' : 'nullable',
        ], [
            'quantity.required'            => 'Quantity is required',
            'quantity.min'                 => 'Quantity must be greater than 0',
            'variants.required'            => 'Select at least one variation',
            'variants.min'                 => 'Select at least one variation',
            'variants.*.quantity.required' => 'Quantity is required for selected variation',
            'variants.*.quantity.min'      => 'Quantity must be greater than 0',
            'variants.*.id.exists'         => 'Selected variation is invalid',
            'date.required'                => 'Select a date',
            'product_id.required'          => 'Select a product',
        ]);

        $product = Product::findOrFail($request->product_id);

        DB::beginTransaction();
        try {
            $wholesalePrice = $request->wholesale_price ? (float) $request->wholesale_price : (float) $product->base_price;
            if ($hasVariants) {
                foreach ($request->variants as $variantData) {
                    $productStock = \App\Models\ProductStock::with(
                        'attributeOptions.attribute',
                        'attributeOptions.attributeValue'
                    )->findOrFail($variantData['id']);

                    $variationLabel = $productStock->attributeOptions->map(function ($opt) {
                        return $opt->attribute->attr_name . ': ' . $opt->attributeValue->attr_value;
                    })->implode(' / ');

                    ProductStockManage::create([
                        'product_id'       => $product->id,
                        'product_stock_id' => $productStock->id,
                        'variation_label'  => $variationLabel,
                        'quantity'         => $variantData['quantity'],
                        'stocked_at'       => $request->date,
                        'product_price'    => $product->base_price ?? 0,
                        'wholesale_price'  => $wholesalePrice,
                        'total_amount'     => ($wholesalePrice ?? 0) * $variantData['quantity'],
                        'stock'            => 'stock_in',
                    ]);

                    $productStock->increment('quantity', $variantData['quantity']);
                }

                $product->update([
                    'quantity' => \App\Models\ProductStock::where('product_id', $product->id)->sum('quantity')
                ]);

            } else {
                ProductStockManage::create([
                    'product_id'       => $product->id,
                    'product_stock_id' => null,
                    'variation_label'  => null,
                    'quantity'         => $request->quantity,
                    'stocked_at'       => $request->date,
                    'product_price'    => $product->base_price ?? 0,
                    'wholesale_price'  => $wholesalePrice,
                    'total_amount'     => ($wholesalePrice ?? 0) * $request->quantity,
                    'stock'            => 'stock_in',
                ]);

                $product->increment('quantity', $request->quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

}
