<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceVehicleDetail extends Model
{
    protected $table = 'services_vehicles_details';
    protected $fillable =  [
        'service_vehicle_id',
        'vehicle_type_price_id',
    ];

    public function serviceVehicle() {
        return $this->belongsTo(ServiceVehicle::class);
    }

    public function vehicleTypePrice() {
        return $this->belongsTo(VehicleTypePrice::class);
    }
}
