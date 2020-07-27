<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PropertyType;
use App\UserPropertyDistribution;

class PropertyTypePrice extends Model
{
    protected $table = 'properties_types_prices';
    protected $fillable =  [
        'property_type_id',
        'key',
        'name',
        'price'
    ];

    public function propertyType() {
        return $this->belongsTo(PropertyType::class);
    }

    public function usersPropertiesDistribution() {
        return $this->hasMany(UserPropertyDistribution::class);
    }
}
