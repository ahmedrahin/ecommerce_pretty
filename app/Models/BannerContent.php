<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerContent extends Model
{
    protected $guarded = [];
    
    use HasFactory;

     public function slider()
    {
        return $this->belongsTo(HomeSlider::class);
    }
}
