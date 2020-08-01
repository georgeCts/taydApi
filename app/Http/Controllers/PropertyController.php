<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PropertyValidator;
use App\UserProperty;
use App\User;
use DB;

class PropertyController extends Controller
{
    public $successStatus = 200;
    protected $validaciones;

    public function __construct(PropertyValidator $validaciones) {
        $this->validaciones = $validaciones;
    }

    public function store(Request $request) {
        $validacion = $this->validaciones->store($request);
        $data       = $request->all();

        if($validacion  !== true) {
            return response()->json(['error'=> $validacion->original], 403);
        }

        if(isset($data["id"]) || is_null($request->id)) {
            unset($data['id']);
        }

        $userProperty = UserProperty::create($data);

        if(isset($request->distribution) && !is_null($request->distribution)){
            $this->propertyDistribution($request->distribution, $userProperty);
        }

        if(isset($request->first_login) && !is_null($request->first_login)) {
            $user               = User::find($request->user_id);
            $user->first_login  = true;
            $user->save();

            return response()->json(['user'=> $user], 200);
        }

        return response()->json(['userProperty'=> $userProperty], 200);
    }

    public function get($id) {
        $userProperty = UserProperty::with(['propertyType:id,name'])->where("id", $id)->first();
        if(is_null($userProperty)){
            return response()->json( ['error'=> "No se encontro la propiedad con id ".$id], 403);
        }
        
        $response = array(
            "id"                    => $userProperty->id,
            "user_id"               => $userProperty->user_id,
            "name"                  => $userProperty->name,
            "latitude"              => $userProperty->latitude,
            "altitude"              => $userProperty->altitude,
            "is_predetermined"      => $userProperty->is_predetermined,
            "property_type_id"      => $userProperty->property_type_id,
            "property_type_name"    => $userProperty->propertyType->name,
            "distribution"          => array()
        );

        foreach($userProperty->userPropertyDistribution as $item) {
            array_push($response['distribution'], array(
                "user_property_distribution_id"     => $item->id,
                "property_type_price_id"            => $item->property_type_price_id,
                "quantity"                          => $item->quantity,
                "key"                               => $item->propertyTypePrice->key,
                "price"                             => $item->propertyTypePrice->price,
            ));
        }

        return response()->json($response, 200);
    }

    public function getUserProperties($userId) {
        $properties = UserProperty::with(['propertyType:id,name'])->where("user_id", $userId)->get();

        return response()->json($properties, 200);
    }

    public function getPredetermined($userId) {
        $userProperty = UserProperty::with(['propertyType:id,name'])
                            ->where("user_id", $userId)
                            ->where("is_predetermined", true)->first();
        if(is_null($userProperty)){
            return response()->json( ['error'=> "No se encontró una propiedad predeterminada."], 403);
        }
        
        $response = array(
            "id"                    => $userProperty->id,
            "user_id"               => $userProperty->user_id,
            "name"                  => $userProperty->name,
            "latitude"              => $userProperty->latitude,
            "altitude"              => $userProperty->altitude,
            "is_predetermined"      => $userProperty->is_predetermined,
            "property_type_id"      => $userProperty->property_type_id,
            "property_type_name"    => $userProperty->propertyType->name,
            "distribution"          => array()
        );

        foreach($userProperty->userPropertyDistribution as $item) {
            array_push($response['distribution'], array(
                "user_property_distribution_id"     => $item->id,
                "property_type_price_id"            => $item->property_type_price_id,
                "quantity"                          => $item->quantity,
                "key"                               => $item->propertyTypePrice->key,
                "price"                             => $item->propertyTypePrice->price,
            ));
        }

        return response()->json($response, 200);
    }

    public function setPredetermined($id) {
        $userProperty = UserProperty::find($id);
        if(is_null($userProperty)){
            return response()->json( ['error'=> "No se encontro la propiedad con id ".$id], 403);
        }

        $userProperty->is_predetermined = true;
        $userProperty->save();

        DB::table('users_properties')
                        ->where('id', '!=', $id)
                        ->where('user_id', $userProperty->user_id)
                        ->update(['is_predetermined' => false]);

        return response()->json(['message' => 'Operación exitosa.'], 200);
    }

    private function propertyDistribution(array $data, UserProperty $userProperty) {
        $success = true;

        try {
            foreach($data as $value) {
                if($value['id'] == 0) {
                    DB::table('users_properties_distribution')->insert([
                        'user_property_id'          => $userProperty->id,
                        'property_type_price_id'    => $value['property_type_price_id'],
                        'quantity'                  => $value['quantity'],
                        'created_at'                => now(),
                        'updated_at'                => now(),
                    ]);
                } else {
                    DB::table('users_properties_distribution')
                        ->where('id', $value['id'])
                        ->update([
                            'quantity'      => $value['quantity'],
                            'updated_at'    => now()
                        ]);
                }
            }
        } catch (Exception $ex){
            $success = false;
        }

        return $success;
    }
}
