<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\WebsiteSetting;

class Customersetting extends Component
{
    public $guest_checkout; 
    public $login_attemp;
    public $cart_session;
    public $login_session;

    public function mount()
    {
        // Load existing settings or defaults
        $settings = WebsiteSetting::first();
        $this->guest_checkout = $settings->guest_checkout ?? true;
        $this->login_attemp  = $settings->login_attemp ?? 1;
        $this->login_session  = $settings->login_session ?? 1;
        $this->cart_session  = $settings->cart_session ?? 1;
    }

    public function update()
    {
        // Validate inputs
        $this->validate([
            'guest_checkout' => 'required|boolean',
            'login_attemp' => 'required|integer|min:1',
            'cart_session' => 'required|integer|min:1',
            'login_session' => 'required|integer|min:1',
        ]);


        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'guest_checkout' => $this->guest_checkout,
                'login_attemp' => $this->login_attemp,
                'cart_session' => $this->cart_session,
                'login_session' => $this->login_session,
            ]
        );

        $this->emit('success', __('Settings Updated successfully.'));
    }


    public function render()
    {
        return view('livewire.settings.customersetting');
    }
}
