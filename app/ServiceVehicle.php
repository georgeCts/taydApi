<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceVehicle extends Model
{
    protected $table    = 'services_vehicles';
    protected $fillable = [
        'service_id',
        'vehicle_type_id',
        'marca',
        'color',
        'latitude',
        'altitude',
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function vehicleType() {
        return $this->belongsTo(VehicleType::class);
    }

    public function details() {
        return $this->hasMany(ServiceVehicleDetail::class);
    }
}
