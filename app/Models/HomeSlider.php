<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class HomeSlider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('home_banners'));
        static::deleted(fn() => Cache::forget('home_banners'));
    }
}
