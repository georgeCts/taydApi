<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\PropertyType;

class UserProperty extends Model
{
    protected $table = 'users_properties';
    protected $fillable =  [
        'user_id',
        'name',
        'latitude',
        'altitude',
        'rooms_qty',
        'bathrooms_qty',
        'has_living_room',
        'has_dinning_room',
        'has_kitchen',
        'has_garage',
        'has_backyard',
        'floors_qty',
        'property_type_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function propertyType() {
        return $this->hasOne(PropertyType::class);
    }
}
