<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\UserProperty;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable =  [
        'request_user_id',
        'provider_user_id',
        'user_property_id',
        'stripe_customer_source_id',
        'is_accepted',
        'dt_start',
        'dt_finish',
        'has_consumables',
        'cost',
        'is_canceled',
        'dt_canceled'
    ];

    public function requester() {
        return $this->hasOne(User::class, 'id', 'request_user_id');
    }

    public function provider() {
        return $this->hasOne(User::class, 'id', 'provider_user_id');
    }

    public function property() {
        return $this->hasOne(UserProperty::class, 'id', 'user_property_id');
    }
}
