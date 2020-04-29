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
        'is_predetermined',
        'rooms_qty',
        'bathrooms_qty',
        'living_room_qty',
        'dinning_room_qty',
        'kitchen_qty',
        'garage_qty',
        'backyard_qty',
        'floors_qty',
        'property_type_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function propertyType() {
        return $this->hasOne(PropertyType::class, 'id', 'property_type_id');
    }
}
