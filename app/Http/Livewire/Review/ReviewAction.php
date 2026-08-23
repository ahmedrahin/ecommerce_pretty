<?php

namespace App\Http\Livewire\Review;

use Livewire\Component;
use App\Models\Review;

class ReviewAction extends Component
{

    protected $listeners = [
        'delete_review'  => 'delete',
    ];

    public function delete($id)
    {
        // Find the coupon by ID
        $data = Review::findOrFail($id);
        $data->delete();

        $this->emit('info', __('Review has been deleted.'));
    }

    public function render()
    {
        return view('livewire.review.review-action');
    }
}
