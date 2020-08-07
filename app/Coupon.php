<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserCoupon;

class Coupon extends Model
{
    protected $table    = 'coupons';
    protected $fillable = [
        'code',
        'title',
        'description',
        'value',
        'free_service',
        'discount_service',
        'start',
        'end'
    ];

    public function userCoupon() {
        return $this->hasMany(UserCoupon::class);
    }
}
