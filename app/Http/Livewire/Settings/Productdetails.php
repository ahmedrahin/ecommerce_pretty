<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\WebsiteSetting;

class Productdetails extends Component
{
    public $product_info;
    public $show_expire;
    public $share;
    public $ask_qustion;
    public $show_size_chart;
    public $show_order_count;

    public function mount()
    {
        // Load existing settings or defaults
        $settings = WebsiteSetting::first();
        $this->product_info = $settings->product_info ?? true;
        $this->show_expire = $settings->show_expire ?? 0;
        $this->share = $settings->share ?? true;

        $this->ask_qustion = $settings->ask_qustion ?? true;
        $this->show_size_chart = $settings->show_size_chart ?? true;
        $this->show_order_count = $settings->show_order_count ?? true;

    }

    public function update()
    {
        WebsiteSetting::updateOrCreate(
            ['id' => 1],
            [
                'show_order_count' => $this->show_order_count,
                'show_size_chart' => $this->show_size_chart,
                'ask_qustion' => $this->ask_qustion,
                'share' => $this->share,
                'product_info' => $this->product_info,
                'show_expire' => $this->show_expire ?? 0
            ]
        );

        $this->emit('success', __('Settings Updated successfully.'));
    }

    public function render()
    {
        return view('livewire.settings.productdetails');
    }
}
