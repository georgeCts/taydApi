<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PropertyTypePrice;
use DB;

class UserPropertyDistribution extends Model
{
    protected $table = 'users_properties_distribution';
    protected $fillable =  [
        'user_property_id',
        'property_type_price_id',
        'quantity'
    ];

    public function userProperty() {
        return $this->belongsTo(UserProperty::class);
    }

    public function propertyTypePrice() {
        return $this->belongsTo(PropertyTypePrice::class);
    }
}
