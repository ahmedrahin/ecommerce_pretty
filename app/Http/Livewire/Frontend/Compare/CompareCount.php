<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;

class CompareCount extends Component
{
   public $count = 0;

    protected $listeners = ['addedCompare' => 'updateCount', 'removedCompare' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $this->count = count(session()->get('compare_products', []));
    }

    public function render()
    {
        return view('livewire.frontend.compare.compare-count');
    }
}
