<?php

namespace App\Http\Livewire\Frontend\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Review;
use illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ProductReview extends Component
{
     use WithPagination;
    public $productId;
    public $name;
    public $email;
    public $user_id;
    public $comment;
    public $rating;

    protected $paginationTheme = 'bootstrap';

    // Event listeners
    protected $listeners = [
        'open_add_modal'    => 'openAddModal',
        'updatedRating'     => 'updatedRating'
    ];

    public function updatedRating($value){
        $this->rating = $value;
    }

    public function mount($productId)
    {
        $this->productId = $productId;
        if (Auth::check()) {
            $this->user_id = Auth::id();
            $this->name = Auth::user()->name;
            $this->email = Auth::user()->email;
        }
    }

    public function submit(){
         // Validation rules
         $rules = [
            'name'  => 'required',
            'email' => 'required|email',
            'rating' => 'required',
            'comment' => 'required'
        ];

        // Custom messages
        $messages = [];

        $this->validate($rules, $messages);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating ?? 2,
            'comment' => $this->comment,
            'user_id' => $this->user_id,
            'product_id' => $this->productId,
        ];

        // store the review
        Review::create($data);
        $this->emit('success', __('Thanks for submitted your review'));
        $this->resetForm();
        $this->emit('load');
    }

     // Reset form fields
     private function resetForm()
     {
        if (!Auth::check()) {
            $this->reset(['name', 'email', 'rating', 'comment']);
        } else {
            $this->reset(['rating', 'comment']);
        }
     }

     public function openAddModal()
     {
         $this->resetForm();
         $this->resetErrorBag();
         $this->resetValidation();
     }

     // Handle component hydration
     public function updated($propertyName)
     {
         $this->resetErrorBag($propertyName);
     }

     public function delete($reviewId)
    {
        $review = Review::find($reviewId);

        if ($review && $review->user_id == Auth::id()) {
            $review->delete();
            $this->emit('info', __('The review has been deleted'));
            $this->emit('load');
        }
    }

    public function hydrate()
    {
        // Reset error bag and validation
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        $product = Product::find($this->productId);

        // Use paginate instead of get()
        $reviews = Review::where('product_id', $this->productId)
            ->latest()
            ->paginate(5);

        $userReview = Auth::check()
            ? Review::where('product_id', $this->productId)
                ->where('user_id', Auth::id())
                ->first()
            : null;

        $totalReviews = $reviews->total();
        $averageRating = $reviews->avg('rating') ?? 0;

        $ratingBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('product_id', $this->productId)->where('rating', $i)->count();
            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
            $ratingBreakdown[$i] = $percentage;
        }

        return view('livewire.frontend.product.product-review', [
            'product' => $product,
            'reviews' => $reviews,
            'userReview' => $userReview,
            'averageRating' => $averageRating,
            'totalReviews' => $totalReviews,
            'ratingBreakdown' => $ratingBreakdown,
        ]);
    }


}
