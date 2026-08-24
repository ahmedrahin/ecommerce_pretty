<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Models\ProductStockManage;
use App\Models\ProductStock;
use App\Models\Product;

class StockIn extends Component
{
   
   protected $listeners = [
        'delete' => 'delete',
    ];

    public function delete($id)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($id) {
            $stock = ProductStockManage::findOrFail($id);

            // If it's a variation stock entry
            if ($stock->product_stock_id) {
                $productStock = ProductStock::find($stock->product_stock_id);
                if ($productStock) {
                    $productStock->decrement('quantity', min($productStock->quantity, $stock->quantity));
                }

                if ($stock->product) {
                    $stock->product->update([
                        'quantity' => ProductStock::where('product_id', $stock->product_id)->sum('quantity')
                    ]);
                }
            } else if ($stock->product) {
                // Single product stock entry
                $stock->product->decrement('quantity', min($stock->product->quantity, $stock->quantity));
            }

            $stock->delete();
        });

        $this->emit('info', __('Stock data has been deleted.'));
    }



    public function render()
    {
        return view('livewire.report.stock-in');
    }
}
