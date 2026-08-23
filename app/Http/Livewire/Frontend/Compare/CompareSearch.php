<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;
use App\Models\Product;

class CompareSearch extends Component
{
    public $search = '';
    public $selectedId;

    public $suggestions = [];


    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
            $this->suggestions = Product::activeProducts()->where('name', 'like', '%' . $this->search . '%')
                ->take(5)
                ->get();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectProduct($id)
    {
        $compare = session()->get('compare_products', []);

        // Replace old ID with new ID in session
        $compare = array_diff($compare, [(string)$this->selectedId, (int)$this->selectedId]);
        $compare[] = $id;
        $compare = array_values(array_unique($compare));

        session()->put('compare_products', $compare);

        // Redirect to compare route with updated ids
        $ids = implode(',', $compare);
        return redirect()->route('compare', ['ids' => $ids]);
    }


    public function render()
    {
        return view('livewire.frontend.compare.compare-search');
    }
}
