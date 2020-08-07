<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Coupon;

class UserCoupon extends Model
{
    protected $table    = 'users_coupons';
    protected $fillable = [
        'user_id',
        'coupon_id',
        'active',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class, 'id', 'coupon_id');
    }
}
