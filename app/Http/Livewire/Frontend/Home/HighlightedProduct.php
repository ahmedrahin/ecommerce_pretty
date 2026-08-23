<?php

namespace App\Http\Livewire\Frontend\Home;

use Livewire\Component;
use App\Models\Product;
use Carbon\Carbon;

class HighlightedProduct extends Component
{
    public $take = 10;

    public function render()
    {
        $newArrivales = Product::activeProducts()
        ->orderBy('id', 'desc')
        ->where('is_new', 1)
        ->take($this->take)
        ->get();

    $featured = Product::activeProducts()
        ->orderBy('id', 'desc')
        ->where('is_featured', 1)
        ->take($this->take)
        ->get();

    $selling = Product::activeProducts()
        ->withCount('orderItems')
        ->having('order_items_count', '>', 0)
        ->orderBy('order_items_count', 'desc')
        ->take($this->take)
        ->get();

    $productsWithHighReviews = Product::activeProducts()
        ->with('reviews')
        ->withAvg('reviews', 'rating')
        ->having('reviews_avg_rating', '>=', 4)
        ->orderBy('reviews_avg_rating', 'desc')
        ->take(10)
        ->get();



        return view('livewire.frontend.home.highlighted-product', compact('newArrivales', 'featured', 'selling', 'productsWithHighReviews'));
    }
}
