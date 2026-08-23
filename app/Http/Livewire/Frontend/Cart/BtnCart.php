<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;

class BtnCart extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $cart = session()->get('cart', []);
        $this->cartCount = collect($cart)->sum('quantity');
    }

    public function render()
    {
        return view('livewire.frontend.cart.btn-cart', [
            'cartCount' => $this->cartCount,
        ]);
    }
}
