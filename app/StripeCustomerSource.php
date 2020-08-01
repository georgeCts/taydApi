<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserStripeCustomer;

class StripeCustomerSource extends Model
{
    protected $table    = 'stripe_customers_sources';
    protected $fillable =  [
        'user_stripe_customer_id',
        'stripe_customer_source_token',
        'is_predetermined',
        'created_at',
        'updated_at'
    ];

    public function userStripeCustomer() {
        return $this->belongsTo(UserStripeCustomer::class);
    }
}
