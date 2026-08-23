<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFilterValue extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function filterOption()
    {
        return $this->belongsTo(FilterOption::class);
    }

    public function value()
    {
        return $this->belongsTo(FilterOptionValue::class, 'filter_option_value_id');
    }
}
