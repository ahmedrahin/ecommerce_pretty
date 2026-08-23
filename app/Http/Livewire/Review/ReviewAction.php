<?php

namespace App\Http\Livewire\Review;

use Livewire\Component;
use App\Models\Review;

class ReviewAction extends Component
{

    protected $listeners = [
        'delete_review'  => 'delete',
        'update_featured'  => 'updateFeatured',
    ];

     public function updateFeatured($id, $value)
    {
        $value = $value == 1 ? 1 : 0;

        $data = Review::find($id);

        if ($data) {
            $data->featured = $value;
            $data->save();

            session()->flash('success', 'Featured status updated.');
        } else {
            session()->flash('error', 'Category not found.');
        }
    }

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
