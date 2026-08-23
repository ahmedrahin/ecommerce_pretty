<?php

namespace App\Http\Livewire\Report;

use Livewire\Component;
use App\Models\ProductStockManage;
use App\Models\Product;

class StockIn extends Component
{
   
   protected $listeners = [
        'delete' => 'delete',
    ];

    public function delete($id)
    {
        $stock = ProductStockManage::findOrFail($id);

        // Decrease the product's quantity
        $stock->product->decrement('quantity', $stock->quantity);
        $stock->delete();

        $this->emit('info', __('Stock data has been deleted.'));
    }



    public function render()
    {
        return view('livewire.report.stock-in');
    }
}
