<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;

class CompareBox extends Component
{
    protected $listeners = ['addedCompare' => 'render', 'removedCompare' => 'render'];

    public function remove($id)
    {
        $compare = session()->get('compare_products', []);
        $compare = array_values(array_diff($compare, [$id]));
        session()->put('compare_products', $compare);

        $this->emit('removedCompare');
    }

    public function clearAll()
    {
        session()->forget('compare_products');
        $this->emit('removedCompare');
    }

    public function render()
    {
        $compareIds = session()->get('compare_products', []);

        // Load product info from DB
        $products = \App\Models\Product::whereIn('id', $compareIds)->get();

        return view('livewire.frontend.compare.compare-box', [
            'products' => $products,
            'count'    => count($compareIds),
        ]);
    }
}
