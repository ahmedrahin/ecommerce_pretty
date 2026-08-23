<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'status',
        'active',
        'address_line1',
        'address_line2',
        'division_id',
        'district_id',
        'zipCode',
        'phone',
        'isAdmin',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function division(){
        return $this->belongsTo(District::class, 'division_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function Products()
    {
        return $this->hasMany(Product::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

}
