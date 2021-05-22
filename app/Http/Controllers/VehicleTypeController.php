<?php

namespace App\Http\Controllers;

use App\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    public function getAll() {
        $response = array();
        $arrVehiclesTypes = VehicleType::where('active', true)->get();

        if(is_null($arrVehiclesTypes)){
            return response()->json( ['error'=> "No se encontraron tipos de vehículos habilitados."], 403);
        }

        foreach($arrVehiclesTypes as $vehicleType) {
            $type = array(
                "id"                    => $vehicleType->id,
                "name"                  => $vehicleType->name,
                "active"                => $vehicleType->active,
                "prices"                => array()
            );

            foreach($vehicleType->vehiclesTypesPrices as $price) {
                array_push($type['prices'], array(
                    "id"                    => $price->id,
                    "vehicle_type_id"       => $price->vehicle_type_id,
                    "key"                   => $price->key,
                    "name"                  => $price->name,
                    "price"                 => $price->price,
                ));
            }

            array_push($response, $type);
        }


        return response()->json(['vehiclesTypes' => $response], 200);
    }
}
