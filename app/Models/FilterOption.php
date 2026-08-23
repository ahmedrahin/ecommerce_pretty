<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterOption extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function values(){
        return $this->hasMany(FilterOptionValue::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_filters', 'filter_option_id', 'category_id');
    }

}
