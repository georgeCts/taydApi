<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PropertyValidator;
use App\UserProperty;

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
        return response()->json(['userProperty'=> $userProperty], 200);
    }

    public function get($id) {
        $userProperty = UserProperty::find($id);
        if(is_null($userProperty)){
            return response()->json( ['error'=> "No se encontro la propiedad con id ".$id], 403);
        }
        $userProperty->propertyType;

        return response()->json($userProperty, 200);
    }
}
