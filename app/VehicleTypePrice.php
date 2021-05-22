<?php

namespace App;

use App\VehicleType;
use Illuminate\Database\Eloquent\Model;

class VehicleTypePrice extends Model
{
    protected $table = 'vehicles_types_prices';
    protected $fillable =  [
        'vehicle_type_id',
        'key',
        'name',
        'price'
    ];

    public function vehicleType() {
        return $this->belongsTo(VehicleType::class);
    }
}
