<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PropertyType;

class PropertyTypeController extends Controller
{
    public function getAll() {
        $response = array();
        $arrPropertiesTypes = PropertyType::where('active', true)->get();

        if(is_null($arrPropertiesTypes)){
            return response()->json( ['error'=> "No se encontraron tipos de inmuebles habilitados."], 403);
        }

        foreach($arrPropertiesTypes as $propertyType) {
            $type = array(
                "id"                    => $propertyType->id,
                "name"                  => $propertyType->name,
                "active"                => $propertyType->active,
                "prices"                => array()
            );

            foreach($propertyType->propertyTypePrice as $price) {
                array_push($type['prices'], array(
                    "id"                    => $price->id,
                    "property_type_id"      => $price->property_type_id,
                    "key"                   => $price->key,
                    "name"                  => $price->name,
                    "price"                 => $price->price,
                ));
            }

            array_push($response, $type);
        }


        return response()->json(['propertyTypes' => $response], 200);
    }
}
