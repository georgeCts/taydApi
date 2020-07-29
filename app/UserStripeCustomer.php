<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UserStripeCustomer extends Model
{
    protected $table = 'users_stripe_customers';
    protected $fillable =  [
        'user_id',
        'stripe_customer_token',
        'created_at',
        'updated_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
