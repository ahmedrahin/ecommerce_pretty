<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\WebsiteSetting;

class Productsetting extends Component
{
    public $product_count_enabled;
    public $item_per_page;
    public $show_review;
    public $allow_guest_reviews;
    public $show_wishlist;
    public $show_expire_date;
    public $allow_review;
    public $allow_guest_review;
    public $guest_checkout;
    public $buy_now_button;

    public function mount()
    {
        // Load existing settings or defaults
        $settings = WebsiteSetting::first();
        $this->product_count_enabled = $settings->product_count_enabled ?? true;
        $this->item_per_page = $settings->item_per_page ?? 20;
        $this->allow_review = $settings->allow_review ?? true;
        $this->show_review = $settings->show_review ?? true;
        $this->allow_guest_review = $settings->allow_guest_review ?? true;
        $this->show_wishlist = $settings->show_wishlist ?? true;
        $this->show_expire_date = $settings->show_expire_date ?? true;
        $this->buy_now_button = $settings->buy_now_button ?? true;

        $this->guest_checkout = $settings->guest_checkout ?? true;
    }

    public function update()
    {
        // Validate inputs
        $this->validate([
            'product_count_enabled' => 'required|boolean',
            'show_review' => 'required|boolean',
            'allow_guest_review' => 'required|boolean',
            'item_per_page' => 'required|integer|min:5'
        ]);


        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'product_count_enabled' => $this->product_count_enabled,
                'item_per_page' => $this->item_per_page,
                'show_review' => $this->show_review,
                'show_wishlist' => $this->show_wishlist,
                'show_expire_date' => $this->show_expire_date,
                'allow_review' => $this->allow_review,
                'allow_guest_review' => $this->allow_guest_review,

                'guest_checkout' => $this->guest_checkout,
                'buy_now_button' => $this->buy_now_button,
            ]
        );

        $this->emit('success', __('Settings Updated successfully.'));
    }


    public function render()
    {
        return view('livewire.settings.productsetting');
    }
}
