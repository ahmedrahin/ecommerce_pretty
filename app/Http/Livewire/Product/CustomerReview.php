<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use App\Models\Review;

class CustomerReview extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->productId)->get();
        return view('livewire.product.customer-review', [
            'reviews' => $reviews,
        ]);
    }
}
