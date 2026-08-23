<?php

namespace App\Http\Livewire\Content;

use Livewire\Component;
use App\Models\HomeSlider;
use App\Models\BannerContent;

class BannerConter extends Component
{
    public $slider_id;
    public $title;
    public $description;
    public $link;

    protected $rules = [
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'link' => 'nullable|string',
    ];

   
    public function openModal($id)
    {
        $this->slider_id = $id;

        $content = BannerContent::where('home_slider_id', $id)->first();

        if ($content) {
            $this->title = $content->title ?? null;
            $this->description = $content->description ?? null;
            $this->link = $content->link ?? null;
        } else {
            $this->title = null;
            $this->description = null;
            $this->link = null;
        }
    }

    public function submit()
    {
        $this->validate();

        BannerContent::updateOrCreate(
            ['home_slider_id' => $this->slider_id],
            [
                'title' => $this->title,
                'description' => $this->description,
                'link' => $this->link
            ]
        );

        $this->emit('success', __('Content update successfully.'));
    }


    public function render()
    {
        $sliders = HomeSlider::all();
        return view('livewire.content.banner-conter', compact('sliders'));
    }
}
