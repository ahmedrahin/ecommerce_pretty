<?php

namespace App\Http\Livewire\Frontend\Product;

use Livewire\Component;
use App\Models\Review;

class ReviewCount extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function render()
    {
        // Get the reviews for the given product
        $reviews = Review::where('product_id', $this->productId)->get();

        // Calculate the average rating
        $averageRating = $reviews->avg('rating');

        return view('livewire.frontend.product.review-count', [
            'averageRating' => $averageRating,
            'reviewsCount' => $reviews->count(),
            'reviews' => $reviews,
        ]);
    }

    protected $listeners = ['load' => '$refresh']; 
}
