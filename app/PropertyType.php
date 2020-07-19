<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserProperty;
use App\PropertyTypePrice;

class PropertyType extends Model
{
    protected $table = 'properties_types';
    protected $fillable =  [
        'name',
        'active'
    ];

    public function property() {
        return $this->belongsTo(UserProperty::class);
    }

    public function propertyTypePrice() {
        return $this->hasMany(PropertyTypePrice::class);
    }
}
