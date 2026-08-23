<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;

class AddCompare extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCompare()
    {
        $compare = session()->get('compare_products', []);

        if (!in_array($this->productId, $compare)) {
            $compare[] = $this->productId;
            session()->put('compare_products', $compare);
        }

        $this->emit('success', 'Product added to compare list');
        $this->emit('addedCompare');
    }


    public function render()
    {
        return view('livewire.frontend.compare.add-compare');
    }

}
