<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\Subscription as SubscriptionModel;
use Illuminate\Validation\ValidationException;

class Subscription extends Component
{
    public $email;

    public function submit()
    {
        try {
            $this->validate([
                'email' => 'required|email|unique:subscriptions,email',
            ]);

            SubscriptionModel::create([
                'email' => $this->email,
            ]);

            $this->reset('email');

            $this->emit('success', 'Thanks for subscribing!');
        } catch (ValidationException $e) {
            $message = $e->validator->errors()->first();
            $this->emit('error', $message);
        }
    }

    public function render()
    {
        return view('livewire.frontend.subscription');
    }
}
