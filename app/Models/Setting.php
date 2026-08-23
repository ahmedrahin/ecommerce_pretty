<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['logo', 'website_logo', 'website_footer_logo', 'dark_logo', 'fav_icon', 'site_title', 'number1', 'number2', 'email', 'state', 'address', 'facebook', 'instagram', 'youtube', 'whatsapp'];
}
