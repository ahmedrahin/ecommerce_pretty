<?php

namespace App\Http\Controllers\Apps\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\Report\StockOutDataTable;
use App\DataTables\Report\LowStockDataTable;
use App\DataTables\Report\StockInDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProductStockManage;
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

    public function storeStock(Request $request){
         $request->validate([
            'product_id' => 'required',
            'date' => 'required',
            'quantity' => 'required|numeric|min:1',
            'wholesale_price' => 'required|numeric|min:1',
        ], [
            'date.required' => 'Select a date',
            'product_id.required' => 'Select a product',
        ]);

        $product = Product::find($request->product_id);
        $data = [
            'product_id'    => $request->product_id,
            'quantity'      => $request->quantity,
            'stocked_at'    => $request->date ?? now(),
            'product_price' => $product->base_price ?? 0,
            'wholesale_price' => $request->wholesale_price,
            'total_amount' => $request->wholesale_price * $request->quantity,
            'note'          => $request->note,
            'stock'          => 'stock_in',
        ];

        ProductStockManage::create($data);
        $product->increment('quantity', $data['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Product stock in successfully'
        ]);

    }

}
