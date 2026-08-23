<?php

namespace App\Http\Livewire\Frontend\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ProductQuestion extends Component
{
    use WithPagination; // <-- Add this

    public $productId;
    public $user_id;
    public $question;

    protected $paginationTheme = 'bootstrap'; // optional if using Bootstrap

    protected $listeners = [
        'open_add_modal' => 'openAddModal',
    ];

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->user_id = auth()->id();
    }

    public function submit()
    {
        if (!auth()->check()) {
            return;
        }   

        $this->validate([
            'question' => 'required'
        ]);

        $question = Question::create([
            'question' => $this->question,
            'user_id' => $this->user_id,
            'product_id' => $this->productId,
        ]);

        \App\Models\Notification::create([
            'type' => 'question',
            'question_id' => $question->id,
        ]);

        $this->emit('success', __('Your question has been submitted. you will get answer by email'));
        $this->resetForm();
        $this->resetPage(); 
    }

    private function resetForm()
    {
        $this->reset(['question']);
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        $questionCount = Question::where('product_id', $this->productId)->whereNotNull('answer')->count();
        $questions = Question::where('product_id', $this->productId)->whereNotNull('answer')->latest()->paginate(5);

        return view('livewire.frontend.product.product-question', compact('questions', 'questionCount'));
    }
}
