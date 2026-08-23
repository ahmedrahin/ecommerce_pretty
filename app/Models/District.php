<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function upazila()
    {
        return $this->hasMany(Upazila::class);
    }

    public function state()
    {
        return $this->hasMany(Upazila::class);
    }

    // Add a model event to handle related records on deletion
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($district) {
            $district->state()->delete();
        });

        static::restoring(function ($district) {
            $district->state()->restore();
        });
    }
}
