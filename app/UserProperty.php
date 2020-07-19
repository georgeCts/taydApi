<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\PropertyType;
use App\UserPropertyDistribution;

class UserProperty extends Model
{
    protected $table    = 'users_properties';
    protected $fillable = [
        'user_id',
        'property_type_id',
        'name',
        'latitude',
        'altitude',
        'is_predetermined'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function propertyType() {
        return $this->hasOne(PropertyType::class, 'id', 'property_type_id');
    }

    public function userPropertyDistribution() {
        return $this->hasMany(UserPropertyDistribution::class);
    }
}
