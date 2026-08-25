<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;
use App\Models\Setting;

class FollowOn extends Component
{   
    public $setting;
    public $facebook;
    public $instagram;
    public $youtube;
    public $whatsapp;

    public function mount()
    {
        $this->setting = Setting::firstOrCreate(['id' => 1]);

        $this->facebook  = $this->setting->facebook ?? null;
        $this->instagram = $this->setting->instagram ?? null;
        $this->youtube   = $this->setting->youtube ?? null;
        $this->whatsapp  = $this->setting->whatsapp ?? null;
    }

    protected $rules = [
        'facebook'  => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?facebook\.com\/[a-zA-Z0-9(\.\?)?]/'],
        'instagram' => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9(_\.\?)?]/'],
        // 'youtube' => ['nullable', 'url', 'regex:/^(https?:\/\/)?(www\.)?youtube\.com\/(channel|c|user)\/[a-zA-Z0-9_\-]+/'],
    ];

    public function update($id = 1)
    {
        $this->validate();
        $updateData = Setting::firstOrCreate(['id' => $id]);
        $updateData->update([
            'facebook'  => $this->facebook ?? null,
            'instagram' => $this->instagram ?? null,
            'youtube'   => $this->youtube ?? null,
            'whatsapp'  => $this->whatsapp ?? null,
        ]);

        $this->emit('success', __('Follow on settings Updated successfully.'));
    }

    public function render()
    {
        return view('livewire.settings.follow-on');
    }
}
