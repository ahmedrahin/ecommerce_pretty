<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = ['attr_name', 'status'];

    public function AttributeValue(){
        return $this->hasMany(AttributeValue::class, 'attr_id');
    }

    // Add a model event to handle related records on deletion
    
}
