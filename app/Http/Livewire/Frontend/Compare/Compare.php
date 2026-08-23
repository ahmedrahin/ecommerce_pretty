<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;
use App\Models\Product;

class Compare extends Component
{
    // Search inputs
    public string $query1 = '';
    public string $query2 = '';

    // Result lists
    public array $results1 = [];
    public array $results2 = [];

    // Selected product ids + labels
    public ?int $selectedId1 = null;
    public ?int $selectedId2 = null;
    public string $selectedLabel1 = '';
    public string $selectedLabel2 = '';

    // Dropdown visibility
    public bool $showDropdown1 = false;
    public bool $showDropdown2 = false;

    public int $limit = 10;

    /** When first input changes */
    public function updatedQuery1()
    {
        $this->selectedId1 = null;
        $this->selectedLabel1 = '';
        $this->results1 = $this->runSearch($this->query1);
        $this->showDropdown1 = !empty($this->query1) && !empty($this->results1);
    }

    /** When second input changes */
    public function updatedQuery2()
    {
        $this->selectedId2 = null;
        $this->selectedLabel2 = '';
        $this->results2 = $this->runSearch($this->query2);
        $this->showDropdown2 = !empty($this->query2) && !empty($this->results2);
    }

    /** Core search: by name or tags (re-using your logic) */
    protected function runSearch(string $term): array
    {
        $term = trim($term);
        if ($term === '') return [];

        return Product::activeProducts()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhereHas('tags', fn($t) => $t->where('name', 'like', "%{$term}%"));
            })
            ->orderBy('name')
            ->limit($this->limit)
            ->get(['id', 'name'])
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->name])
            ->toArray();
    }

    /** Select one of the search results for either slot */
    public function selectProduct(string $slot, int $productId): void
    {
        $product = Product::select('id','name')->find($productId);
        if (!$product) return;

        if ($slot === 'left') {
            $this->selectedId1 = $product->id;
            $this->selectedLabel1 = $product->name;
            $this->query1 = $product->name;
            $this->results1 = [];
            $this->showDropdown1 = false;
        } else {
            $this->selectedId2 = $product->id;
            $this->selectedLabel2 = $product->name;
            $this->query2 = $product->name;
            $this->results2 = [];
            $this->showDropdown2 = false;
        }
    }

    /** Clear a selection */
    public function clearSelection(string $slot): void
    {
        if ($slot === 'left') {
            $this->selectedId1 = null;
            $this->selectedLabel1 = '';
            $this->query1 = '';
            $this->results1 = [];
            $this->showDropdown1 = false;
        } else {
            $this->selectedId2 = null;
            $this->selectedLabel2 = '';
            $this->query2 = '';
            $this->results2 = [];
            $this->showDropdown2 = false;
        }
    }

    public function viewComparison()
    {
        if (empty($this->selectedId1) || empty($this->selectedId2)) {
            $this->emit('error', 'Please select two products to compare.');
            return;
        }

        session()->forget('compare_products');

        return redirect()->route('compare', [
            'ids' => $this->selectedId1 . ',' . $this->selectedId2
        ]);
    }

    public function render()
    {
        return view('livewire.frontend.compare.compare');
    }
}
