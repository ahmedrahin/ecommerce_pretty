<?php

namespace App\Http\Livewire\Frontend\Cart;

use Livewire\Component;

class Btnshopping extends Component
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
        $this->cartCount = count($cart); 
    }

    public function render()
    {
        return view('livewire.frontend.cart.btnshopping', [
            'cartCount' => $this->cartCount,
        ]);
    }
}
