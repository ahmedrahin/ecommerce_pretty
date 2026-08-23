<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function content()
    {
        return $this->hasOne(BannerContent::class, 'home_slider_id');
    }
}
