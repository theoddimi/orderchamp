<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    
    public function details()
    {
        return $this->hasMany(UserDetails::class);
    }
    
    public function discounts(){
        return $this->belongsToMany(Discount::class, 'discount_user_token', 'user_id', 'discount_id')->withPivot('active', 'token');
    }
    
    public function activeDiscounts(){
        return $this->belongsToMany(Discount::class, 'discount_user_token', 'user_id', 'discount_id')->withPivot('active', 'token')
                ->wherePivot('active', 1);
    }
    
    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
}
