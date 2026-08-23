<?php

namespace App\Http\Livewire\Frontend\Compare;

use Livewire\Component;
use App\Models\Product;
use App\Models\FullComparison;

class FullCompare extends Component
{

    public $first_product_id = '';
    public $second_product_id = '';
    public $products = [];

     protected $listeners = [
        'open_add_modal' => 'openAddModal',
        'delete' => 'delete',
    ];

    public function mount()
    {
        $this->products = Product::select('id','name')->orderBy('name')->get();
    }

    public function submit()
    {
        $this->validate([
            'first_product_id'  => 'required|different:second_product_id|exists:products,id',
            'second_product_id' => 'required|different:first_product_id|exists:products,id',
        ], [
            'different' => 'First and second product must be different.',
        ]);

        FullComparison::create([
            'first_product_id'  => $this->first_product_id,
            'second_product_id' => $this->second_product_id,
        ]);

        
       $this->emit('success', __('Created successfully.'));
    }

     private function resetForm()
    {
       $this->reset(['first_product_id','second_product_id']);
    }

    public function openAddModal()
    {
        $this->resetForm();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function delete($id)
    {
        $comparison = FullComparison::find($id);
        if ($comparison) {
            $comparison->delete();
            $this->emit('success', __('Deleted successfully.'));
        }  
    }     

    public function render()
    {
        $products = Product::orderBy('name','asc')->activeProducts()->get();
        $data = FullComparison::with(['firstProduct', 'secondProduct'])->orderBy('id','desc')->get();
        return view('livewire.frontend.compare.full-compare', compact('products', 'data'));
    }
}
