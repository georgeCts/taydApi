<?php

namespace App;

use App\VehicleTypePrice;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'vehicles_types';
    protected $fillable =  [
        'name',
        'active'
    ];

    public function vehiclesTypesPrices() {
        return $this->hasMany(VehicleTypePrice::class);
    }
}
