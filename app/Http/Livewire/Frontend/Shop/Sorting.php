<?php

namespace App\Http\Livewire\Frontend\Shop;

use Livewire\Component;

class Sorting extends Component
{
    public $sortOrder = '';
    public $showPerPage = '';

    protected $queryString = [
        'sortOrder' => ['except' => ''], 
        'showPerPage' => ['except' => ''], 
    ];

    public function updatedSortOrder($value)
    {
        $this->emit('sortOrderUpdated', $value);
    }

    public function updatedShowPerPage($value)
    {
        $this->emit('updatedShowPerPage', $value);
    }

    
    public function render()
    {
        return view('livewire.frontend.shop.sorting');
    }
}
