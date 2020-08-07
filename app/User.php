<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\UserInfo;
use App\UserDocument;
use App\UserProperty;
use App\UserStripeCustomer;
use App\UserCoupon;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function info(){
        return $this->hasOne(UserInfo::class);
    }

    public function documents() {
        return $this->hasMany(UserDocument::class);
    }

    public function properties() {
        return $this->hasMany(UserProperty::class);
    }

    public function coupons() {
        return $this->hasMany(UserCoupon::class);
    }

    public function stripeCustomer() {
        return $this->hasOne(UserStripeCustomer::class);
    }

}
